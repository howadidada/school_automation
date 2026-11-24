<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | رفع الجداول الدراسية</title>

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
            margin-bottom: 5px;
        }

        .topbar p {
            color: #6b7280;
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

        .info-box {
            max-width: 750px;
            background: #eef2ff;
            color: #3730a3;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            line-height: 1.9;
            font-size: 14px;
        }

        .form-box {
            max-width: 750px;
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
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
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
        }

        .hint {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.8;
        }

        .required {
            color: #dc2626;
        }

        .target-box {
            padding: 18px;
            border: 1px solid #e0e7ff;
            background: #f8faff;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .target-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #3730a3;
        }

        .save-btn {
            margin-top: 10px;
            background: #111827;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
        }

        .save-btn:hover {
            background: #1f2937;
        }

        .errors {
            max-width: 750px;
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .errors ul {
            padding-right: 20px;
        }

        .success {
            max-width: 750px;
            background: #dcfce7;
            color: #166534;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        [hidden] {
            display: none !important;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .main {
                padding: 20px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-box {
                padding: 20px;
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
                <h2>رفع جدول جديد</h2>
                <p>
                    إدارة جداول حصص المعلمين والطلاب وجداول الامتحانات
                </p>
            </div>

            <a href="{{ route('schedule-files.index') }}" class="back-btn">
                العودة إلى الجداول
            </a>

        </div>

        @if($errors->any())
            <div class="errors">
                <strong>يرجى تصحيح الأخطاء التالية:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="errors">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <div class="info-box">
            <strong>تعليمات رفع الجداول:</strong>
            <br>

            اختر نوع الجدول أولًا، ثم حدد المعلم أو الشعبة
            التي يرتبط بها الجدول.

            <br>

            • جدول حصص المعلم: خاص بتوزيع حصص معلم محدد.
            <br>

            • جدول حصص الطالب: خاص بالحصص الدراسية لشعبة محددة.
            <br>

            • جدول الامتحانات: خاص بمواعيد امتحانات شعبة محددة.
        </div>

        <div class="form-box">

            <form
                method="POST"
                action="{{ route('schedule-files.store') }}"
                enctype="multipart/form-data"
            >

                @csrf

                <!-- عنوان الجدول -->

                <div class="form-group">

                    <label for="title">
                        عنوان الجدول
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="مثال: جدول الحصص للفصل الدراسي الأول"
                        maxlength="255"
                        required
                    >

                </div>

                <!-- نوع الجدول -->

                <div class="form-group">

                    <label for="type">
                        نوع الجدول
                        <span class="required">*</span>
                    </label>

                    <select
                        id="type"
                        name="type"
                        required
                    >

                        <option value="">
                            اختر نوع الجدول
                        </option>

                        <option
                            value="teacher_schedule"
                            {{ old('type') === 'teacher_schedule' ? 'selected' : '' }}
                        >
                            جدول حصص المعلم
                        </option>

                        <option
                            value="student_schedule"
                            {{ old('type') === 'student_schedule' ? 'selected' : '' }}
                        >
                            جدول حصص الطالب
                        </option>

                        <option
                            value="exam_schedule"
                            {{ old('type') === 'exam_schedule' ? 'selected' : '' }}
                        >
                            جدول الامتحانات
                        </option>

                    </select>

                    <span class="hint">
                        سيتم تحديد الجهة المستهدفة بناءً على نوع الجدول.
                    </span>

                </div>

                <!-- اختيار المعلم -->

                <div
                    class="target-box"
                    id="teacherBox"
                    hidden
                >

                    <div class="target-title">
                        بيانات المعلم المستهدف
                    </div>

                    <div class="form-group">

                        <label for="teacher_id">
                            اختر المعلم
                            <span class="required">*</span>
                        </label>

                        <select
                            id="teacher_id"
                            name="teacher_id"
                            disabled
                        >

                            <option value="">
                                اختر المعلم
                            </option>

                            @foreach($teachers as $teacher)

                                <option
                                    value="{{ $teacher->id }}"
                                    {{ (string) old('teacher_id') === (string) $teacher->id ? 'selected' : '' }}
                                >
                                    {{ $teacher->user?->name ?? 'معلم بدون حساب' }}
                                </option>

                            @endforeach

                        </select>

                        <span class="hint">
                            سيتم ربط جدول الحصص بهذا المعلم فقط.
                        </span>

                    </div>

                </div>

                <!-- اختيار الفصل والشعبة -->

                <div
                    class="target-box"
                    id="sectionBox"
                    hidden
                >

                    <div class="target-title">
                        بيانات الفصل والشعبة
                    </div>

                    <div class="form-group">

                        <label for="section_id">
                            الفصل والشعبة
                            <span class="required">*</span>
                        </label>

                        <select
                            id="section_id"
                            name="section_id"
                            disabled
                        >

                            <option value="">
                                اختر الفصل والشعبة
                            </option>

                            @foreach($sections as $section)

                                <option
                                    value="{{ $section->id }}"
                                    {{ (string) old('section_id') === (string) $section->id ? 'selected' : '' }}
                                >
                                    {{ $section->schoolClass?->name ?? 'فصل غير محدد' }}
                                    —
                                    شعبة {{ $section->name }}
                                </option>

                            @endforeach

                        </select>

                        <span class="hint" id="sectionHint">
                            اختر الشعبة المستهدفة.
                        </span>

                    </div>

                </div>

                <!-- رفع الملف -->

                <div class="form-group">

                    <label for="file">
                        ملف الجدول
                        <span class="required">*</span>
                    </label>

                    <input
                        type="file"
                        id="file"
                        name="file"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                        required
                    >

                    <span class="hint">
                        الملفات المسموحة:
                        PDF، JPG، PNG، Word، Excel.
                        <br>
                        الحد الأقصى لحجم الملف: 10 ميجابايت.
                    </span>

                </div>

                <button type="submit" class="save-btn">
                    رفع الجدول
                </button>

            </form>

        </div>

    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const typeSelect = document.getElementById('type');

        const teacherBox = document.getElementById('teacherBox');
        const sectionBox = document.getElementById('sectionBox');

        const teacherSelect = document.getElementById('teacher_id');
        const sectionSelect = document.getElementById('section_id');

        const sectionHint = document.getElementById('sectionHint');

        function updateTargetFields() {

            const type = typeSelect.value;

            const isTeacher = type === 'teacher_schedule';

            const isSection =
                type === 'student_schedule' ||
                type === 'exam_schedule';

            // إظهار الحقول المناسبة فقط.
            teacherBox.hidden = !isTeacher;
            sectionBox.hidden = !isSection;

            // منع إرسال الحقول غير المستخدمة.
            teacherSelect.disabled = !isTeacher;
            sectionSelect.disabled = !isSection;

            // جعل الحقل المطلوب إجباريًا.
            teacherSelect.required = isTeacher;
            sectionSelect.required = isSection;

            if (type === 'student_schedule') {

                sectionHint.textContent =
                    'اختر الفصل والشعبة التي سيظهر لها جدول الحصص.';

            } else if (type === 'exam_schedule') {

                sectionHint.textContent =
                    'اختر الفصل والشعبة المرتبطة بجدول الامتحانات.';

            } else {

                sectionHint.textContent =
                    'اختر الشعبة المستهدفة.';
            }
        }

        typeSelect.addEventListener('change', function () {

            // مسح الاختيار السابق عند تغيير النوع.
            teacherSelect.value = '';
            sectionSelect.value = '';

            updateTargetFields();
        });

        // استعادة الحقول عند وجود أخطاء تحقق.
        updateTargetFields();

    });
</script>

</body>
</html>