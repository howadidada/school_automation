<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إضافة شعبة</title>

    <style>
        /* =========================================
           الإعدادات العامة
        ========================================= */

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
           توزيع الصفحة
        ========================================= */

        .layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
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
            color: white;
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
            transition: background 0.2s;
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

        /* =========================================
           شريط العنوان
        ========================================= */

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 5px;
            color: #111827;
        }

        .topbar p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.7;
        }

        .back-btn {
            display: inline-block;
            background: #e5e7eb;
            color: #1f2937;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
            font-size: 14px;
            white-space: nowrap;
        }

        .back-btn:hover {
            background: #d1d5db;
        }

        /* =========================================
           صندوق النموذج
        ========================================= */

        .form-box {
            width: 100%;
            max-width: 750px;
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        /* =========================================
           الملاحظة التوضيحية
        ========================================= */

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 14px 16px;
            border-radius: 9px;
            margin-bottom: 22px;
            font-size: 13px;
            line-height: 1.8;
            border-right: 4px solid #4f46e5;
        }

        /* =========================================
           حقول النموذج
        ========================================= */

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .required {
            color: #dc2626;
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
            transition: border-color 0.2s;
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.08);
        }

        .help-text {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.7;
        }

        /* =========================================
           أخطاء التحقق
        ========================================= */

        .errors {
            max-width: 750px;
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .errors ul {
            padding-right: 20px;
        }

        .errors li {
            margin-bottom: 5px;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
        }

        .input-error {
            border-color: #dc2626;
        }

        /* =========================================
           أزرار النموذج
        ========================================= */

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
        }

        .save-btn {
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

        .cancel-btn {
            display: inline-block;
            background: #f3f4f6;
            color: #374151;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 9px;
            font-size: 14px;
        }

        .cancel-btn:hover {
            background: #e5e7eb;
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
                padding: 20px;
            }

            .topbar {
                flex-wrap: wrap;
            }

            .form-box {
                max-width: 100%;
                padding: 20px;
            }

            .errors {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {

            .topbar h2 {
                font-size: 22px;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
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

    <!-- =========================================
         القائمة الجانبية المشتركة
    ========================================= -->

    @include('partials.sidebar')

    <!-- =========================================
         المحتوى الرئيسي
    ========================================= -->

    <main class="main">

        <!-- عنوان الصفحة -->

        <div class="topbar">

            <div>
                <h2>إضافة شعبة جديدة</h2>

                <p>
                    إنشاء شعبة وتحديد الصف الدراسي
                    والقسم التابع لها
                </p>
            </div>

            <a
                href="{{ route('sections.index') }}"
                class="back-btn"
            >
                العودة للشعب
            </a>

        </div>

        <!-- =========================================
             عرض أخطاء التحقق
        ========================================= -->

        @if($errors->any())

            <div class="errors">

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif

        <!-- =========================================
             نموذج إضافة شعبة
        ========================================= -->

        <div class="form-box">

            <div class="info-box">

                <strong>ملاحظة:</strong>

                حددي الصف الدراسي والقسم أولًا.

                يمكن إنشاء شعبة بالاسم نفسه في قسمي
                البنين والبنات، لكن لا يمكن تكرار اسم
                الشعبة داخل الصف والقسم نفسيهما.

            </div>

            <form
                method="POST"
                action="{{ route('sections.store') }}"
            >

                @csrf

                <!-- =====================================
                     الصف الدراسي
                ===================================== -->

                <div class="form-group">

                    <label for="school_class_id">

                        الصف الدراسي

                        <span class="required">*</span>

                    </label>

                    <select
                        id="school_class_id"
                        name="school_class_id"
                        class="@error('school_class_id') input-error @enderror"
                        required
                    >

                        <option value="">
                            اختر الصف الدراسي
                        </option>

                        @foreach($classes as $schoolClass)

                            <option
                                value="{{ $schoolClass->id }}"
                                {{ old('school_class_id') == $schoolClass->id ? 'selected' : '' }}
                            >

                                {{ $schoolClass->name }}

                                @if($schoolClass->grade_level)
                                    - {{ $schoolClass->grade_level }}
                                @endif

                                @if($schoolClass->education_stage)
                                    - {{ $schoolClass->education_stage }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    @error('school_class_id')
                        <small class="field-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <!-- =====================================
                     القسم
                ===================================== -->

                <div class="form-group">

                    <label for="gender_section">

                        القسم

                        <span class="required">*</span>

                    </label>

                    <select
                        id="gender_section"
                        name="gender_section"
                        class="@error('gender_section') input-error @enderror"
                        required
                    >

                        <option value="">
                            اختر القسم
                        </option>

                        <option
                            value="boys"
                            {{ old('gender_section') === 'boys' ? 'selected' : '' }}
                        >
                            بنين
                        </option>

                        <option
                            value="girls"
                            {{ old('gender_section') === 'girls' ? 'selected' : '' }}
                        >
                            بنات
                        </option>

                    </select>

                    <small class="help-text">

                        يحدد القسم ما إذا كانت الشعبة
                        تتبع قسم البنين أو قسم البنات.

                        بعد ربط حسابات المشرفين،
                        سيُحدد المشرف المسؤول تلقائيًا
                        بناءً على المرحلة الدراسية والقسم.

                    </small>

                    @error('gender_section')
                        <small class="field-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <!-- =====================================
                     اسم الشعبة
                ===================================== -->

                <div class="form-group">

                    <label for="name">

                        اسم الشعبة

                        <span class="required">*</span>

                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="مثال: أ"
                        maxlength="255"
                        class="@error('name') input-error @enderror"
                        required
                    >

                    <small class="help-text">

                        مثال: أ أو ب أو ج.

                        يمكن استخدام اسم الشعبة نفسه
                        في قسم آخر من الصف الدراسي.

                    </small>

                    @error('name')
                        <small class="field-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <!-- =====================================
                     سعة الشعبة
                ===================================== -->

                <div class="form-group">

                    <label for="capacity">

                        سعة الشعبة

                        <span class="required">*</span>

                    </label>

                    <input
                        type="number"
                        id="capacity"
                        name="capacity"
                        value="{{ old('capacity') }}"
                        min="1"
                        step="1"
                        placeholder="مثال: 30"
                        class="@error('capacity') input-error @enderror"
                        required
                    >

                    <small class="help-text">

                        أدخلي الحد الأقصى لعدد الطلاب
                        المسموح بتسجيلهم في هذه الشعبة.

                        لا يمكن تسجيل طلاب أكثر من
                        السعة المحددة.

                    </small>

                    @error('capacity')
                        <small class="field-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <!-- =====================================
                     أزرار الحفظ والإلغاء
                ===================================== -->

                <div class="form-actions">

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        حفظ الشعبة
                    </button>

                    <a
                        href="{{ route('sections.index') }}"
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