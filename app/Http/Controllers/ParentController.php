<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentModel::with([
            'user',
            'students.user',
        ])->latest()->get();

        return view('parents.index', compact('parents'));
    }

    public function create()
    {
        $students = Student::with('user')
            ->orderBy('id')
            ->get();

        return view('parents.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'relation' => ['required', 'string', 'max:100'],
            'students' => ['nullable', 'array'],
            'students.*' => ['exists:students,id'],
        ]);

        DB::transaction(function () use ($validated) {

            $parentRole = Role::where('name', 'parent')
                ->firstOrFail();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => $validated['password'],
                'role_id' => $parentRole->id,
                'is_active' => true,
            ]);

            $parent = ParentModel::create([
                'user_id' => $user->id,
            ]);

            if (!empty($validated['students'])) {

                $syncData = [];

                foreach ($validated['students'] as $studentId) {
                    $syncData[$studentId] = [
                        'relation' => $validated['relation'],
                    ];
                }

                $parent->students()->attach($syncData);
            }
        });

        return redirect()
            ->route('parents.index')
            ->with('success', 'تم إضافة ولي الأمر بنجاح.');
    }

    public function edit(ParentModel $parent)
    {
        $parent->load([
            'user',
            'students',
        ]);

        $students = Student::with('user')
            ->orderBy('id')
            ->get();

        return view(
            'parents.edit',
            compact('parent', 'students')
        );
    }

    public function update(Request $request, ParentModel $parent)
    {
        $parent->load('user');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($parent->user_id),
            ],

            'phone' => ['nullable', 'string', 'max:30'],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'relation' => ['required', 'string', 'max:100'],

            'students' => ['nullable', 'array'],
            'students.*' => ['exists:students,id'],
        ]);

        DB::transaction(function () use ($validated, $parent) {

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $parent->user->update($userData);

            $syncData = [];

            foreach ($validated['students'] ?? [] as $studentId) {
                $syncData[$studentId] = [
                    'relation' => $validated['relation'],
                ];
            }

            $parent->students()->sync($syncData);
        });

        return redirect()
            ->route('parents.index')
            ->with('success', 'تم تعديل بيانات ولي الأمر بنجاح.');
    }

    public function destroy(ParentModel $parent)
    {
        DB::transaction(function () use ($parent) {

            $user = $parent->user;

            // حذف روابط ولي الأمر بالطلاب
            $parent->students()->detach();

            // حذف سجل ولي الأمر
            $parent->delete();

            // حذف حساب المستخدم المرتبط
            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('parents.index')
            ->with('success', 'تم حذف ولي الأمر بنجاح.');
    }
}