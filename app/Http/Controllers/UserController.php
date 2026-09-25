<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->latest()
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $allowedRoles = [
            'admin',
            'supervisor',
            'principal',
            'vice_principal',
        ];

        $roles = Role::whereIn('name', $allowedRoles)
            ->orderBy('name')
            ->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $allowedRoles = [
            'admin',
            'supervisor',
            'principal',
            'vice_principal',
        ];

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],
        ]);

        $role = Role::findOrFail($validated['role_id']);

        if (!in_array($role->name, $allowedRoles, true)) {
            return back()
                ->withInput()
                ->withErrors([
                    'role_id' =>
                        'هذا الدور لا يمكن إنشاؤه من إدارة المستخدمين العامة. استخدم الصفحة المخصصة له.',
                ]);
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'role_id' => $validated['role_id'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح.');
    }

    public function edit(User $user)
    {
        $allowedRoles = [
            'admin',
            'supervisor',
            'principal',
            'vice_principal',
        ];

        /*
        |--------------------------------------------------------------------------
        | منع تعديل الحسابات المتخصصة من صفحة المستخدمين العامة
        |--------------------------------------------------------------------------
        |
        | المعلم والطالب وولي الأمر لديهم بيانات مرتبطة في جداول مستقلة،
        | ولذلك يتم تعديلهم من صفحاتهم الخاصة.
        |
        */

        if (
            $user->role &&
            in_array(
                $user->role->name,
                ['teacher', 'student', 'parent'],
                true
            )
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'يتم تعديل هذا المستخدم من الصفحة المخصصة لدوره.'
                );
        }

        $roles = Role::whereIn('name', $allowedRoles)
            ->orderBy('name')
            ->get();

        return view(
            'users.edit',
            compact('user', 'roles')
        );
    }

    public function update(Request $request, User $user)
    {
        $allowedRoles = [
            'admin',
            'supervisor',
            'principal',
            'vice_principal',
        ];

        if (
            $user->role &&
            in_array(
                $user->role->name,
                ['teacher', 'student', 'parent'],
                true
            )
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'يتم تعديل هذا المستخدم من الصفحة المخصصة لدوره.'
                );
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],
        ]);

        $role = Role::findOrFail($validated['role_id']);

        if (!in_array($role->name, $allowedRoles, true)) {
            return back()
                ->withInput()
                ->withErrors([
                    'role_id' =>
                        'لا يمكن تعيين هذا الدور من إدارة المستخدمين العامة.',
                ]);
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role_id' => $validated['role_id'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم تعديل بيانات المستخدم بنجاح.');
    }

    public function toggleStatus(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | منع المستخدم من تعطيل حسابه بنفسه
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'لا يمكنك تعطيل حسابك الحالي.'
                );
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $message = $user->is_active
            ? 'تم تفعيل المستخدم بنجاح.'
            : 'تم تعطيل المستخدم بنجاح.';

        return redirect()
            ->route('users.index')
            ->with('success', $message);
    }
}