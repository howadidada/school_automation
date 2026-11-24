<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends Controller
{
    /**
     * الأدوار التي يمكن إدارتها من صفحة المستخدمين العامة.
     */
    private function allowedRoles(): array
    {
        return [
            'admin',
            'supervisor',
            'principal',
            'vice_principal',
        ];
    }

    /**
     * الأدوار التي لها صفحات إدارة مستقلة.
     */
    private function specialRoles(): array
    {
        return [
            'teacher',
            'student',
            'parent',
        ];
    }

    /**
     * التحقق من أن الحساب ينتظر التفعيل.
     */
    private function isPendingActivation(User $user): bool
    {
        return !$user->is_active && $user->password === null;
    }

    /**
     * قواعد التحقق من رقم الجوال اليمني.
     *
     * الصيغ المقبولة:
     * 777123456
     * +967777123456
     * 00967777123456
     */
    private function yemeniPhoneRules(): array
    {
        return [
            'nullable',
            'string',
            'regex:/^(?:(?:\+967|00967)?(?:77|78|73|71|70)[0-9]{7})$/',
        ];
    }

    /**
     * إنشاء رابط تفعيل وإرساله إلى البريد الإلكتروني.
     */
    private function sendActivationEmail(User $user): void
    {
        $token = Password::broker()->createToken($user);

        $activationUrl = route(
            'account.activation.show',
            [
                'token' => $token,
                'email' => $user->email,
            ]
        );

        Mail::send(
            'emails.account-activation',
            [
                'user' => $user,
                'activationUrl' => $activationUrl,
            ],
            function ($message) use ($user) {
                $message
                    ->to($user->email)
                    ->subject('تفعيل حسابك في نظام صلة');
            }
        );
    }

    /**
     * عرض المستخدمين مع البحث والتصفية والإحصائيات.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],

            'role_id' => [
                'nullable',
                'integer',
                'exists:roles,id',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'active',
                    'inactive',
                    'pending',
                ]),
            ],
        ]);

        $query = User::with('role');

        /*
        |--------------------------------------------------------------------------
        | البحث بالاسم أو البريد الإلكتروني
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['search'])) {
            $search = trim($validated['search']);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | التصفية حسب الدور الوظيفي
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['role_id'])) {
            $query->where(
                'role_id',
                $validated['role_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | التصفية حسب حالة الحساب
        |--------------------------------------------------------------------------
        |
        | active   : حساب نشط
        | inactive : حساب معطل ولديه كلمة مرور
        | pending  : حساب لم يتم تفعيله ولم ينشئ كلمة مرور
        |
        */

        if (!empty($validated['status'])) {

            if ($validated['status'] === 'active') {

                $query->where(
                    'is_active',
                    true
                );

            } elseif ($validated['status'] === 'pending') {

                $query
                    ->where('is_active', false)
                    ->whereNull('password');

            } elseif ($validated['status'] === 'inactive') {

                $query
                    ->where('is_active', false)
                    ->whereNotNull('password');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | جلب المستخدمين مع ترقيم الصفحات
        |--------------------------------------------------------------------------
        */

        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | الأدوار المستخدمة في التصفية
        |--------------------------------------------------------------------------
        */

        $roles = Role::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | الإحصائيات العامة
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $activeUsers = User::where(
            'is_active',
            true
        )->count();

        $inactiveUsers = User::where(
            'is_active',
            false
        )
            ->whereNotNull('password')
            ->count();

        $pendingUsers = User::where(
            'is_active',
            false
        )
            ->whereNull('password')
            ->count();

        $administrativeUsers = User::whereHas(
            'role',
            function ($query) {
                $query->whereIn(
                    'name',
                    $this->allowedRoles()
                );
            }
        )->count();

        return view('users.index', compact(
            'users',
            'roles',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'pendingUsers',
            'administrativeUsers'
        ));
    }

    /**
     * عرض نموذج إضافة مستخدم.
     */
    public function create()
    {
        $roles = Role::whereIn(
            'name',
            $this->allowedRoles()
        )
            ->orderBy('name')
            ->get();

        return view(
            'users.create',
            compact('roles')
        );
    }

    /**
     * حفظ مستخدم جديد وإرسال رابط التفعيل.
     */
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
                Rule::unique('users', 'email'),
            ],

            'phone' => $this->yemeniPhoneRules(),

            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'education_stage' => [
                'nullable',
                Rule::in([
                    'primary',
                    'middle',
                    'secondary',
                ]),
            ],

            'gender_section' => [
                'nullable',
                Rule::in([
                    'boys',
                    'girls',
                ]),
            ],
        ], [
            'phone.regex' =>
                'يرجى إدخال رقم جوال يمني صحيح، مثل 777123456 أو +967777123456.',
        ]);

        $role = Role::findOrFail(
            $validated['role_id']
        );

        /*
        |--------------------------------------------------------------------------
        | منع إنشاء أدوار غير مسموحة
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $role->name,
            $this->allowedRoles(),
            true
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'role_id' =>
                        'هذا الدور لا يمكن إنشاؤه من إدارة المستخدمين العامة.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | التحقق من المرحلة والقسم حسب الدور
        |--------------------------------------------------------------------------
        */

        if ($role->name === 'supervisor') {
            $request->validate([
                'education_stage' => [
                    'required',
                    Rule::in([
                        'primary',
                        'middle',
                        'secondary',
                    ]),
                ],

                'gender_section' => [
                    'required',
                    Rule::in([
                        'boys',
                        'girls',
                    ]),
                ],
            ]);
        }

        if ($role->name === 'vice_principal') {
            $request->validate([
                'gender_section' => [
                    'required',
                    Rule::in([
                        'boys',
                        'girls',
                    ]),
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | تحديد البيانات حسب الدور
        |--------------------------------------------------------------------------
        */

        $educationStage = $role->name === 'supervisor'
            ? ($validated['education_stage'] ?? null)
            : null;

        $genderSection = in_array(
            $role->name,
            [
                'supervisor',
                'vice_principal',
            ],
            true
        )
            ? ($validated['gender_section'] ?? null)
            : null;

        /*
        |--------------------------------------------------------------------------
        | إنشاء الحساب دون كلمة مرور
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => null,
            'role_id' => $role->id,
            'education_stage' => $educationStage,
            'gender_section' => $genderSection,
            'is_active' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | إرسال رابط التفعيل
        |--------------------------------------------------------------------------
        */

        try {
            $this->sendActivationEmail($user);

        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'تم إنشاء الحساب بحالة غير مفعّل، لكن تعذر إرسال رسالة التفعيل. يمكنك إعادة إرسال الرابط من صفحة إدارة المستخدمين.'
                );
        }

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'تم إنشاء المستخدم وإرسال رابط التفعيل إلى بريده الإلكتروني بنجاح.'
            );
    }

    /**
     * إعادة إرسال رابط تفعيل الحساب.
     */
    public function resendActivation(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | السماح للحسابات الإدارية فقط
        |--------------------------------------------------------------------------
        */

        if (
            !$user->role ||
            !in_array(
                $user->role->name,
                $this->allowedRoles(),
                true
            )
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'لا يمكن إعادة إرسال رابط التفعيل لهذا المستخدم من إدارة المستخدمين العامة.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | منع إعادة الإرسال للحسابات المكتملة
        |--------------------------------------------------------------------------
        */

        if (!$this->isPendingActivation($user)) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'لا يمكن إعادة إرسال رابط التفعيل؛ لأن هذا الحساب لا ينتظر التفعيل.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | التحقق من البريد الإلكتروني
        |--------------------------------------------------------------------------
        */

        if (empty($user->email)) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'لا يمكن إرسال رابط التفعيل لأن المستخدم لا يملك بريدًا إلكترونيًا.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | إنشاء رابط جديد وإرساله
        |--------------------------------------------------------------------------
        */

        try {
            Password::broker()->deleteToken($user);

            $this->sendActivationEmail($user);

        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'تعذر إرسال رابط التفعيل. تحقق من إعدادات البريد الإلكتروني ثم حاول مرة أخرى.'
                );
        }

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'تم إرسال رابط تفعيل جديد إلى البريد الإلكتروني للمستخدم بنجاح. الرابط السابق لم يعد صالحًا.'
            );
    }

    /**
     * عرض نموذج تعديل المستخدم.
     */
    public function edit(User $user)
    {
        if (
            $user->role &&
            in_array(
                $user->role->name,
                $this->specialRoles(),
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

        $roles = Role::whereIn(
            'name',
            $this->allowedRoles()
        )
            ->orderBy('name')
            ->get();

        return view(
            'users.edit',
            compact('user', 'roles')
        );
    }

    /**
     * تحديث بيانات المستخدم دون تعديل كلمة مروره.
     */
    public function update(Request $request, User $user)
    {
        if (
            $user->role &&
            in_array(
                $user->role->name,
                $this->specialRoles(),
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

            'phone' => $this->yemeniPhoneRules(),

            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'education_stage' => [
                'nullable',
                Rule::in([
                    'primary',
                    'middle',
                    'secondary',
                ]),
            ],

            'gender_section' => [
                'nullable',
                Rule::in([
                    'boys',
                    'girls',
                ]),
            ],
        ], [
            'phone.regex' =>
                'يرجى إدخال رقم جوال  صحيح، مثل 777123456 أو +967777123456.',
        ]);

        $role = Role::findOrFail(
            $validated['role_id']
        );

        /*
        |--------------------------------------------------------------------------
        | منع تعيين أدوار غير مسموحة
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $role->name,
            $this->allowedRoles(),
            true
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'role_id' =>
                        'لا يمكن تعيين هذا الدور من إدارة المستخدمين العامة.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | منع المستخدم من تغيير دوره بنفسه
        |--------------------------------------------------------------------------
        */

        if (
            auth()->id() === $user->id &&
            (int) $user->role_id !== (int) $role->id
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'role_id' =>
                        'لا يمكنك تغيير دور حسابك الحالي بنفسك.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | التحقق من المرحلة والقسم
        |--------------------------------------------------------------------------
        */

        if ($role->name === 'supervisor') {
            $request->validate([
                'education_stage' => [
                    'required',
                    Rule::in([
                        'primary',
                        'middle',
                        'secondary',
                    ]),
                ],

                'gender_section' => [
                    'required',
                    Rule::in([
                        'boys',
                        'girls',
                    ]),
                ],
            ]);
        }

        if ($role->name === 'vice_principal') {
            $request->validate([
                'gender_section' => [
                    'required',
                    Rule::in([
                        'boys',
                        'girls',
                    ]),
                ],
            ]);
        }

        $educationStage = $role->name === 'supervisor'
            ? ($validated['education_stage'] ?? null)
            : null;

        $genderSection = in_array(
            $role->name,
            [
                'supervisor',
                'vice_principal',
            ],
            true
        )
            ? ($validated['gender_section'] ?? null)
            : null;

        /*
        |--------------------------------------------------------------------------
        | حماية آخر مدير نظام نشط
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use (
            $user,
            $validated,
            $role,
            $educationStage,
            $genderSection
        ) {
            $adminRole = Role::where(
                'name',
                'admin'
            )->first();

            if ($adminRole) {
                $activeAdminIds = User::where(
                    'role_id',
                    $adminRole->id
                )
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->pluck('id');

                $isActiveAdmin = (
                    (int) $user->role_id ===
                    (int) $adminRole->id
                ) && $user->is_active;

                $changingAwayFromAdmin = (
                    (int) $role->id !==
                    (int) $adminRole->id
                );

                if (
                    $isActiveAdmin &&
                    $changingAwayFromAdmin &&
                    $activeAdminIds->count() <= 1
                ) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'role_id' =>
                                'لا يمكن تغيير دور آخر مدير نظام نشط.',
                        ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | حفظ البريد القديم قبل التعديل
            |--------------------------------------------------------------------------
            */

            $oldEmail = $user->email;

            $emailChanged = $oldEmail !== $validated['email'];

            $pendingActivation = $this->isPendingActivation($user);

            /*
            |--------------------------------------------------------------------------
            | تحديث بيانات المستخدم
            |--------------------------------------------------------------------------
            */

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'role_id' => $role->id,
                'education_stage' => $educationStage,
                'gender_section' => $genderSection,
            ]);

            /*
            |--------------------------------------------------------------------------
            | إبطال رابط التفعيل القديم عند تغيير البريد
            |--------------------------------------------------------------------------
            */

            if ($emailChanged && $pendingActivation) {
                $oldUser = clone $user;
                $oldUser->email = $oldEmail;

                Password::broker()->deleteToken($oldUser);
            }

            return redirect()
                ->route('users.index')
                ->with(
                    'success',
                    $emailChanged && $pendingActivation
                        ? 'تم تعديل بيانات المستخدم. يجب إعادة إرسال رابط التفعيل إلى البريد الإلكتروني الجديد.'
                        : 'تم تعديل بيانات المستخدم بنجاح.'
                );
        });
    }

    /**
     * تفعيل المستخدم أو تعطيله.
     */
    public function toggleStatus(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | منع تعطيل الحساب الحالي
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

        return DB::transaction(function () use ($user) {
            $adminRole = Role::where(
                'name',
                'admin'
            )->first();

            /*
            |--------------------------------------------------------------------------
            | حماية آخر مدير نظام نشط
            |--------------------------------------------------------------------------
            */

            if ($adminRole) {
                $activeAdminIds = User::where(
                    'role_id',
                    $adminRole->id
                )
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->pluck('id');

                $isActiveAdmin = (
                    (int) $user->role_id ===
                    (int) $adminRole->id
                ) && $user->is_active;

                if (
                    $isActiveAdmin &&
                    $activeAdminIds->count() <= 1
                ) {
                    return redirect()
                        ->route('users.index')
                        ->with(
                            'error',
                            'لا يمكن تعطيل آخر مدير نظام نشط.'
                        );
                }
            }

            $user->refresh();

            /*
            |--------------------------------------------------------------------------
            | منع التفعيل قبل تعيين كلمة المرور
            |--------------------------------------------------------------------------
            */

            if (
                !$user->is_active &&
                $user->password === null
            ) {
                return redirect()
                    ->route('users.index')
                    ->with(
                        'error',
                        'لا يمكن تفعيل هذا الحساب قبل أن ينشئ المستخدم كلمة مروره من رابط التفعيل.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | تغيير حالة الحساب
            |--------------------------------------------------------------------------
            */

            $user->update([
                'is_active' => !$user->is_active,
            ]);

            $message = $user->is_active
                ? 'تم تفعيل المستخدم بنجاح.'
                : 'تم تعطيل المستخدم بنجاح.';

            return redirect()
                ->route('users.index')
                ->with('success', $message);
        });
    }
}