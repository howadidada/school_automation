<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * الأدوار الأساسية التي لا يجوز حذفها أو تغيير أسمائها.
     */
    private const PROTECTED_ROLES = [
        'admin',
        'secretary',
        'supervisor',
        'teacher',
        'student',
        'parent',
        'principal',
        'vice_principal',
    ];

    /**
     * الصلاحيات الأساسية التي يجب الاحتفاظ بها لمدير النظام.
     */
    private const ADMIN_REQUIRED_PERMISSIONS = [
        'إدارة المستخدمين',
        'إدارة الأدوار والصلاحيات',
    ];

    public function index()
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->latest()
            ->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'integer',
                'distinct',
                'exists:permissions,id',
            ],
        ]);

        $name = $validated['name'];

        if ($this->isReservedName($name)) {
            throw ValidationException::withMessages([
                'name' => 'هذا الاسم محجوز لدور أساسي في النظام.',
            ]);
        }

        DB::transaction(function () use ($validated) {
            $role = Role::create([
                'name' => $validated['name'],
            ]);

            $role->permissions()->sync(
                $validated['permissions'] ?? []
            );
        });

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم إضافة الدور بنجاح.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();

        $role->load('permissions');

        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'integer',
                'distinct',
                'exists:permissions,id',
            ],
        ]);

        if ($this->isProtectedRole($role)) {
            // لا نسمح بتغيير اسم أي دور أساسي.
            $validated['name'] = $role->name;
        } elseif ($this->isReservedName($validated['name'])) {
            throw ValidationException::withMessages([
                'name' => 'هذا الاسم محجوز لدور أساسي في النظام.',
            ]);
        }

        DB::transaction(function () use ($role, $validated) {
            $role = Role::query()
                ->lockForUpdate()
                ->findOrFail($role->id);

            $permissionIds = $validated['permissions'] ?? [];

            if ($role->name === 'admin') {
                $requiredIds = Permission::whereIn(
                    'name',
                    self::ADMIN_REQUIRED_PERMISSIONS
                )->pluck('id')->all();

                if (count($requiredIds) !== count(self::ADMIN_REQUIRED_PERMISSIONS)) {
                    throw ValidationException::withMessages([
                        'permissions' => 'بعض صلاحيات مدير النظام الأساسية غير موجودة في قاعدة البيانات.',
                    ]);
                }

                // الاحتفاظ بصلاحيات مدير النظام الأساسية.
                $permissionIds = array_values(array_unique(
                    array_merge($permissionIds, $requiredIds)
                ));
            }

            $role->update([
                'name' => $validated['name'],
            ]);

            $role->permissions()->sync($permissionIds);
        });

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم تعديل الدور والصلاحيات بنجاح.');
    }

    public function destroy(Role $role)
    {
        if ($this->isProtectedRole($role)) {
            return redirect()
                ->route('roles.index')
                ->with(
                    'error',
                    'لا يمكن حذف هذا الدور لأنه من الأدوار الأساسية في النظام.'
                );
        }

        if ($role->users()->exists()) {
            return redirect()
                ->route('roles.index')
                ->with(
                    'error',
                    'لا يمكن حذف هذا الدور لأنه مرتبط بمستخدم أو أكثر.'
                );
        }

        DB::transaction(function () use ($role) {
            $role = Role::query()
                ->lockForUpdate()
                ->findOrFail($role->id);

            // إعادة التحقق داخل المعاملة.
            if ($this->isProtectedRole($role) || $role->users()->exists()) {
                throw ValidationException::withMessages([
                    'role' => 'لا يمكن حذف دور أساسي أو دور مرتبط بمستخدمين.',
                ]);
            }

            $role->permissions()->detach();
            $role->delete();
        });

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم حذف الدور بنجاح.');
    }

    private function isProtectedRole(Role $role): bool
    {
        return in_array($role->name, self::PROTECTED_ROLES, true);
    }

    private function isReservedName(string $name): bool
    {
        return in_array(
            mb_strtolower(trim($name)),
            self::PROTECTED_ROLES,
            true
        );
    }
}