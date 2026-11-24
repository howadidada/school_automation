<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Services\AccountActivationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

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
            ->withCount('assignments')
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
    // حفظ معلم جديد دون كلمة مرور
    // ===============================================================
    public function store(
        Request $request,
        AccountActivationService $activationService
    ) {
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

            'subject_id' => [
                'required',
                'integer',
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

        /*
        |--------------------------------------------------------------------------
        | إنشاء حساب المعلم وبياناته
        |--------------------------------------------------------------------------
        |
        | لا يحدد مدير النظام كلمة المرور.
        | يتم إنشاء الحساب بحالة انتظار التفعيل.
        |
        */

        $user = DB::transaction(function () use ($validated) {

            $teacherRole = Role::where(
                'name',
                'teacher'
            )->firstOrFail();

            $user = new User();

            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? null;

            // المعلم يحدد كلمة المرور بنفسه
            $user->password = null;

            // الحساب ينتظر التفعيل
            $user->is_active = false;

            $user->role_id = $teacherRole->id;

            $user->save();

            $teacher = new Teacher();

            $teacher->user_id = $user->id;
            $teacher->subject_id = $validated['subject_id'];
            $teacher->education_stage = $validated['education_stage'];

            $teacher->specialization =
                $validated['specialization'] ?? null;

            $teacher->employee_number = null;

            $teacher->save();

            // توليد الرقم الوظيفي
            $teacher->employee_number =
                'T-' . str_pad(
                    (string) $teacher->id,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

            $teacher->save();

            return $user;
        });

        /*
        |--------------------------------------------------------------------------
        | إرسال رابط التفعيل
        |--------------------------------------------------------------------------
        |
        | يتم الإرسال بعد حفظ الحساب بنجاح.
        | إذا فشل البريد، لا نحذف حساب المعلم.
        |
        */

        try {
            $activationService->send($user);

            return redirect()
                ->route('teachers.index')
                ->with(
                    'success',
                    'تمت إضافة المعلم بنجاح وإرسال رابط التفعيل إلى بريده الإلكتروني.'
                );

        } catch (Throwable $exception) {

            report($exception);

            return redirect()
                ->route('teachers.index')
                ->with(
                    'error',
                    'تم حفظ بيانات المعلم بنجاح، لكن تعذر إرسال رابط التفعيل. '
                    . 'الحساب ما زال بانتظار التفعيل، ويمكن إعادة إرسال الرابط لاحقًا.'
                );
        }
    }

    // ===============================================================
    // صفحة تعديل المعلم
    // ===============================================================
    public function edit(Teacher $teacher)
    {
        $teacher->load([
            'user',
            'subject',
        ]);

        $teacher->loadCount('assignments');

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

            'subject_id' => [
                'required',
                'integer',
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

        /*
        |--------------------------------------------------------------------------
        | حماية مادة المعلم ومرحلته عند وجود توزيعات
        |--------------------------------------------------------------------------
        */

        $hasAssignments = $teacher->assignments()->exists();

        $subjectChanged =
            (int) $teacher->subject_id !==
            (int) $validated['subject_id'];

        $stageChanged =
            $teacher->education_stage !==
            $validated['education_stage'];

        if ($hasAssignments && $subjectChanged) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'لا يمكن تغيير مادة المعلم لأنه مرتبط بتوزيعات تدريسية. '
                    . 'يجب معالجة توزيعاته الحالية أولًا.'
                );
        }

        if ($hasAssignments && $stageChanged) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'لا يمكن تغيير المرحلة التعليمية للمعلم لأنه مرتبط بتوزيعات تدريسية. '
                    . 'يجب معالجة توزيعاته الحالية أولًا.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | تحديث البيانات
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $teacher
        ) {
            $user = $teacher->user;

            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? null;

            // لا يغير مدير النظام كلمة مرور المعلم
            $user->save();

            $teacher->subject_id =
                $validated['subject_id'];

            $teacher->education_stage =
                $validated['education_stage'];

            $teacher->specialization =
                $validated['specialization'] ?? null;

            // الرقم الوظيفي لا يتغير
            $teacher->save();
        });

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'تم تعديل بيانات المعلم بنجاح.'
            );
    }

    // ===============================================================
    // إعادة إرسال رابط تفعيل حساب المعلم
    // ===============================================================
    public function resendActivation(
        Teacher $teacher,
        AccountActivationService $activationService
    ) {
        $teacher->load('user');

        $user = $teacher->user;

        if (!$user) {
            return back()->with(
                'error',
                'لا يوجد حساب مستخدم مرتبط بهذا المعلم.'
            );
        }

        if (!$activationService->isPendingActivation($user)) {
            return back()->with(
                'error',
                'هذا الحساب لا ينتظر التفعيل، لذلك لا يمكن إرسال رابط تفعيل جديد.'
            );
        }

        try {
            $activationService->send($user);

            return back()->with(
                'success',
                'تم إرسال رابط تفعيل جديد إلى بريد المعلم الإلكتروني.'
            );

        } catch (Throwable $exception) {

            report($exception);

            return back()->with(
                'error',
                'تعذر إرسال رابط التفعيل. يرجى التأكد من إعدادات البريد الإلكتروني والمحاولة لاحقًا.'
            );
        }
    }

    // ===============================================================
    // تعطيل وإعادة تنشيط حساب المعلم
    // ===============================================================
    public function toggleStatus(Teacher $teacher)
    {
        $result = DB::transaction(function () use ($teacher) {

            /*
            |--------------------------------------------------------------------------
            | جلب حساب المعلم وقفل السجل أثناء التحديث
            |--------------------------------------------------------------------------
            */

            $user = User::query()
                ->where('id', $teacher->user_id)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                return [
                    'type' => 'error',
                    'message' => 'لا يوجد حساب مستخدم مرتبط بهذا المعلم.',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | منع تنشيط الحساب قبل إكمال التفعيل
            |--------------------------------------------------------------------------
            */

            if (
                !$user->is_active &&
                $user->password === null
            ) {
                return [
                    'type' => 'error',
                    'message' => 'هذا المعلم لم يفعّل حسابه بعد. '
                        . 'يرجى إعادة إرسال رابط التفعيل.',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | تغيير حالة الحساب
            |--------------------------------------------------------------------------
            */

            $wasActive = (bool) $user->is_active;

            $user->is_active = !$wasActive;

            $user->save();

            /*
            |--------------------------------------------------------------------------
            | إلغاء جميع رموز تسجيل الدخول عند تعطيل الحساب
            |--------------------------------------------------------------------------
            |
            | عند تعطيل المعلم، يتم حذف جميع رموز الدخول
            | الخاصة به حتى لا يتمكن من استخدام الجلسات السابقة.
            |
            | عند إعادة التنشيط، يجب تسجيل الدخول من جديد.
            |
            */

            if ($wasActive) {

                $user->tokens()->delete();

            }

            /*
            |--------------------------------------------------------------------------
            | الاحتفاظ بجميع البيانات الأكاديمية
            |--------------------------------------------------------------------------
            |
            | لا يتم حذف المعلم أو تعديل بياناته الأكاديمية.
            | تبقى المادة والمرحلة والتوزيعات محفوظة.
            |
            */

            return [
                'type' => 'success',

                'message' => $wasActive
                    ? 'تم تعطيل حساب المعلم وإنهاء جلسات تسجيل الدخول الخاصة به بنجاح، مع الاحتفاظ بجميع بياناته وتوزيعاته التدريسية.'
                    : 'تمت إعادة تنشيط حساب المعلم بنجاح.',
            ];

        }, 3);

        return redirect()
            ->route('teachers.index')
            ->with(
                $result['type'],
                $result['message']
            );
    }

    // ===============================================================
    // منع الحذف النهائي للمعلم
    // ===============================================================
    public function destroy(Teacher $teacher)
    {
        return redirect()
            ->route('teachers.index')
            ->with(
                'error',
                'الحذف النهائي للمعلمين متوقف لحماية السجلات الأكاديمية. '
                . 'سيتم استخدام التعطيل وإعادة التنشيط بدلًا من الحذف.'
            );
    }
}