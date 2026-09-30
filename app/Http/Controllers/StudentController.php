<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // ===============================================================
    // عرض الطلاب
    // ===============================================================
    public function index()
    {
        $students = Student::with([
            'user',
            'section.schoolClass',
        ])
            ->latest()
            ->get();

        return view(
            'students.index',
            compact('students')
        );
    }

    // ===============================================================
    // صفحة إضافة طالب
    // ===============================================================
    public function create()
    {
        $sections = Section::with('schoolClass')
            ->orderBy('name')
            ->get();

        return view(
            'students.create',
            compact('sections')
        );
    }

    // ===============================================================
    // حفظ طالب جديد
    // ===============================================================
    public function store(Request $request)
    {
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

            'section_id' => [
                'required',
                'exists:sections,id',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                ]),
            ],
        ]);

        DB::transaction(function () use ($validated) {

            // =======================================================
            // جلب دور الطالب
            // =======================================================
            $studentRole = Role::where(
                'name',
                'student'
            )->firstOrFail();

            // =======================================================
            // إنشاء حساب المستخدم
            // =======================================================
            $user = User::create([
                'name' =>
                    $validated['name'],

                'email' =>
                    $validated['email'],

                'phone' =>
                    $validated['phone'] ?? null,

                'password' =>
                    $validated['password'],

                'role_id' =>
                    $studentRole->id,

                'is_active' =>
                    true,
            ]);

            // =======================================================
            // إنشاء سجل الطالب
            // =======================================================
            $student = Student::create([
                'user_id' =>
                    $user->id,

                'section_id' =>
                    $validated['section_id'],

                'student_number' =>
                    'TEMP',

                'date_of_birth' =>
                    $validated['date_of_birth']
                    ?? null,

                'gender' =>
                    $validated['gender']
                    ?? null,
            ]);

            // =======================================================
            // إنشاء الرقم الطلابي تلقائياً
            // =======================================================
            $student->update([
                'student_number' =>
                    'STU' .
                    str_pad(
                        $student->id,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ),
            ]);
        });

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'تم إضافة الطالب بنجاح وتوليد الرقم الطلابي تلقائيًا.'
            );
    }

    // ===============================================================
    // صفحة تعديل الطالب
    // ===============================================================
    public function edit(Student $student)
    {
        $student->load('user');

        $sections = Section::with('schoolClass')
            ->orderBy('name')
            ->get();

        return view(
            'students.edit',
            compact(
                'student',
                'sections'
            )
        );
    }

    // ===============================================================
    // تحديث بيانات الطالب
    // ===============================================================
    public function update(
        Request $request,
        Student $student
    ) {
        $student->load('user');

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

                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $student->user_id
                ),
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

            'section_id' => [
                'required',
                'exists:sections,id',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                ]),
            ],
        ]);

        DB::transaction(
            function () use (
                $validated,
                $student
            ) {
                // ===================================================
                // تحديث بيانات حساب المستخدم
                // ===================================================
                $userData = [
                    'name' =>
                        $validated['name'],

                    'email' =>
                        $validated['email'],

                    'phone' =>
                        $validated['phone'] ?? null,
                ];

                // ===================================================
                // تغيير كلمة المرور فقط إذا تم إدخال كلمة جديدة
                // ===================================================
                if (
                    !empty(
                        $validated['password']
                    )
                ) {
                    $userData['password'] =
                        $validated['password'];
                }

                $student->user->update(
                    $userData
                );

                // ===================================================
                // تحديث بيانات الطالب
                //
                // مهم:
                // إذا لم تصل قيمة الجنس أو تاريخ الميلاد
                // نحافظ على القيمة القديمة ولا نمسحها.
                // ===================================================
                $student->update([
                    'section_id' =>
                        $validated['section_id'],

                    'date_of_birth' =>
                        $validated['date_of_birth']
                        ?? $student->date_of_birth,

                    'gender' =>
                        $validated['gender']
                        ?? $student->gender,
                ]);
            }
        );

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'تم تعديل بيانات الطالب بنجاح.'
            );
    }

    // ===============================================================
    // حذف الطالب
    // ===============================================================
    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {

            $student->load('user');

            $user = $student->user;

            // =======================================================
            // حذف روابط ولي الأمر مع الطالب
            // =======================================================
            $student
                ->parents()
                ->detach();

            // =======================================================
            // حذف سجل الطالب
            // =======================================================
            $student->delete();

            // =======================================================
            // حذف حساب المستخدم المرتبط بالطالب
            // =======================================================
            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'تم حذف الطالب بنجاح.'
            );
    }
}