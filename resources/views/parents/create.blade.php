<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إضافة ولي أمر</title>

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
            margin-bottom: 5px;
        }

        .sidebar .brand span {
            color: #cbd5e1;
            font-size: 13px;
        }

        .sidebar .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .sidebar .menu-item {
            display: block;
            text-decoration: none;
            color: #cbd5e1;
            padding: 12px 15px;
            border-radius: 9px;
            font-size: 14px;
        }

        .sidebar .menu-item:hover {
            background: #1f2937;
            color: white;
        }

        .sidebar .menu-item.active {
            background: #2563eb;
            color: white;
        }

        .sidebar .logout-area {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #374151;
        }

        .sidebar .logout-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 9px;
            background: #dc2626;
            color: white;
            cursor: pointer;
        }

        /* المحتوى */

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
        }

        .form-box {
            max-width: 1000px;
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
            font-size: 14px;
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .hint {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.8;
        }

        .info {
            background: #eef2ff;
            color: #3730a3;
            padding: 14px;
            border-radius: 9px;
            margin-bottom: 22px;
            font-size: 13px;
            line-height: 1.9;
        }

        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 28px 0;
        }

        /* قائمة الطلاب */

        .students-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #f9fafb;
            max-height: 420px;
            overflow-y: auto;
            padding: 10px 15px;
        }

        .student-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 180px;
            align-items: center;
            gap: 15px;
            padding: 14px 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .student-item:last-child {
            border-bottom: none;
        }

        .student-choice {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .student-choice input {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        .student-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .student-name {
            font-size: 14px;
            font-weight: 600;
        }

        .student-number {
            color: #6b7280;
            font-size: 12px;
        }

        .relation-select:disabled {
            background: #f3f4f6;
            color: #9ca3af;
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

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
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

            .student-item {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-wrap: wrap;
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
                <h2>إضافة ولي أمر</h2>
                <p>تسجيل ولي أمر جديد وربطه بطالب أو أكثر</p>
            </div>

            <a href="{{ route('parents.index') }}" class="back-btn">
                العودة لأولياء الأمور
            </a>
        </div>

        @if($errors->any())
            <div class="errors">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-box">

            <div class="info">
                عند تسجيل ولي أمر جديد، سيُنشأ حساب غير مفعّل.
                وسيُرسل رابط تفعيل إلى بريده الإلكتروني ليحدد كلمة مروره بنفسه.
                <br>
                يمكنك ربط ولي الأمر بأكثر من طالب، مع تحديد صلة القرابة لكل طالب.
            </div>

            <form
                id="parentCreateForm"
                method="POST"
                action="{{ route('parents.store') }}"
            >

                @csrf

                <h3 class="section-title">البيانات الأساسية</h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="name">اسم ولي الأمر *</label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني *</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="form-group full">
                        <label for="phone">رقم الهاتف</label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            maxlength="30"
                            inputmode="tel"
                            dir="ltr"
                            placeholder="مثال: 777123456"
                        >
                    </div>

                </div>

                <hr class="divider">

                <h3 class="section-title">ربط ولي الأمر بالطلاب</h3>

                <p class="hint" style="margin-bottom: 15px;">
                    اختاري الطلاب الذين تريدين ربطهم بولي الأمر،
                    ثم حددي صلة القرابة لكل طالب.
                    يمكن ترك القائمة فارغة وإضافة الأبناء لاحقًا.
                </p>

                <div class="students-box">

                    @forelse($students as $student)

                        @php
                            $oldStudents = old('students', []);

                            $isSelected = in_array(
                                (string) $student->id,
                                array_map('strval', (array) $oldStudents),
                                true
                            );

                            $selectedRelation = old(
                                'relations.' . $student->id,
                                ''
                            );
                        @endphp

                        <div class="student-item">

                            <label class="student-choice">

                                <input
                                    type="checkbox"
                                    class="student-checkbox"
                                    name="students[]"
                                    value="{{ $student->id }}"
                                    data-student-id="{{ $student->id }}"
                                    {{ $isSelected ? 'checked' : '' }}
                                >

                                <span class="student-details">

                                    <span class="student-name">
                                        {{ $student->user?->name ?? '-' }}
                                    </span>

                                    <span class="student-number">
                                        {{ $student->student_number }}
                                    </span>

                                </span>

                            </label>

                            <select
                                name="relations[{{ $student->id }}]"
                                class="relation-select"
                                data-student-id="{{ $student->id }}"
                                {{ $isSelected ? 'required' : 'disabled' }}
                            >

                                <option value="">صلة القرابة</option>

                                <option
                                    value="father"
                                    {{ $selectedRelation === 'father' ? 'selected' : '' }}
                                >
                                    أب
                                </option>

                                <option
                                    value="mother"
                                    {{ $selectedRelation === 'mother' ? 'selected' : '' }}
                                >
                                    أم
                                </option>

                            </select>

                        </div>

                    @empty

                        <p class="hint" style="padding: 15px;">
                            لا يوجد طلاب مسجلون حاليًا.
                            يمكنك إضافة ولي الأمر الآن وربطه بالطلاب لاحقًا.
                        </p>

                    @endforelse

                </div>

                <div class="buttons">

                    <button
                        type="submit"
                        id="saveButton"
                        class="save-btn"
                    >
                        حفظ ولي الأمر
                    </button>

                    <a
                        href="{{ route('parents.index') }}"
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

    const checkboxes = document.querySelectorAll('.student-checkbox');
    const form = document.getElementById('parentCreateForm');
    const saveButton = document.getElementById('saveButton');

    checkboxes.forEach(function (checkbox) {

        const studentId = checkbox.dataset.studentId;

        const relationSelect = document.querySelector(
            '.relation-select[data-student-id="' + studentId + '"]'
        );

        function updateRelationField() {

            relationSelect.disabled = !checkbox.checked;
            relationSelect.required = checkbox.checked;

            if (!checkbox.checked) {
                relationSelect.value = '';
            }
        }

        checkbox.addEventListener('change', updateRelationField);

        updateRelationField();
    });

    form.addEventListener('submit', function (event) {

        if (!form.checkValidity()) {
            return;
        }

        saveButton.disabled = true;
        saveButton.textContent = 'جارٍ الحفظ...';
    });

});
</script>

</body>
</html>