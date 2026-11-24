<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تعديل بيانات المعلم</title>

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

        .sidebar {
            width: 260px;
            min-width: 260px;
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
        }

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
            line-height: 1.8;
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

        .form-box {
            max-width: 950px;
            background: white;
            padding: 28px;
            border-radius: 15px;
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
            min-width: 0;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
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
        }

        input:disabled,
        select:disabled {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }

        input[readonly] {
            background: #f1f5f9;
            color: #475569;
        }

        .hint {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.8;
        }

        .required {
            color: #dc2626;
        }

        .section-title {
            grid-column: 1 / -1;
            font-size: 17px;
            font-weight: 700;
            color: #111827;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
            margin-top: 8px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 15px 18px;
            border-radius: 10px;
            margin-bottom: 22px;
            line-height: 1.9;
            font-size: 14px;
        }

        .warning-box {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fcd34d;
            padding: 16px 18px;
            border-radius: 10px;
            margin-bottom: 22px;
            line-height: 1.9;
            font-size: 14px;
        }

        .warning-box strong {
            color: #78350f;
        }

        .warning-box a {
            color: #92400e;
            font-weight: 700;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            padding-right: 20px;
        }

        .save-btn {
            margin-top: 25px;
            background: #111827;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .save-btn:hover {
            background: #1f2937;
        }

        .form-footer {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .cancel-btn {
            margin-top: 25px;
            background: #f1f5f9;
            color: #334155;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 9px;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-width: 0;
            }

            .main {
                padding: 18px;
            }

            .topbar {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 650px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full,
            .section-title {
                grid-column: auto;
            }

            .form-box {
                padding: 18px;
            }

            .back-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | التحقق من وجود توزيعات للمعلم
    |--------------------------------------------------------------------------
    */

    $assignmentsCount = $teacher->assignments_count ?? 0;

    $hasAssignments = $assignmentsCount > 0;

    /*
    |--------------------------------------------------------------------------
    | أسماء المراحل التعليمية
    |--------------------------------------------------------------------------
    */

    $stageNames = [
        'primary' => 'المرحلة الابتدائية',
        'middle' => 'المرحلة المتوسطة / الإعدادية',
        'secondary' => 'المرحلة الثانوية',
    ];

@endphp

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        {{-- ===================================================== --}}
        {{-- رأس الصفحة --}}
        {{-- ===================================================== --}}

        <div class="topbar">

            <div>
                <h2>تعديل بيانات المعلم</h2>

                <p>
                    تحديث البيانات الشخصية وحساب تسجيل الدخول
                    والمعلومات الأكاديمية للمعلم.
                </p>
            </div>

            <a
                href="{{ route('teachers.index') }}"
                class="back-btn"
            >
                العودة للمعلمين
            </a>

        </div>

        {{-- ===================================================== --}}
        {{-- رسائل النجاح --}}
        {{-- ===================================================== --}}

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif

        {{-- ===================================================== --}}
        {{-- رسائل الخطأ --}}
        {{-- ===================================================== --}}

        @if(session('error'))

            <div class="alert alert-error">
                {{ session('error') }}
            </div>

        @endif

        {{-- ===================================================== --}}
        {{-- أخطاء التحقق --}}
        {{-- ===================================================== --}}

        @if($errors->any())

            <div class="alert alert-error">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        {{-- ===================================================== --}}
        {{-- معلومات المعلم --}}
        {{-- ===================================================== --}}

        <div class="info-box">

            <strong>معلومات المعلم:</strong>

            <br>

            الاسم:
            <strong>
                {{ $teacher->user?->name ?? 'غير محدد' }}
            </strong>

            <br>

            الرقم الوظيفي:
            <strong>
                {{ $teacher->employee_number ?? 'غير محدد' }}
            </strong>

            <br>

            المادة الأساسية:
            <strong>
                {{ $teacher->subject?->name ?? 'غير محددة' }}
            </strong>

            <br>

            عدد التوزيعات التدريسية:
            <strong>
                {{ $assignmentsCount }}
            </strong>

        </div>

        {{-- ===================================================== --}}
        {{-- تنبيه التوزيعات --}}
        {{-- ===================================================== --}}

        @if($hasAssignments)

            <div class="warning-box">

                <strong>تنبيه مهم:</strong>

                هذا المعلم مرتبط حاليًا بـ

                <strong>
                    {{ $assignmentsCount }}
                </strong>

                توزيع تدريسي.

                <br>

                يمكنك تعديل اسمه وبيانات حسابه ورقم هاتفه
                وتخصصه وكلمة المرور.

                <br>

                لكن لا يمكن تغيير
                <strong>المادة الأساسية أو المرحلة التعليمية</strong>
                قبل معالجة توزيعاته الحالية.

                <br>

                لإدارة التوزيعات، انتقلي إلى

                <a href="{{ route('teacher-assignments.index') }}">
                    توزيع المعلمين
                </a>.

            </div>

        @else

            <div class="info-box">

                <strong>ملاحظة:</strong>

                هذا المعلم غير مرتبط بأي توزيع تدريسي حاليًا.

                يمكنك تغيير مادته الأساسية أو مرحلته التعليمية،
                ثم توزيعه على الصفوف والشعب المناسبة.

            </div>

        @endif

        {{-- ===================================================== --}}
        {{-- نموذج التعديل --}}
        {{-- ===================================================== --}}

        <div class="form-box">

            <form
                method="POST"
                action="{{ route('teachers.update', $teacher) }}"
            >

                @csrf

                @method('PUT')

                <div class="form-grid">

                    {{-- ============================================= --}}
                    {{-- القسم الأول: البيانات الشخصية --}}
                    {{-- ============================================= --}}

                    <div class="section-title">
                        البيانات الشخصية
                    </div>

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
                            value="{{ old('name', $teacher->user?->name) }}"
                            maxlength="255"
                            required
                        >

                    </div>

                    {{-- رقم الهاتف --}}

                    <div class="form-group">

                        <label for="phone">
                            رقم الهاتف
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $teacher->user?->phone) }}"
                            maxlength="30"
                        >

                    </div>

                    {{-- ============================================= --}}
                    {{-- القسم الثاني: المعلومات الأكاديمية --}}
                    {{-- ============================================= --}}

                    <div class="section-title">
                        المعلومات الأكاديمية
                    </div>

                    {{-- الرقم الوظيفي --}}

                    <div class="form-group">

                        <label for="employee_number">
                            الرقم الوظيفي
                        </label>

                        <input
                            type="text"
                            id="employee_number"
                            value="{{ $teacher->employee_number ?? 'غير محدد' }}"
                            readonly
                        >

                        <span class="hint">
                            الرقم الوظيفي يُنشأ تلقائيًا ولا يمكن تعديله.
                        </span>

                    </div>

                    {{-- المادة الدراسية --}}

                    <div class="form-group">

                        <label for="subject_id">
                            المادة الدراسية الأساسية
                            <span class="required">*</span>
                        </label>

                        <select
                            id="subject_id"
                            name="subject_id"
                            required
                            @disabled($hasAssignments)
                        >

                            <option value="">
                                اختر المادة
                            </option>

                            @foreach($subjects as $subject)

                                <option
                                    value="{{ $subject->id }}"
                                    @selected(
                                        old(
                                            'subject_id',
                                            $teacher->subject_id
                                        ) == $subject->id
                                    )
                                >
                                    {{ $subject->name }}
                                </option>

                            @endforeach

                        </select>

                        @if($hasAssignments)

                            <input
                                type="hidden"
                                name="subject_id"
                                value="{{ $teacher->subject_id }}"
                            >

                            <span class="hint">
                                لا يمكن تغيير المادة لأن المعلم مرتبط بتوزيعات تدريسية.
                            </span>

                        @else

                            <span class="hint">
                                لكل معلم مادة دراسية أساسية واحدة.
                            </span>

                        @endif

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
                            required
                            @disabled($hasAssignments)
                        >

                            <option value="">
                                اختر المرحلة التعليمية
                            </option>

                            @foreach($stageNames as $stageValue => $stageLabel)

                                <option
                                    value="{{ $stageValue }}"
                                    @selected(
                                        old(
                                            'education_stage',
                                            $teacher->education_stage
                                        ) === $stageValue
                                    )
                                >
                                    {{ $stageLabel }}
                                </option>

                            @endforeach

                        </select>

                        @if($hasAssignments)

                            <input
                                type="hidden"
                                name="education_stage"
                                value="{{ $teacher->education_stage }}"
                            >

                            <span class="hint">
                                لا يمكن تغيير المرحلة التعليمية قبل معالجة التوزيعات الحالية.
                            </span>

                        @else

                            <span class="hint">
                                تظهر للمعلم في صفحة التوزيع الشعب التابعة لهذه المرحلة فقط.
                            </span>

                        @endif

                    </div>

                    {{-- التخصص --}}

                    <div class="form-group">

                        <label for="specialization">
                            التخصص
                        </label>

                        <input
                            type="text"
                            id="specialization"
                            name="specialization"
                            value="{{ old('specialization', $teacher->specialization) }}"
                            maxlength="255"
                        >

                        <span class="hint">
                            مثال: لغة عربية، رياضيات، علوم.
                        </span>

                    </div>

                    {{-- ============================================= --}}
                    {{-- القسم الثالث: حساب تسجيل الدخول --}}
                    {{-- ============================================= --}}

                    <div class="section-title">
                        حساب تسجيل الدخول
                    </div>

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
                            value="{{ old('username', $teacher->user?->username) }}"
                            maxlength="100"
                            autocomplete="off"
                            required
                        >

                        <span class="hint">
                            يستخدم المعلم اسم المستخدم لتسجيل الدخول إلى التطبيق.
                        </span>

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
                            value="{{ old('email', $teacher->user?->email) }}"
                            maxlength="255"
                            required
                        >

                    </div>

                    {{-- كلمة المرور الجديدة --}}

                    <div class="form-group">

                        <label for="password">
                            كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            minlength="8"
                            autocomplete="new-password"
                        >

                        <span class="hint">
                            اتركي الحقل فارغًا إذا لم ترغبي في تغيير كلمة المرور.
                        </span>

                    </div>

                    {{-- تأكيد كلمة المرور --}}

                    <div class="form-group">

                        <label for="password_confirmation">
                            تأكيد كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            minlength="8"
                            autocomplete="new-password"
                        >

                    </div>

                </div>

                {{-- ============================================= --}}
                {{-- أزرار الحفظ والإلغاء --}}
                {{-- ============================================= --}}

                <div class="form-footer">

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        حفظ التعديلات
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

</body>
</html>