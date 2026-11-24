<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Services\AccountActivationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class ParentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | عرض أولياء الأمور
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $parents = ParentModel::with([
            'user',
            'students.user',
        ])
            ->latest()
            ->get();

        return view('parents.index', compact('parents'));
    }

    /*
    |--------------------------------------------------------------------------
    | صفحة إضافة ولي أمر
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $students = Student::with('user')
            ->orderBy('id')
            ->get();

        return view(
            'parents.create',
            compact('students')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | حفظ ولي أمر جديد
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        AccountActivationService $activationService
    ) {
        $validated = $request->validate(
            $this->parentRules(),
            $this->validationMessages()
        );

        /*
        |--------------------------------------------------------------------------
        | إنشاء حساب ولي الأمر وربطه بالطلاب
        |--------------------------------------------------------------------------
        */

        $user = DB::transaction(function () use ($validated) {

            $parentRole = Role::where(
                'name',
                'parent'
            )->firstOrFail();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => null,
                'role_id' => $parentRole->id,
                'is_active' => false,
            ]);

            $parent = ParentModel::create([
                'user_id' => $user->id,
            ]);

            $this->attachStudents(
                $parent,
                $validated['students'] ?? [],
                $validated['relations'] ?? []
            );

            return $user;

        }, 3);

        /*
        |--------------------------------------------------------------------------
        | إرسال رابط التفعيل بعد حفظ البيانات
        |--------------------------------------------------------------------------
        */

        $emailSent = false;

        try {
            $activationService->send($user);
            $emailSent = true;
        } catch (Throwable $exception) {
            report($exception);
        }

        $message = 'تمت إضافة ولي الأمر بنجاح.';

        if ($emailSent) {
            $message .= ' تم إرسال رابط التفعيل إلى بريده الإلكتروني.';
        } else {
            $message .= ' تعذر إرسال رابط التفعيل، ويمكن إعادة إرساله لاحقًا.';
        }

        return redirect()
            ->route('parents.index')
            ->with('success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | صفحة تعديل ولي أمر
    |--------------------------------------------------------------------------
    */

    public function edit(ParentModel $parent)
    {
        $parent->load([
            'user',
            'students.user',
        ]);

        $students = Student::with('user')
            ->orderBy('id')
            ->get();

        return view(
            'parents.edit',
            compact('parent', 'students')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | تحديث بيانات ولي الأمر
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ParentModel $parent
    ) {
        $parent->load('user');

        $validated = $request->validate(
            $this->parentRules($parent),
            $this->validationMessages()
        );

        DB::transaction(function () use (
            $validated,
            $parent
        ) {

            /*
            |----------------------------------------------------------
            | تحديث بيانات الحساب
            |----------------------------------------------------------
            */

            $parent->load('user');

            $user = $parent->user;

            $oldEmail = $user->email;

            $newEmail = $validated['email'];

            $emailChanged = $oldEmail !== $newEmail;

            $pendingActivation =
                !$user->is_active &&
                $user->password === null;

            $user->update([
                'name' => $validated['name'],
                'email' => $newEmail,
                'phone' => $validated['phone'] ?? null,
            ]);

            /*
            |----------------------------------------------------------
            | إبطال رابط التفعيل القديم عند تغيير البريد
            |----------------------------------------------------------
            */

            if ($emailChanged && $pendingActivation) {

                $oldUser = clone $user;

                $oldUser->email = $oldEmail;

                Password::broker()->deleteToken($oldUser);
            }

            /*
            |----------------------------------------------------------
            | الحفاظ على ارتباطات ولي الأمر السابقة
            |----------------------------------------------------------
            */

            $this->attachStudents(
                $parent,
                $validated['students'] ?? [],
                $validated['relations'] ?? []
            );

        }, 3);

        return redirect()
            ->route('parents.index')
            ->with(
                'success',
                'تم تعديل بيانات ولي الأمر بنجاح مع الحفاظ على ارتباطاته السابقة.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | إعادة إرسال رابط تفعيل ولي الأمر
    |--------------------------------------------------------------------------
    */

    public function resendActivation(
        ParentModel $parent,
        AccountActivationService $activationService
    ) {
        $parent->load('user');

        $user = $parent->user;

        if (!$user) {
            return redirect()
                ->route('parents.index')
                ->with(
                    'error',
                    'لا يوجد حساب مستخدم مرتبط بولي الأمر.'
                );
        }

        if (
            !$activationService->isPendingActivation($user)
        ) {
            return redirect()
                ->route('parents.index')
                ->with(
                    'error',
                    'لا يمكن إعادة إرسال رابط التفعيل؛ لأن حساب ولي الأمر لا ينتظر التفعيل.'
                );
        }

        try {

            $activationService->send($user);

        } catch (Throwable $exception) {

            report($exception);

            return redirect()
                ->route('parents.index')
                ->with(
                    'error',
                    'تعذر إرسال رابط التفعيل. تحققي من إعدادات البريد الإلكتروني ثم حاولي مرة أخرى.'
                );
        }

        return redirect()
            ->route('parents.index')
            ->with(
                'success',
                'تم إرسال رابط التفعيل إلى البريد الإلكتروني لولي الأمر.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ربط ولي الأمر بالطلاب
    |--------------------------------------------------------------------------
    */

    private function attachStudents(
        ParentModel $parent,
        array $studentIds,
        array $relations
    ): void {

        if (empty($studentIds)) {
            return;
        }

        $syncData = [];

        foreach ($studentIds as $studentId) {

            $relation = $relations[$studentId] ?? null;

            if (
                !in_array(
                    $relation,
                    ['father', 'mother'],
                    true
                )
            ) {
                throw ValidationException::withMessages([
                    'relations' =>
                        'يجب تحديد صلة القرابة (أب أو أم) لكل طالب يتم ربطه.',
                ]);
            }

            /*
            |----------------------------------------------------------
            | منع تكرار الأب أو الأم للطالب
            |----------------------------------------------------------
            */

            $existingRelation = DB::table(
                'parent_students'
            )
                ->where('student_id', $studentId)
                ->where('relation', $relation)
                ->where('parent_id', '!=', $parent->id)
                ->exists();

            if ($existingRelation) {
                throw ValidationException::withMessages([
                    'students' =>
                        'الطالب المحدد مرتبط مسبقًا بولي أمر آخر بنفس صلة القرابة. يرجى تعديل ارتباطه من صفحة الطالب أولًا.',
                ]);
            }

            $syncData[$studentId] = [
                'relation' => $relation,
            ];
        }

        /*
        |----------------------------------------------------------
        | إضافة الارتباطات دون حذف الارتباطات القديمة
        |----------------------------------------------------------
        */

        $parent->students()->syncWithoutDetaching(
            $syncData
        );
    }

    /*
    |--------------------------------------------------------------------------
    | قواعد التحقق من بيانات ولي الأمر
    |--------------------------------------------------------------------------
    */

    private function parentRules(
        ?ParentModel $parent = null
    ): array {

        $emailRule = Rule::unique(
            'users',
            'email'
        );

        if ($parent) {
            $emailRule->ignore(
                $parent->user_id
            );
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                $emailRule,
            ],

            /*
            |----------------------------------------------------------
            | التحقق من رقم الجوال اليمني
            |----------------------------------------------------------
            |
            | الصيغ المقبولة:
            |
            | 777123456
            | +967777123456
            | 00967777123456
            |
            | يبدأ رقم الجوال المحلي بالرقم 7،
            | ويتكون من 9 أرقام.
            |
            | لا نقبل المسافات أو الحروف أو الرموز الأخرى.
            |
            */

            'phone' => [
                'nullable',
                'string',
                'regex:/^(?:(?:\+967|00967)?7[0-9]{8})$/',
            ],

            'students' => [
                'nullable',
                'array',
            ],

            'students.*' => [
                'required',
                'integer',
                'distinct',
                'exists:students,id',
            ],

            'relations' => [
                'nullable',
                'array',
            ],

            'relations.*' => [
                Rule::in(['father', 'mother']),
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | رسائل التحقق باللغة العربية
    |--------------------------------------------------------------------------
    */

    private function validationMessages(): array
    {
        return [
            'name.required' =>
                'يرجى إدخال اسم ولي الأمر.',

            'email.required' =>
                'يرجى إدخال البريد الإلكتروني.',

            'email.email' =>
                'يرجى إدخال بريد إلكتروني صحيح.',

            'email.unique' =>
                'هذا البريد الإلكتروني مستخدم مسبقًا في النظام.',

            'phone.regex' =>
                'رقم الجوال غير صحيح. يجب إدخال رقم مكون من 9 أرقام يبدأ بالرقم 7، مثل 777123456، أو بالصيغة الدولية +967777123456.',

            'phone.string' =>
                'يجب أن يكون رقم الجوال نصًا صحيحًا.',

            'students.*.exists' =>
                'أحد الطلاب المحددين غير موجود في النظام.',

            'students.*.distinct' =>
                'لا يمكن اختيار الطالب نفسه أكثر من مرة.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | حذف ولي الأمر
    |--------------------------------------------------------------------------
    |
    | الحذف النهائي متوقف مؤقتًا لحماية
    | بيانات الطلاب وارتباطاتهم.
    |
    */

    public function destroy(ParentModel $parent)
    {
        return redirect()
            ->route('parents.index')
            ->with(
                'error',
                'تم إيقاف الحذف النهائي لأولياء الأمور مؤقتًا لحماية بيانات الطلاب وارتباطاتهم.'
            );
    }
}