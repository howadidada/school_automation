<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
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
                'exists:permissions,id',
            ],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
        ]);

        $role->permissions()->sync(
            $validated['permissions'] ?? []
        );

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم إضافة الدور بنجاح.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();

        $role->load('permissions');

        return view(
            'roles.edit',
            compact('role', 'permissions')
        );
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->ignore($role->id),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'exists:permissions,id',
            ],
        ]);

        $protectedRoles = [
            'admin',
            'supervisor',
            'teacher',
            'student',
            'parent',
            'principal',
            'vice_principal',
        ];

        if (in_array($role->name, $protectedRoles, true)) {
            $validated['name'] = $role->name;
        }

        $role->update([
            'name' => $validated['name'],
        ]);

        $role->permissions()->sync(
            $validated['permissions'] ?? []
        );

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم تعديل الدور والصلاحيات بنجاح.');
    }

    public function destroy(Role $role)
    {
        $protectedRoles = [
            'admin',
            'supervisor',
            'teacher',
            'student',
            'parent',
            'principal',
            'vice_principal',
        ];

        if (in_array($role->name, $protectedRoles, true)) {
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

        $role->permissions()->detach();

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم حذف الدور بنجاح.');
    }
}