<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تسجيل الحضور</title>

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

        /* =========================================================
           القائمة الجانبية
        ========================================================= */

        .sidebar {
            width: 260px;
            background: #111827;
            color: white;
            padding: 25px 18px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
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

        /* =========================================================
           المحتوى
        ========================================================= */

        .main {
            flex: 1;
            padding: 30px;
            min-width: 0;
        }

        .topbar {
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

        /* =========================================================
           التنبيهات
        ========================================================= */

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
            line-height: 1.8;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-info {
            background: #e0f2fe;
            color: #075985;
            text-align: center;
        }

        .alert ul {
            padding-right: 20px;
        }

        /* =========================================================
           بطاقة اختيار الشعبة والتاريخ
        ========================================================= */

        .filter-box {
            background: white;
            border-radius: 15px;
            padding: 22px;
            margin-bottom: 25px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1.5fr 1fr;
            gap: 18px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            background: white;
            color: #1f2937;
            font-size: 14px;
            outline: none;
        }

        .form-control:focus {
            border-color: #111827;
        }

        .show-btn {
            width: 100%;
            padding: 11px 18px;
            border: none;
            border-radius: 9px;
            background: #111827;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .show-btn:hover {
            background: #1f2937;
        }

        /* =========================================================
           جدول الطلاب
        ========================================================= */

        .attendance-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .attendance-header {
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .attendance-header h3 {
            font-size: 19px;
            margin-bottom: 5px;
        }

        .attendance-header p {
            color: #6b7280;
            font-size: 13px;
        }

        .attendance-date {
            background: #f3f4f6;
            padding: 8px 13px;
            border-radius: 8px;
            color: #4b5563;
            font-size: 14px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        th {
            background: #f9fafb;
            font-size: 14px;
        }

        td {
            font-size: 14px;
        }

        .student-name {
            font-weight: 600;
        }

        .number-column {
            width: 70px;
            text-align: center;
        }

        .status-column {
            width: 220px;
        }

        .status-select {
            width: 100%;
            padding: 9px 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            outline: none;
        }

        .status-select:focus {
            border-color: #111827;
        }

        /* =========================================================
           أسفل الجدول
        ========================================================= */

        .attendance-footer {
            padding: 18px 22px;
            background: white;
            display: flex;
            justify-content: flex-start;
        }

        .save-btn {
            border: none;
            background: #16a34a;
            color: white;
            padding: 11px 24px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .save-btn:hover {
            background: #15803d;
        }

        /* =========================================================
           الشاشات الصغيرة
        ========================================================= */

        @media (max-width: 900px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }

            .attendance-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    {{-- القائمة الجانبية --}}
    @include('partials.sidebar')

    <main class="main">

        {{-- عنوان الصفحة --}}
        <div class="topbar">

            <h2>تسجيل الحضور</h2>

            <p>
                اختر الشعبة والتاريخ ثم سجل حالة حضور الطلاب
            </p>

        </div>

        {{-- رسالة نجاح --}}
        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif

        {{-- أخطاء التحقق --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
             اختيار الشعبة والتاريخ
        ====================================================== --}}

        <div class="filter-box">

            <form
                id="attendanceFilterForm"
                method="GET"
                action="{{ route('attendances.index') }}"
            >

                <div class="filter-grid">

                    {{-- الشعبة --}}
                    <div class="form-group">

                        <label>
                            الشعبة
                        </label>

                        <select
                            id="sectionSelect"
                            name="section_id"
                            class="form-control"
                            required
                        >

                            <option value="">
                                اختر الشعبة
                            </option>

                            @foreach($sections as $section)

                                <option
                                    value="{{ $section->id }}"
                                    {{ (string) $selectedSectionId === (string) $section->id ? 'selected' : '' }}
                                >
                                    {{ $section->class_name }}
                                    -
                                    {{ $section->section_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- التاريخ --}}
                    <div class="form-group">

                        <label>
                            التاريخ
                        </label>

                        <input
                            id="attendanceDate"
                            type="date"
                            name="attendance_date"
                            class="form-control"
                            value="{{ $attendanceDate }}"
                            required
                        >

                    </div>


                    {{-- عرض الطلاب --}}
                    <div class="form-group">

                        <button
                            type="submit"
                            class="show-btn"
                        >
                            عرض الطلاب
                        </button>

                    </div>

                </div>

            </form>

        </div>


        {{-- =====================================================
             بعد اختيار الشعبة
        ====================================================== --}}

        @if($selectedSectionId)

            @if($students->isEmpty())

                <div class="alert alert-info">
                    لا يوجد طلاب في هذه الشعبة.
                </div>

            @else

                {{-- نموذج حفظ الحضور --}}
                <form
                    method="POST"
                    action="{{ route('attendances.store') }}"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="section_id"
                        value="{{ $selectedSectionId }}"
                    >

                    <input
                        type="hidden"
                        name="attendance_date"
                        value="{{ $attendanceDate }}"
                    >

                    <div class="attendance-box">

                        {{-- رأس الجدول --}}
                        <div class="attendance-header">

                            <div>

                                <h3>
                                    قائمة الطلاب
                                </h3>

                                <p>
                                    عدد الطلاب:
                                    {{ $students->count() }}
                                </p>

                            </div>

                            <div class="attendance-date">

                                التاريخ:
                                {{ $attendanceDate }}

                            </div>

                        </div>


                        {{-- الجدول --}}
                        <div class="table-wrapper">

                            <table>

                                <thead>

                                <tr>

                                    <th class="number-column">
                                        #
                                    </th>

                                    <th>
                                        اسم الطالب
                                    </th>

                                    <th>
                                        رقم الطالب
                                    </th>

                                    <th class="status-column">
                                        حالة الحضور
                                    </th>

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($students as $index => $student)

                                    @php

                                        $existing =
                                            $existingAttendances
                                                ->get($student->id);

                                        $currentStatus = old(
                                            'attendance.' . $student->id,
                                            $existing->status ?? 'present'
                                        );

                                    @endphp

                                    <tr>

                                        <td class="number-column">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="student-name">
                                            {{ $student->name }}
                                        </td>

                                        <td>
                                            {{ $student->student_number ?? '-' }}
                                        </td>

                                        <td class="status-column">

                                            <select
                                                name="attendance[{{ $student->id }}]"
                                                class="status-select"
                                                required
                                            >

                                                <option
                                                    value="present"
                                                    {{ $currentStatus === 'present' ? 'selected' : '' }}
                                                >
                                                    حاضر
                                                </option>

                                                <option
                                                    value="absent"
                                                    {{ $currentStatus === 'absent' ? 'selected' : '' }}
                                                >
                                                    غائب
                                                </option>

                                                <option
                                                    value="late"
                                                    {{ $currentStatus === 'late' ? 'selected' : '' }}
                                                >
                                                    متأخر
                                                </option>

                                                <option
                                                    value="excused"
                                                    {{ $currentStatus === 'excused' ? 'selected' : '' }}
                                                >
                                                    بعذر
                                                </option>

                                            </select>

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>


                        {{-- زر الحفظ --}}
                        <div class="attendance-footer">

                            <button
                                type="submit"
                                class="save-btn"
                            >
                                حفظ الحضور
                            </button>

                        </div>

                    </div>

                </form>

            @endif

        @endif

    </main>

</div>


{{-- =============================================================
     تحديث الطلاب تلقائياً عند تغيير الشعبة أو التاريخ
============================================================= --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form =
            document.getElementById('attendanceFilterForm');

        const sectionSelect =
            document.getElementById('sectionSelect');

        const attendanceDate =
            document.getElementById('attendanceDate');

        if (!form || !sectionSelect || !attendanceDate) {
            return;
        }

        function refreshStudents() {

            // لا نرسل الطلب إلا إذا تم اختيار شعبة
            // ويوجد تاريخ صحيح
            if (
                sectionSelect.value !== '' &&
                attendanceDate.value !== ''
            ) {
                form.submit();
            }
        }

        // عند تغيير الشعبة
        sectionSelect.addEventListener(
            'change',
            refreshStudents
        );

        // عند تغيير التاريخ
        attendanceDate.addEventListener(
            'change',
            refreshStudents
        );

    });
</script>

</body>

</html>