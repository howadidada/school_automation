<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إضافة معلم جديد</title>

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

        /* =========================================
           التخطيط العام
        ========================================= */

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* =========================================
           القائمة الجانبية
        ========================================= */

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

        .brand {
            margin-bottom: 35px;
            padding: 0 10px;
        }

        .brand h1 {
            font-size: 30px;
            margin-bottom: 5px;
        }

        .brand span {
            font-size: 13px;
            color: #cbd5e1;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            display: block;
            text-decoration: none;
            color: #d1d5db;
            padding: 13px 14px;
            border-radius: 9px;
            font-size: 15px;
        }

        .menu-item:hover,
        .menu-item.active {
            background: #1f2937;
            color: white;
        }

        .logout-area {
            margin-top: auto;
            padding-top: 25px;
        }

        .logout-btn {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 8px;
            background: #ef4444;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        /* =========================================
           المحتوى الرئيسي
        ========================================= */

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
            margin-bottom: 7px;
        }

        .topbar p {
            color: #6b7280;
            line-height: 1.7;
            font-size: 14px;
        }

        .back-btn {
            display: inline-block;
            background: #e5e7eb;
            color: #1f2937;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
            white-space: nowrap;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #d1d5db;
        }

        /* =========================================
           صندوق المعلومات
        ========================================= */

        .info-box {
            max-width: 950px;
            background: #eef2ff;
            color: #3730a3;
            padding: 16px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 2;
            border: 1px solid #c7d2fe;
        }

        /* =========================================
           نموذج إضافة المعلم
        ========================================= */

        .form-box {
            max-width: 950px;
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
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

        .required {
            color: #dc2626;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            background: white;
            color: #1f2937;
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
        }

        input::placeholder {
            color: #9ca3af;
        }

        .hint {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.7;
        }

        /* =========================================
           معلومات تفعيل الحساب
        ========================================= */

        .activation-box {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 17px;
            border-radius: 11px;
            font-size: 14px;
            line-height: 2;
            margin-bottom: 20px;
        }

        /* =========================================
           الأزرار
        ========================================= */

        .form-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .save-btn {
            background: #111827;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
        }

        .save-btn:hover {
            background: #1f2937;
        }

        .save-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .cancel-btn {
            display: inline-block;
            background: #f3f4f6;
            color: #374151;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 9px;
            font-size: 14px;
        }

        .cancel-btn:hover {
            background: #e5e7eb;
        }

        /* =========================================
           رسائل الأخطاء
        ========================================= */

        .errors {
            max-width: 950px;
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .errors ul {
            padding-right: 20px;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
        }

        .input-error {
            border-color: #ef4444 !important;
        }

        /* =========================================
           التصميم المتجاوب
        ========================================= */

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-width: 0;
                min-height: auto;
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
        }

        @media (max-width: 480px) {
            .topbar h2 {
                font-size: 22px;
            }

            .form-actions {
                flex-direction: column;
            }

            .save-btn,
            .cancel-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    {{-- ============================================== --}}
    {{-- القائمة الجانبية --}}
    {{-- ============================================== --}}

    @include('partials.sidebar')

    {{-- ============================================== --}}
    {{-- المحتوى الرئيسي --}}
    {{-- ============================================== --}}

    <main class="main">

        <div class="topbar">

            <div>
                <h2>إضافة معلم جديد</h2>

                <p>
                    تسجيل بيانات المعلم وتحديد مادته الأساسية
                    ومرحلته التعليمية وإنشاء حسابه.
                </p>
            </div>

            <a
                href="{{ route('teachers.index') }}"
                class="back-btn"
            >
                العودة للمعلمين
            </a>

        </div>

        {{-- ============================================== --}}
        {{-- معلومات إنشاء الحساب --}}
        {{-- ============================================== --}}

        <div class="info-box">

            <strong>طريقة إنشاء حساب المعلم:</strong>

            <br>

            يقوم مدير النظام بإدخال بيانات المعلم فقط،
            ثم ينشئ النظام حسابًا بانتظار التفعيل.

            <br>

            يُرسل رابط التفعيل إلى البريد الإلكتروني للمعلم،
            ويقوم المعلم بتحديد كلمة المرور بنفسه.

        </div>

        {{-- ============================================== --}}
        {{-- أخطاء التحقق --}}
        {{-- ============================================== --}}

        @if($errors->any())

            <div class="errors">

                <strong>
                    يرجى تصحيح الأخطاء التالية:
                </strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif

        {{-- ============================================== --}}
        {{-- نموذج إضافة المعلم --}}
        {{-- ============================================== --}}

        <div class="form-box">

            <form
                id="createTeacherForm"
                method="POST"
                action="{{ route('teachers.store') }}"
            >

                @csrf

                {{-- ============================================== --}}
                {{-- البيانات الشخصية --}}
                {{-- ============================================== --}}

                <h3 class="section-title">
                    البيانات الشخصية
                </h3>

                <div class="form-grid">

                    {{-- اسم المعلم --}}

                    <div class="form-group">

                        <label for="name">
                            اسم المعلم
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            maxlength="255"
                            class="@error('name') input-error @enderror"
                            required
                        >

                        @error('name')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- رقم الهاتف --}}

                    <div class="form-group">

                        <label for="phone">
                            رقم الهاتف
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            maxlength="30"
                            dir="ltr"
                            class="@error('phone') input-error @enderror"
                        >

                        @error('phone')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

                {{-- ============================================== --}}
                {{-- المعلومات الأكاديمية --}}
                {{-- ============================================== --}}

                <h3 class="section-title">
                    المعلومات الأكاديمية
                </h3>

                <div class="form-grid">

                    {{-- المادة الدراسية الأساسية --}}

                    <div class="form-group">

                        <label for="subject_id">
                            المادة الدراسية الأساسية
                            <span class="required">*</span>
                        </label>

                        <select
                            id="subject_id"
                            name="subject_id"
                            class="@error('subject_id') input-error @enderror"
                            required
                        >

                            <option value="">
                                اختر المادة
                            </option>

                            @foreach($subjects as $subject)

                                <option
                                    value="{{ $subject->id }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}
                                >
                                    {{ $subject->name }}
                                </option>

                            @endforeach

                        </select>

                        <span class="hint">
                            لكل معلم مادة أساسية واحدة،
                            ويمكن توزيعه على عدة صفوف وشعب
                            ضمن مرحلته التعليمية.
                        </span>

                        @error('subject_id')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- المرحلة التعليمية --}}

                    <div class="form-group">

                        <label for="education_stage">
                            المرحلة التعليمية
                            <span class="required">*</span>
                        </label>

                        <select
                            id="education_stage"
                            name="education_stage"
                            class="@error('education_stage') input-error @enderror"
                            required
                        >

                            <option value="">
                                اختر المرحلة التعليمية
                            </option>

                            <option
                                value="primary"
                                {{ old('education_stage') === 'primary' ? 'selected' : '' }}
                            >
                                المرحلة الابتدائية
                            </option>

                            <option
                                value="middle"
                                {{ old('education_stage') === 'middle' ? 'selected' : '' }}
                            >
                                المرحلة المتوسطة / الإعدادية
                            </option>

                            <option
                                value="secondary"
                                {{ old('education_stage') === 'secondary' ? 'selected' : '' }}
                            >
                                المرحلة الثانوية
                            </option>

                        </select>

                        @error('education_stage')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- التخصص --}}

                    <div class="form-group full">

                        <label for="specialization">
                            التخصص
                        </label>

                        <input
                            type="text"
                            id="specialization"
                            name="specialization"
                            value="{{ old('specialization') }}"
                            maxlength="255"
                            placeholder="مثال: لغة عربية، رياضيات، علوم"
                            class="@error('specialization') input-error @enderror"
                        >

                        @error('specialization')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

                {{-- ============================================== --}}
                {{-- حساب تسجيل الدخول --}}
                {{-- ============================================== --}}

                <h3 class="section-title">
                    حساب تسجيل الدخول
                </h3>

                <div class="form-grid">

                    {{-- اسم المستخدم --}}

                    <div class="form-group">

                        <label for="username">
                            اسم المستخدم
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            maxlength="100"
                            autocomplete="off"
                            placeholder="مثال: ahmed_teacher"
                            class="@error('username') input-error @enderror"
                            required
                        >

                        <span class="hint">
                            يستخدم المعلم هذا الاسم لتسجيل الدخول
                            إلى تطبيق صلة.
                        </span>

                        @error('username')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- البريد الإلكتروني --}}

                    <div class="form-group">

                        <label for="email">
                            البريد الإلكتروني
                            <span class="required">*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            maxlength="255"
                            dir="ltr"
                            autocomplete="off"
                            placeholder="example@email.com"
                            class="@error('email') input-error @enderror"
                            required
                        >

                        <span class="hint">
                            سيُرسل رابط تفعيل الحساب إلى هذا البريد.
                        </span>

                        @error('email')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

                {{-- ============================================== --}}
                {{-- معلومات التفعيل --}}
                {{-- ============================================== --}}

                <div class="activation-box">

                    <strong>
                        تفعيل الحساب وكلمة المرور
                    </strong>

                    <br>

                    لن يقوم مدير النظام بإنشاء كلمة مرور للمعلم.

                    <br>

                    بعد حفظ البيانات، سيُرسل رابط التفعيل إلى
                    البريد الإلكتروني المسجل.

                    <br>

                    يفتح المعلم الرابط ويحدد كلمة المرور بنفسه،
                    ثم يستطيع تسجيل الدخول بعد نجاح التفعيل.

                    <br>

                    <strong>ملاحظة:</strong>
                    إذا تعذر إرسال البريد الإلكتروني،
                    ستبقى بيانات المعلم محفوظة وحسابه بانتظار التفعيل.

                </div>

                {{-- ============================================== --}}
                {{-- الإجراءات --}}
                {{-- ============================================== --}}

                <div class="form-actions">

                    <button
                        type="submit"
                        id="saveTeacherBtn"
                        class="save-btn"
                    >
                        حفظ المعلم وإرسال رابط التفعيل
                    </button>

                    <a
                        href="{{ route('teachers.index') }}"
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

        const form = document.getElementById('createTeacherForm');
        const saveButton = document.getElementById('saveTeacherBtn');

        if (!form || !saveButton) {
            return;
        }

        form.addEventListener('submit', function () {

            if (!form.checkValidity()) {
                return;
            }

            saveButton.disabled = true;
            saveButton.textContent = 'جارٍ حفظ بيانات المعلم...';

        });

    });
</script>

</body>
</html>