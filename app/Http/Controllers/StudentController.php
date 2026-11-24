<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Role;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use App\Services\AccountActivationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class StudentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | عرض الطلاب
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $students = Student::with([
            'user',
            'section.schoolClass',
            'parents.user',
        ])
            ->latest()
            ->get();

        return view('students.index', compact('students'));
    }

    /*
    |--------------------------------------------------------------------------
    | صفحة إضافة طالب
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $sections = $this->getSections();
        $parents = $this->getParents();

        return view(
            'students.create',
            compact('sections', 'parents')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | حفظ طالب جديد
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        AccountActivationService $activationService
    ) {
        $validated = $request->validate(
            $this->studentRules()
        );

        $this->validateNewAccountEmails($validated);

        $newUsers = DB::transaction(function () use ($validated) {

            $section = Section::whereKey(
                $validated['section_id']
            )
                ->lockForUpdate()
                ->firstOrFail();

            $this->checkSectionCapacity($section);

            $studentRole = Role::where(
                'name',
                'student'
            )->firstOrFail();

            $studentUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => null,
                'password' => null,
                'role_id' => $studentRole->id,
                'is_active' => false,
            ]);

            $student = Student::create([
                'user_id' => $studentUser->id,
                'section_id' => $section->id,
                'student_number' => 'TEMP-' . $studentUser->id,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ]);

            $student->update([
                'student_number' => 'STU' . str_pad(
                    $student->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                ),
            ]);

            $newUsers = [$studentUser];
            $relations = [];

            foreach (['father', 'mother'] as $relation) {

                $parentData = $validated[$relation] ?? [];

                if (empty($parentData['mode'])) {
                    continue;
                }

                if ($parentData['mode'] === 'existing') {

                    $parent = ParentModel::findOrFail(
                        $parentData['parent_id']
                    );

                } else {

                    $parentRole = Role::where(
                        'name',
                        'parent'
                    )->firstOrFail();

                    $parentUser = User::create([
                        'name' => $parentData['name'],
                        'email' => $parentData['email'],
                        'phone' => $parentData['phone'],
                        'password' => null,
                        'role_id' => $parentRole->id,
                        'is_active' => false,
                    ]);

                    $parent = ParentModel::create([
                        'user_id' => $parentUser->id,
                    ]);

                    $newUsers[] = $parentUser;
                }

                if (isset($relations[$parent->id])) {
                    throw ValidationException::withMessages([
                        $relation . '.parent_id' =>
                            'لا يمكن اختيار ولي الأمر نفسه أبًا وأمًا للطالب.',
                    ]);
                }

                $relations[$parent->id] = [
                    'relation' => $relation,
                ];
            }

            $student->parents()->sync($relations);

            return $newUsers;

        }, 3);

        /*
        |--------------------------------------------------------------------------
        | إرسال روابط التفعيل
        |--------------------------------------------------------------------------
        */

        $sentCount = 0;
        $failedCount = 0;

        foreach ($newUsers as $user) {

            try {

                $activationService->send($user);

                $sentCount++;

            } catch (Throwable $exception) {

                report($exception);

                $failedCount++;
            }
        }

        $message = 'تمت إضافة الطالب وربطه بأولياء أموره بنجاح.';

        if ($sentCount > 0) {
            $message .= ' تم إرسال ' . $sentCount . ' رابط تفعيل.';
        }

        if ($failedCount > 0) {
            $message .= ' تعذر إرسال بعض روابط التفعيل، ويمكن إعادة إرسالها لاحقًا.';
        }

        return redirect()
            ->route('students.index')
            ->with('success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | صفحة تعديل الطالب
    |--------------------------------------------------------------------------
    */

    public function edit(Student $student)
    {
        $student->load([
            'user',
            'parents.user',
        ]);

        $sections = $this->getSections();
        $parents = $this->getParents();

        return view(
            'students.edit',
            compact('student', 'sections', 'parents')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | تحديث بيانات الطالب
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Student $student,
        AccountActivationService $activationService
    ) {
        $student->load('user');

        $validated = $request->validate(
            $this->studentRules($student)
        );

        $this->validateNewAccountEmails($validated);

        /*
        |--------------------------------------------------------------------------
        | قراءة تأكيد فك الارتباط
        |--------------------------------------------------------------------------
        */

        $confirmedParentUnlink = $request->boolean(
            'confirm_parent_unlink'
        );

        $newUsers = DB::transaction(function () use (
            $validated,
            $student,
            $confirmedParentUnlink
        ) {

            /*
            |--------------------------------------------------------------------------
            | قفل الشعب بترتيب ثابت
            |--------------------------------------------------------------------------
            */

            $currentSectionId = (int) $student->section_id;
            $newSectionId = (int) $validated['section_id'];

            $sectionIds = array_values(array_unique([
                $currentSectionId,
                $newSectionId,
            ]));

            sort($sectionIds);

            $lockedSections = Section::whereIn(
                'id',
                $sectionIds
            )
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            /*
            |--------------------------------------------------------------------------
            | قفل الطالب أثناء التعديل
            |--------------------------------------------------------------------------
            */

            $student = Student::whereKey($student->id)
                ->lockForUpdate()
                ->firstOrFail();

            $student->load('user');

            /*
            |--------------------------------------------------------------------------
            | جلب ارتباطات أولياء الأمور الحالية
            |--------------------------------------------------------------------------
            */

            $existingRelations = DB::table('parent_students')
                ->where('student_id', $student->id)
                ->get();

            if (
                (int) $student->section_id !==
                $currentSectionId
            ) {
                throw ValidationException::withMessages([
                    'section_id' =>
                        'تم تغيير شعبة الطالب أثناء العملية. يرجى تحديث الصفحة.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | فحص سعة الشعبة عند النقل
            |--------------------------------------------------------------------------
            */

            if ($newSectionId !== $currentSectionId) {

                $newSection = $lockedSections->get(
                    $newSectionId
                );

                if (!$newSection) {
                    throw ValidationException::withMessages([
                        'section_id' =>
                            'الشعبة المحددة غير موجودة.',
                    ]);
                }

                $this->checkSectionCapacity($newSection);
            }

            /*
            |--------------------------------------------------------------------------
            | تحديث بيانات الطالب
            |--------------------------------------------------------------------------
            */

            $oldEmail = $student->user->email;

            $emailChanged = $oldEmail !== $validated['email'];

            $pendingActivation =
                !$student->user->is_active &&
                $student->user->password === null;

            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | إبطال رابط التفعيل القديم عند تغيير البريد
            |--------------------------------------------------------------------------
            */

            if ($emailChanged && $pendingActivation) {

                $oldUser = clone $student->user;

                $oldUser->email = $oldEmail;

                Password::broker()->deleteToken($oldUser);
            }

            $student->update([
                'section_id' => $newSectionId,
                'date_of_birth' =>
                    $validated['date_of_birth'] ?? null,
                'gender' =>
                    $validated['gender'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | تجهيز ارتباطات أولياء الأمور الجديدة
            |--------------------------------------------------------------------------
            */

            $relations = [];
            $newUsers = [];

            foreach (['father', 'mother'] as $relation) {

                $parentData = $validated[$relation] ?? [];

                if (empty($parentData['mode'])) {
                    continue;
                }

                if ($parentData['mode'] === 'existing') {

                    $parent = ParentModel::findOrFail(
                        $parentData['parent_id']
                    );

                } else {

                    $parentRole = Role::where(
                        'name',
                        'parent'
                    )->firstOrFail();

                    $parentUser = User::create([
                        'name' => $parentData['name'],
                        'email' => $parentData['email'],
                        'phone' => $parentData['phone'],
                        'password' => null,
                        'role_id' => $parentRole->id,
                        'is_active' => false,
                    ]);

                    $parent = ParentModel::create([
                        'user_id' => $parentUser->id,
                    ]);

                    $newUsers[] = $parentUser;
                }

                if (isset($relations[$parent->id])) {
                    throw ValidationException::withMessages([
                        $relation . '.parent_id' =>
                            'لا يمكن اختيار ولي الأمر نفسه مرتين.',
                    ]);
                }

                $relations[$parent->id] = [
                    'relation' => $relation,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | حماية فك الارتباط من جهة Laravel
            |--------------------------------------------------------------------------
            */

            $existingParentIds = $existingRelations
                ->pluck('parent_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $newParentIds = array_map(
                'intval',
                array_keys($relations)
            );

            $removedParentIds = array_diff(
                $existingParentIds,
                $newParentIds
            );

            /*
            | إذا كان التعديل سيزيل ارتباطًا قائمًا،
            | يجب إرسال تأكيد صريح من النموذج.
            */

            if (
                !empty($removedParentIds) &&
                !$confirmedParentUnlink
            ) {
                throw ValidationException::withMessages([
                    'confirm_parent_unlink' =>
                        'يجب تأكيد فك ارتباط ولي الأمر قبل حفظ التعديلات.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | تنفيذ تحديث الارتباطات
            |--------------------------------------------------------------------------
            |
            | sync() يغيّر ارتباطات هذا الطالب فقط.
            | لا يحذف حساب ولي الأمر أو ارتباطاته بطلاب آخرين.
            |
            */

            $student->parents()->sync($relations);

            return $newUsers;

        }, 3);

        /*
        |--------------------------------------------------------------------------
        | إرسال روابط التفعيل لأولياء الأمور الجدد
        |--------------------------------------------------------------------------
        */

        $failedCount = 0;

        foreach ($newUsers as $user) {

            try {

                $activationService->send($user);

            } catch (Throwable $exception) {

                report($exception);

                $failedCount++;
            }
        }

        $message = 'تم تعديل بيانات الطالب وأولياء أموره بنجاح.';

        if ($failedCount > 0) {
            $message .= ' تعذر إرسال بعض روابط التفعيل.';
        }

        return redirect()
            ->route('students.index')
            ->with('success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | إعادة إرسال رابط تفعيل الطالب
    |--------------------------------------------------------------------------
    */

    public function resendActivation(
        Student $student,
        AccountActivationService $activationService
    ) {
        $student->load('user');

        $user = $student->user;

        if (!$user) {
            return redirect()
                ->route('students.index')
                ->with(
                    'error',
                    'لا يوجد حساب مستخدم مرتبط بهذا الطالب.'
                );
        }

        if (!$activationService->isPendingActivation($user)) {
            return redirect()
                ->route('students.index')
                ->with(
                    'error',
                    'لا يمكن إعادة إرسال رابط التفعيل؛ لأن حساب الطالب لا ينتظر التفعيل.'
                );
        }

        try {

            $activationService->send($user);

        } catch (Throwable $exception) {

            report($exception);

            return redirect()
                ->route('students.index')
                ->with(
                    'error',
                    'تعذر إرسال رابط التفعيل. تحققي من إعدادات البريد الإلكتروني ثم حاولي مرة أخرى.'
                );
        }

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'تم إرسال رابط التفعيل إلى البريد الإلكتروني للطالب.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | حذف الطالب
    |--------------------------------------------------------------------------
    */

    public function destroy(Student $student)
    {
        return redirect()
            ->route('students.index')
            ->with(
                'error',
                'تم إيقاف الحذف النهائي للطلاب مؤقتًا لحماية السجلات الأكاديمية. سيتم توفير الأرشفة بدلًا منه.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | قواعد التحقق
    |--------------------------------------------------------------------------
    */

    private function studentRules(?Student $student = null): array
    {
        $emailRule = Rule::unique('users', 'email');

        if ($student) {
            $emailRule->ignore($student->user_id);
        }

        $rules = [
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

            'section_id' => [
                'required',
                'exists:sections,id',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'gender' => [
                'nullable',
                Rule::in(['male', 'female']),
            ],
        ];

        foreach (['father', 'mother'] as $relation) {

            $rules[$relation] = [
                'nullable',
                'array',
            ];

            $rules[$relation . '.mode'] = [
                'nullable',
                Rule::in(['existing', 'new']),
            ];

            $rules[$relation . '.parent_id'] = [
                'required_if:' . $relation . '.mode,existing',
                'nullable',
                'integer',
                'exists:parents,id',
            ];

            $rules[$relation . '.name'] = [
                'required_if:' . $relation . '.mode,new',
                'nullable',
                'string',
                'max:255',
            ];

            $rules[$relation . '.email'] = [
                'required_if:' . $relation . '.mode,new',
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ];

            $rules[$relation . '.phone'] = [
                'required_if:' . $relation . '.mode,new',
                'nullable',
                'string',
                'regex:/^(?:(?:\+967|00967)?(?:77|78|73|71|70)[0-9]{7})$/',
            ];
        }

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | منع تكرار البريد بين الحسابات الجديدة
    |--------------------------------------------------------------------------
    */

    private function validateNewAccountEmails(array $validated): void
    {
        $emails = [
            'email' => $validated['email'],
        ];

        foreach (['father', 'mother'] as $relation) {

            $parentData = $validated[$relation] ?? [];

            if (
                ($parentData['mode'] ?? null) === 'new' &&
                !empty($parentData['email'])
            ) {
                $emails[$relation . '.email'] =
                    $parentData['email'];
            }
        }

        $seen = [];

        foreach ($emails as $field => $email) {

            $normalizedEmail = mb_strtolower(
                trim($email)
            );

            if (isset($seen[$normalizedEmail])) {
                throw ValidationException::withMessages([
                    $field =>
                        'لا يمكن استخدام البريد الإلكتروني نفسه لأكثر من حساب.',
                ]);
            }

            $seen[$normalizedEmail] = true;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | جلب الشعب
    |--------------------------------------------------------------------------
    */

    private function getSections()
    {
        return Section::with('schoolClass')
            ->withCount('students')
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | جلب أولياء الأمور
    |--------------------------------------------------------------------------
    */

    private function getParents()
    {
        return ParentModel::with('user')
            ->orderBy('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | التحقق من سعة الشعبة
    |--------------------------------------------------------------------------
    */

    private function checkSectionCapacity(Section $section): void
    {
        if (
            $section->capacity === null ||
            (int) $section->capacity < 1
        ) {
            throw ValidationException::withMessages([
                'section_id' =>
                    'لا يمكن التسجيل في هذه الشعبة لأن سعتها غير محددة.',
            ]);
        }

        $studentsCount = Student::where(
            'section_id',
            $section->id
        )->count();

        if ($studentsCount >= (int) $section->capacity) {
            throw ValidationException::withMessages([
                'section_id' =>
                    'الشعبة ممتلئة، والحد الأقصى '
                    . $section->capacity
                    . ' طالبًا.',
            ]);
        }
    }
}