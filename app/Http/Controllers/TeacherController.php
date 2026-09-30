<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    // ===============================================================
    // عرض جميع المعلمين
    // ===============================================================
    public function index()
    {
        $teachers = Teacher::with([
            'user',
            'subject',
        ])
            ->latest()
            ->get();

        return view(
            'teachers.index',
            compact('teachers')
        );
    }

    // ===============================================================
    // صفحة إضافة معلم
    // ===============================================================
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();

        return view(
            'teachers.create',
            compact('subjects')
        );
    }

    // ===============================================================
    // حفظ معلم جديد
    // ===============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:users,username',
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

            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],

            'education_stage' => [
                'required',
                Rule::in([
                    'primary',
                    'middle',
                    'secondary',
                ]),
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            // =======================================================
            // جلب دور المعلم
            // =======================================================
            $teacherRole = Role::where(
                'name',
                'teacher'
            )->firstOrFail();

            // =======================================================
            // إنشاء حساب المستخدم
            // =======================================================
            $user = new User();

            $user->name =
                $validated['name'];

            $user->username =
                $validated['username'];

            $user->email =
                $validated['email'];

            $user->phone =
                $validated['phone'] ?? null;

            $user->password =
                Hash::make(
                    $validated['password']
                );

            $user->role_id =
                $teacherRole->id;

            $user->is_active = true;

            $user->save();

            // =======================================================
            // إنشاء سجل المعلم
            // =======================================================
            $teacher = new Teacher();

            $teacher->user_id =
                $user->id;

            $teacher->subject_id =
                $validated['subject_id'];

            $teacher->education_stage =
                $validated['education_stage'];

            $teacher->specialization =
                $validated['specialization']
                ?? null;

            /*
             * الرقم الوظيفي يترك فارغًا أولًا
             * حتى يتم إنشاء ID للمعلم.
             */
            $teacher->employee_number = null;

            $teacher->save();

            // =======================================================
            // إنشاء الرقم الوظيفي تلقائيًا
            //
            // مثال:
            // id = 1  -> T-001
            // id = 2  -> T-002
            // id = 15 -> T-015
            // =======================================================
            $teacher->employee_number =
                'T-' . str_pad(
                    (string) $teacher->id,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

            $teacher->save();
        });

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'تم إضافة المعلم بنجاح ويمكنه الآن تسجيل الدخول باسم المستخدم وكلمة المرور.'
            );
    }

    // ===============================================================
    // صفحة تعديل المعلم
    // ===============================================================
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');

        $subjects = Subject::orderBy('name')->get();

        return view(
            'teachers.edit',
            compact(
                'teacher',
                'subjects'
            )
        );
    }

    // ===============================================================
    // تحديث بيانات المعلم
    // ===============================================================
    public function update(
        Request $request,
        Teacher $teacher
    ) {
        $teacher->load('user');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',

                Rule::unique(
                    'users',
                    'username'
                )->ignore(
                    $teacher->user_id
                ),
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $teacher->user_id
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

            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],

            'education_stage' => [
                'required',
                Rule::in([
                    'primary',
                    'middle',
                    'secondary',
                ]),
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(
            function () use (
                $validated,
                $teacher
            ) {
                // ===================================================
                // تحديث حساب المستخدم
                // ===================================================
                $user = $teacher->user;

                $user->name =
                    $validated['name'];

                $user->username =
                    $validated['username'];

                $user->email =
                    $validated['email'];

                $user->phone =
                    $validated['phone'] ?? null;

                // ===================================================
                // تغيير كلمة المرور فقط إذا تم إدخال كلمة جديدة
                // ===================================================
                if (
                    !empty(
                        $validated['password']
                    )
                ) {
                    $user->password =
                        Hash::make(
                            $validated['password']
                        );
                }

                $user->save();

                // ===================================================
                // تحديث بيانات المعلم
                // ===================================================
                $teacher->subject_id =
                    $validated['subject_id'];

                $teacher->education_stage =
                    $validated['education_stage'];

                $teacher->specialization =
                    $validated['specialization']
                    ?? null;

                /*
                 * لا نغيّر employee_number هنا.
                 * الرقم الوظيفي يبقى ثابتًا للمعلم.
                 */

                $teacher->save();
            }
        );

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'تم تعديل بيانات المعلم بنجاح.'
            );
    }

    // ===============================================================
    // حذف المعلم
    // ===============================================================
    public function destroy(
        Teacher $teacher
    ) {
        DB::transaction(
            function () use ($teacher) {

                $teacher->load('user');

                $user = $teacher->user;

                // ===================================================
                // حذف إسنادات المعلم
                // ===================================================
                $teacher
                    ->assignments()
                    ->delete();

                // ===================================================
                // حذف سجل المعلم
                // ===================================================
                $teacher->delete();

                // ===================================================
                // حذف حساب المستخدم والتوكنات
                // ===================================================
                if ($user) {
                    $user
                        ->tokens()
                        ->delete();

                    $user->delete();
                }
            }
        );

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'تم حذف المعلم بنجاح.'
            );
    }
}