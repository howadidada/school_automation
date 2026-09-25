<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with([
            'user',
            'subject',
        ])->latest()->get();

        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();

        return view('teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'specialization' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {

            $teacherRole = Role::where('name', 'teacher')->firstOrFail();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => $validated['password'],
                'role_id' => $teacherRole->id,
                'is_active' => true,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'subject_id' => $validated['subject_id'],
                'specialization' => $validated['specialization'] ?? null,
            ]);
        });

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم إضافة المعلم بنجاح.');
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user');

        $subjects = Subject::orderBy('name')->get();

        return view(
            'teachers.edit',
            compact('teacher', 'subjects')
        );
    }

    public function update(Request $request, Teacher $teacher)
    {
        $teacher->load('user');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($teacher->user_id),
            ],

            'phone' => ['nullable', 'string', 'max:30'],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'subject_id' => ['required', 'exists:subjects,id'],
            'specialization' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $teacher) {

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $teacher->user->update($userData);

            $teacher->update([
                'subject_id' => $validated['subject_id'],
                'specialization' => $validated['specialization'] ?? null,
            ]);
        });

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم تعديل بيانات المعلم بنجاح.');
    }

    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {

            $user = $teacher->user;

            // حذف جميع إسنادات المعلم أولًا
            $teacher->assignments()->delete();

            // حذف سجل المعلم
            $teacher->delete();

            // حذف حساب المستخدم المرتبط بالمعلم
            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم حذف المعلم بنجاح.');
    }
}