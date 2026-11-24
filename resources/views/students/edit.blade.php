<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تعديل بيانات الطالب</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
        }

        body {
            background: #f5f7fb;
            color: #1f2937;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* الشريط الجانبي */

        .sidebar {
            width: 260px;
            min-width: 260px;
            min-height: 100vh;
            background: #111827;
            color: white;
            padding: 25px 18px;
            display: flex;
            flex-direction: column;
        }

        .sidebar .brand {
            text-align: center;
            margin-bottom: 35px;
        }

        .sidebar .brand h1 {
            font-size: 34px;
            color: white;
            margin-bottom: 5px;
        }

        .sidebar .brand span {
            display: inline-block;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.8;
        }

        .sidebar .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .sidebar .menu-item {
            display: block;
            padding: 12px 15px;
            border-radius: 9px;
            text-decoration: none;
            color: #cbd5e1;
            font-size: 14px;
            transition: 0.2s;
        }

        .sidebar .menu-item:hover {
            background: #1f2937;
            color: white;
        }

        .sidebar .menu-item.active {
            background: #2563eb;
            color: white;
            font-weight: 600;
        }

        .sidebar .logout-area {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #374151;
        }

        .sidebar .logout-btn {
            width: 100%;
            padding: 12px;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
        }

        .sidebar .logout-btn:hover {
            background: #b91c1c;
        }

        /* المحتوى الرئيسي */

        .main {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 6px;
        }

        .topbar p {
            color: #6b7280;
            font-size: 14px;
        }

        .back-btn {
            background: #e5e7eb;
            color: #1f2937;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
            white-space: nowrap;
        }

        .back-btn:hover {
            background: #d1d5db;
        }

        /* النموذج */

        .form-box {
            max-width: 1050px;
            background: white;
            border-radius: 15px;
            padding: 28px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            background: white;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
        }

        .readonly-input {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        .input-error {
            border-color: #dc2626 !important;
        }

        .field-error {
            color: #b91c1c;
            font-size: 12px;
            line-height: 1.7;
        }

        /* التنبيهات */

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.9;
        }

        .warning-box {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            color: #92400e;
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.9;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .errors ul {
            padding-right: 20px;
        }

        .section-title {
            font-size: 19px;
            margin-bottom: 10px;
        }

        .section-description {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }

        /* أولياء الأمور */

        .parent-card {
            border: 1px solid #e2e8f0;
            background: #fafbff;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 20px;
        }

        .parent-card h4 {
            font-size: 17px;
            margin-bottom: 18px;
        }

        .parent-fields {
            margin-top: 18px;
        }

        .hidden {
            display: none !important;
        }

        .helper-text {
            font-size: 12px;
            color: #64748b;
            line-height: 1.8;
        }

        .selected-parent-info {
            margin-top: 12px;
            padding: 12px 14px;
            background: #ecfdf5;
            color: #065f46;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.9;
        }

        /* الأزرار */

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .save-btn {
            background: #111827;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .save-btn:hover {
            background: #1f2937;
        }

        .save-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .cancel-btn {
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 9px;
        }

        /* الشاشات الصغيرة */

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-width: 0;
                min-height: auto;
            }

            .sidebar .menu {
                flex: initial;
            }

            .main {
                padding: 18px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }

            .topbar {
                flex-wrap: wrap;
            }

            .form-box {
                padding: 20px;
            }

            .parent-card {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="topbar">

            <div>
                <h2>تعديل بيانات الطالب</h2>

                <p>
                    تعديل بيانات الطالب والشعبة وأولياء الأمور المرتبطين به
                </p>
            </div>

            <a
                href="{{ route('students.index') }}"
                class="back-btn"
            >
                العودة للطلاب
            </a>

        </div>

        @if($errors->any())

            <div class="errors" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif

        <div class="form-box">

            <div class="info-box">

                الرقم الطلابي ثابت ولا يمكن تعديله.

                <br>

                كلمة المرور يديرها صاحب الحساب،
                ولا يتم تغييرها من هذه الصفحة.

                <br>

                تغيير ولي الأمر هنا يغيّر ارتباطه بهذا الطالب فقط،
                دون حذف حسابه أو التأثير على أبنائه الآخرين.

            </div>

            <form
                id="studentEditForm"
                method="POST"
                action="{{ route('students.update', $student) }}"
            >

                @csrf
                @method('PUT')

                <!-- تأكيد فك ارتباط ولي الأمر -->

                <input
                    type="hidden"
                    id="confirm_parent_unlink"
                    name="confirm_parent_unlink"
                    value="0"
                >

                <!-- بيانات الطالب -->

                <h3 class="section-title">
                    بيانات الطالب
                </h3>

                <p class="section-description">
                    عدّلي البيانات المطلوبة،
                    واتركي بقية البيانات كما هي.
                </p>

                <div class="form-grid">

                    <!-- الرقم الطلابي -->

                    <div class="form-group">

                        <label for="student_number">
                            الرقم الطلابي
                        </label>

                        <input
                            type="text"
                            id="student_number"
                            value="{{ $student->student_number }}"
                            class="readonly-input"
                            readonly
                        >

                    </div>

                    <!-- اسم الطالب -->

                    <div class="form-group">

                        <label for="name">
                            اسم الطالب *
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $student->user?->name) }}"
                            maxlength="255"
                            required
                            class="@error('name') input-error @enderror"
                        >

                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror

                    </div>

                    <!-- البريد الإلكتروني -->

                    <div class="form-group">

                        <label for="email">
                            البريد الإلكتروني للطالب *
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $student->user?->email) }}"
                            maxlength="255"
                            required
                            class="@error('email') input-error @enderror"
                        >

                        <span class="helper-text">
                            يُستخدم البريد الإلكتروني لتسجيل الدخول إلى تطبيق صلة.
                        </span>

                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror

                    </div>

                    <!-- الشعبة -->

                    <div class="form-group">

                        <label for="section_id">
                            الشعبة الدراسية *
                        </label>

                        <select
                            id="section_id"
                            name="section_id"
                            required
                            class="@error('section_id') input-error @enderror"
                        >

                            <option value="">
                                اختر الشعبة
                            </option>

                            @foreach($sections as $section)

                                <option
                                    value="{{ $section->id }}"
                                    {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}
                                >

                                    {{ $section->schoolClass?->name ?? '-' }}

                                    -

                                    {{ $section->name }}

                                    ({{ $section->students_count }}/{{ $section->capacity ?? 'غير محددة' }})

                                </option>

                            @endforeach

                        </select>

                        @error('section_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror

                    </div>

                    <!-- الجنس -->

                    <div class="form-group">

                        <label for="gender">
                            الجنس
                        </label>

                        <select
                            id="gender"
                            name="gender"
                        >

                            <option value="">
                                اختر الجنس
                            </option>

                            <option
                                value="male"
                                {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}
                            >
                                ذكر
                            </option>

                            <option
                                value="female"
                                {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}
                            >
                                أنثى
                            </option>

                        </select>

                        @error('gender')
                            <span class="field-error">{{ $message }}</span>
                        @enderror

                    </div>

                    <!-- تاريخ الميلاد -->

                    <div class="form-group">

                        <label for="date_of_birth">
                            تاريخ الميلاد
                        </label>

                        <input
                            type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            value="{{ old('date_of_birth', $student->date_of_birth ? substr((string) $student->date_of_birth, 0, 10) : '') }}"
                            max="{{ now()->toDateString() }}"
                            class="@error('date_of_birth') input-error @enderror"
                        >

                        @error('date_of_birth')
                            <span class="field-error">{{ $message }}</span>
                        @enderror

                    </div>

                </div>

                <hr class="divider">

                <!-- بيانات أولياء الأمور -->

                <h3 class="section-title">
                    بيانات أولياء الأمور
                </h3>

                <p class="section-description">

                    يمكنك الاحتفاظ بالأب والأم الحاليين،
                    أو اختيار حسابات أخرى،
                    أو إنشاء حسابات جديدة.

                </p>

                <div class="warning-box">

                    <strong>تنبيه:</strong>

                    اختيار «بدون ربط» لأب أو أم مرتبطين حاليًا
                    سيؤدي إلى فك ارتباطهما بهذا الطالب عند الحفظ.

                    <br>

                    لن يتم حذف حساب ولي الأمر،
                    ولن تتأثر علاقته بأبنائه الآخرين.

                    <br>

                    سيطلب النظام تأكيدك قبل تنفيذ التغيير.

                </div>

                @foreach([
                    'father' => 'الأب',
                    'mother' => 'الأم'
                ] as $relationKey => $relationLabel)

                    @php
                        $linkedParent = $student->parents->first(
                            fn ($parent) => $parent->pivot?->relation === $relationKey
                        );

                        $defaultMode = $linkedParent ? 'existing' : '';

                        $selectedMode = old(
                            $relationKey . '.mode',
                            $defaultMode
                        );

                        $selectedParentId = old(
                            $relationKey . '.parent_id',
                            $linkedParent?->id
                        );
                    @endphp

                    <div
                        class="parent-card"
                        id="{{ $relationKey }}-card"
                    >

                        <h4>
                            بيانات {{ $relationLabel }}
                        </h4>

                        <div class="form-grid">

                            <!-- طريقة الربط -->

                            <div class="form-group full">

                                <label for="{{ $relationKey }}_mode">
                                    طريقة ربط {{ $relationLabel }}
                                </label>

                                <select
                                    id="{{ $relationKey }}_mode"
                                    name="{{ $relationKey }}[mode]"
                                >

                                    <option
                                        value=""
                                        {{ $selectedMode === '' ? 'selected' : '' }}
                                    >
                                        بدون ربط
                                    </option>

                                    <option
                                        value="existing"
                                        {{ $selectedMode === 'existing' ? 'selected' : '' }}
                                    >
                                        اختيار ولي أمر مسجل مسبقًا
                                    </option>

                                    <option
                                        value="new"
                                        {{ $selectedMode === 'new' ? 'selected' : '' }}
                                    >
                                        إنشاء حساب جديد
                                    </option>

                                </select>

                            </div>

                            <!-- حساب موجود -->

                            <div
                                id="{{ $relationKey }}_existing_fields"
                                class="form-group full parent-fields hidden"
                            >

                                <label for="{{ $relationKey }}_parent_id">
                                    اختر {{ $relationLabel }}
                                </label>

                                <select
                                    id="{{ $relationKey }}_parent_id"
                                    name="{{ $relationKey }}[parent_id]"
                                    class="@error($relationKey . '.parent_id') input-error @enderror"
                                >

                                    <option value="">
                                        اختر ولي الأمر
                                    </option>

                                    @foreach($parents as $parent)

                                        <option
                                            value="{{ $parent->id }}"
                                            data-name="{{ $parent->user?->name ?? '' }}"
                                            data-phone="{{ $parent->user?->phone ?? '' }}"
                                            {{ (string) $selectedParentId === (string) $parent->id ? 'selected' : '' }}
                                        >

                                            {{ $parent->user?->name ?? 'بدون اسم' }}

                                            —

                                            {{ $parent->user?->phone ?: 'بدون رقم هاتف' }}

                                            —

                                            {{ $parent->user?->email ?? '' }}

                                        </option>

                                    @endforeach

                                </select>

                                @error($relationKey . '.parent_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror

                                <div
                                    id="{{ $relationKey }}_selected_info"
                                    class="selected-parent-info hidden"
                                ></div>

                            </div>

                            <!-- حساب جديد -->

                            <div
                                id="{{ $relationKey }}_new_fields"
                                class="parent-fields full hidden"
                            >

                                <div class="form-grid">

                                    <!-- الاسم -->

                                    <div class="form-group">

                                        <label for="{{ $relationKey }}_name">
                                            اسم {{ $relationLabel }} *
                                        </label>

                                        <input
                                            type="text"
                                            id="{{ $relationKey }}_name"
                                            name="{{ $relationKey }}[name]"
                                            value="{{ old($relationKey . '.name') }}"
                                            maxlength="255"
                                            class="@error($relationKey . '.name') input-error @enderror"
                                        >

                                        @error($relationKey . '.name')
                                            <span class="field-error">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <!-- البريد -->

                                    <div class="form-group">

                                        <label for="{{ $relationKey }}_email">
                                            البريد الإلكتروني *
                                        </label>

                                        <input
                                            type="email"
                                            id="{{ $relationKey }}_email"
                                            name="{{ $relationKey }}[email]"
                                            value="{{ old($relationKey . '.email') }}"
                                            maxlength="255"
                                            class="@error($relationKey . '.email') input-error @enderror"
                                        >

                                        @error($relationKey . '.email')
                                            <span class="field-error">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <!-- رقم الجوال -->

                                    <div class="form-group">

                                        <label for="{{ $relationKey }}_phone">
                                            رقم الجوال اليمني *
                                        </label>

                                        <input
                                            type="tel"
                                            id="{{ $relationKey }}_phone"
                                            name="{{ $relationKey }}[phone]"
                                            value="{{ old($relationKey . '.phone') }}"
                                            placeholder="مثال: 777123456"
                                            maxlength="16"
                                            inputmode="tel"
                                            dir="ltr"
                                            pattern="(\+967|00967)?(77|78|73|71|70)[0-9]{7}"
                                            title="أدخلي رقم جوال يمني صحيحًا مثل 777123456 أو +967777123456"
                                            class="@error($relationKey . '.phone') input-error @enderror"
                                        >

                                        <span class="helper-text">
                                            يجب أن يبدأ الرقم بـ 70 أو 71 أو 73 أو 77 أو 78،
                                            ويتكون من 9 أرقام محليًا.
                                            ويمكن استخدام +967 أو 00967.
                                        </span>

                                        <span
                                            id="{{ $relationKey }}_phone_error"
                                            class="field-error hidden"
                                            aria-live="polite"
                                        >
                                            رقم الجوال غير صحيح.
                                            مثال: 777123456 أو +967777123456.
                                        </span>

                                        @error($relationKey . '.phone')
                                            <span class="field-error">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <!-- التفعيل -->

                                    <div class="form-group">

                                        <label>
                                            تفعيل حساب ولي الأمر
                                        </label>

                                        <div
                                            class="info-box"
                                            style="margin-bottom: 0;"
                                        >

                                            عند إنشاء حساب جديد،
                                            سيُرسل رابط تعيين كلمة المرور
                                            إلى البريد الإلكتروني لولي الأمر.

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

                <div class="buttons">

                    <button
                        type="submit"
                        class="save-btn"
                        id="saveButton"
                    >
                        حفظ التعديلات
                    </button>

                    <a
                        href="{{ route('students.index') }}"
                        class="cancel-btn"
                    >
                        إلغاء
                    </a>

                </div>

            </form>

        </div>

    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const relations = ['father', 'mother'];

    /*
    |--------------------------------------------------------------------------
    | حفظ ارتباطات الأب والأم الأصلية
    |--------------------------------------------------------------------------
    */

    const originalParents = {
        father: @json(
            $student->parents
                ->first(fn ($parent) => $parent->pivot?->relation === 'father')
                ?->id
        ),
        mother: @json(
            $student->parents
                ->first(fn ($parent) => $parent->pivot?->relation === 'mother')
                ?->id
        )
    };

    const phonePattern =
        /^(?:(?:\+967|00967)?(?:77|78|73|71|70)[0-9]{7})$/;

    /*
    |--------------------------------------------------------------------------
    | عرض معلومات ولي الأمر المختار
    |--------------------------------------------------------------------------
    */

    function updateSelectedParentInfo(relation) {

        const select = document.getElementById(
            relation + '_parent_id'
        );

        const info = document.getElementById(
            relation + '_selected_info'
        );

        const option = select.options[select.selectedIndex];

        if (!select.value || !option) {
            info.textContent = '';
            info.classList.add('hidden');
            return;
        }

        const name = option.dataset.name || 'غير محدد';
        const phone = option.dataset.phone || 'غير مسجل';

        info.textContent =
            'ولي الأمر: ' + name +
            ' | رقم الهاتف: ' + phone;

        info.classList.remove('hidden');
    }

    /*
    |--------------------------------------------------------------------------
    | إظهار الحقول حسب طريقة الربط
    |--------------------------------------------------------------------------
    */

    function updateParentFields(relation) {

        const modeSelect = document.getElementById(
            relation + '_mode'
        );

        const existingFields = document.getElementById(
            relation + '_existing_fields'
        );

        const newFields = document.getElementById(
            relation + '_new_fields'
        );

        const parentSelect = document.getElementById(
            relation + '_parent_id'
        );

        const nameInput = document.getElementById(
            relation + '_name'
        );

        const emailInput = document.getElementById(
            relation + '_email'
        );

        const phoneInput = document.getElementById(
            relation + '_phone'
        );

        const isExisting = modeSelect.value === 'existing';
        const isNew = modeSelect.value === 'new';

        existingFields.classList.toggle('hidden', !isExisting);
        newFields.classList.toggle('hidden', !isNew);

        parentSelect.required = isExisting;

        nameInput.required = isNew;
        emailInput.required = isNew;
        phoneInput.required = isNew;

        parentSelect.disabled = !isExisting;

        nameInput.disabled = !isNew;
        emailInput.disabled = !isNew;
        phoneInput.disabled = !isNew;

        if (!isNew) {
            clearPhoneError(relation);
        }

        updateSelectedParentInfo(relation);
    }

    /*
    |--------------------------------------------------------------------------
    | إظهار خطأ الهاتف
    |--------------------------------------------------------------------------
    */

    function showPhoneError(relation) {

        const phoneInput = document.getElementById(
            relation + '_phone'
        );

        const error = document.getElementById(
            relation + '_phone_error'
        );

        phoneInput.classList.add('input-error');
        error.classList.remove('hidden');
    }

    /*
    |--------------------------------------------------------------------------
    | إزالة خطأ الهاتف
    |--------------------------------------------------------------------------
    */

    function clearPhoneError(relation) {

        const phoneInput = document.getElementById(
            relation + '_phone'
        );

        const error = document.getElementById(
            relation + '_phone_error'
        );

        phoneInput.classList.remove('input-error');
        error.classList.add('hidden');
    }

    /*
    |--------------------------------------------------------------------------
    | التحقق من رقم الهاتف اليمني
    |--------------------------------------------------------------------------
    */

    function validatePhone(relation) {

        const mode = document.getElementById(
            relation + '_mode'
        ).value;

        const phoneInput = document.getElementById(
            relation + '_phone'
        );

        if (mode !== 'new') {
            clearPhoneError(relation);
            return true;
        }

        const phone = phoneInput.value.trim();

        if (!phonePattern.test(phone)) {
            showPhoneError(relation);
            return false;
        }

        clearPhoneError(relation);
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | تفعيل الأحداث
    |--------------------------------------------------------------------------
    */

    relations.forEach(function (relation) {

        const modeSelect = document.getElementById(
            relation + '_mode'
        );

        const parentSelect = document.getElementById(
            relation + '_parent_id'
        );

        const phoneInput = document.getElementById(
            relation + '_phone'
        );

        modeSelect.addEventListener('change', function () {
            updateParentFields(relation);
        });

        parentSelect.addEventListener('change', function () {
            updateSelectedParentInfo(relation);
        });

        phoneInput.addEventListener('input', function () {
            validatePhone(relation);
        });

        phoneInput.addEventListener('blur', function () {
            validatePhone(relation);
        });

        updateParentFields(relation);
    });

    /*
    |--------------------------------------------------------------------------
    | التحقق قبل الحفظ
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById('studentEditForm');

    const saveButton = document.getElementById('saveButton');

    const confirmParentUnlink = document.getElementById(
        'confirm_parent_unlink'
    );

    form.addEventListener('submit', function (event) {

        /*
        | القيمة الافتراضية: لا يوجد تأكيد لفك الارتباط
        */

        confirmParentUnlink.value = '0';

        const fatherMode = document.getElementById(
            'father_mode'
        ).value;

        const motherMode = document.getElementById(
            'mother_mode'
        ).value;

        const fatherId = document.getElementById(
            'father_parent_id'
        ).value;

        const motherId = document.getElementById(
            'mother_parent_id'
        ).value;

        /*
        |--------------------------------------------------------------------------
        | منع اختيار الحساب نفسه أبًا وأمًا
        |--------------------------------------------------------------------------
        */

        if (
            fatherMode === 'existing' &&
            motherMode === 'existing' &&
            fatherId &&
            motherId &&
            fatherId === motherId
        ) {

            event.preventDefault();

            alert(
                'لا يمكن اختيار ولي الأمر نفسه أبًا وأمًا للطالب.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | التحقق من أرقام الجوال
        |--------------------------------------------------------------------------
        */

        let phonesValid = true;

        relations.forEach(function (relation) {
            if (!validatePhone(relation)) {
                phonesValid = false;
            }
        });

        if (!phonesValid) {

            event.preventDefault();

            alert(
                'يرجى تصحيح أرقام الجوال اليمنية قبل حفظ التعديلات.'
            );

            return;
        }

        if (!form.checkValidity()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | تحديد الارتباطات التي ستُزال
        |--------------------------------------------------------------------------
        */

        const removedRelations = [];

        relations.forEach(function (relation) {

            const originalId = originalParents[relation];

            if (!originalId) {
                return;
            }

            const mode = document.getElementById(
                relation + '_mode'
            ).value;

            const selectedId = document.getElementById(
                relation + '_parent_id'
            ).value;

            const keepingSameParent =
                mode === 'existing' &&
                String(selectedId) === String(originalId);

            if (!keepingSameParent) {
                removedRelations.push(
                    relation === 'father' ? 'الأب' : 'الأم'
                );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | تأكيد فك الارتباط
        |--------------------------------------------------------------------------
        */

        if (removedRelations.length > 0) {

            const message =
                'تنبيه: سيتم فك ارتباط ' +
                removedRelations.join(' و') +
                ' بالطالب الحالي.\n\n' +
                'لن يتم حذف حساب ولي الأمر، ' +
                'ولن تتأثر ارتباطاته بأبنائه الآخرين.\n\n' +
                'هل تريدين المتابعة وحفظ التعديلات؟';

            if (!confirm(message)) {
                event.preventDefault();
                return;
            }

            /*
            | إرسال الموافقة الصريحة إلى Laravel
            */

            confirmParentUnlink.value = '1';
        }

        /*
        |--------------------------------------------------------------------------
        | منع الضغط المتكرر
        |--------------------------------------------------------------------------
        */

        saveButton.disabled = true;
        saveButton.textContent = 'جارٍ حفظ التعديلات...';
    });

});
</script>

</body>
</html>