<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إدارة الطلاب</title>

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
            flex-direction: row;
            width: 100%;
            min-height: 100vh;
        }

        .sidebar {
            width: 275px;
            min-width: 275px;
            height: calc(100vh - 24px);
            background: #111827;
            color: white;
            margin: 12px;
            padding: 28px 20px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 12px;
        }

        .brand {
            margin-bottom: 35px;
            padding: 0 10px;
        }

        .brand h1 {
            font-size: 30px;
            color: white;
            margin-bottom: 5px;
        }

        .brand span {
            display: block;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.7;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            display: block;
            text-decoration: none;
            color: #e5e7eb;
            padding: 13px 14px;
            border-radius: 9px;
            font-size: 15px;
            transition: 0.2s;
        }

        .menu-item:hover,
        .menu-item.active {
            background: #1f2937;
            color: white;
        }

        .logout-area {
            margin-top: auto;
            padding-top: 30px;
        }

        .logout-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #ff4747;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        .main {
            flex: 1;
            min-width: 0;
            padding: 30px 30px 30px 18px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 28px;
            margin-bottom: 6px;
            color: #111827;
        }

        .topbar p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.8;
        }

        .add-btn {
            display: inline-block;
            text-decoration: none;
            background: #111827;
            color: white;
            padding: 12px 20px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
            white-space: nowrap;
        }

        .add-btn:hover {
            background: #1f2937;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 20px;
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

        .alert ul {
            padding-right: 20px;
        }

        /* ==============================
           البطاقات الإحصائية
        ============================== */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 13px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 5px;
        }

        .stat-card.total::before {
            background: #2563eb;
        }

        .stat-card.active::before {
            background: #16a34a;
        }

        .stat-card.pending::before {
            background: #d97706;
        }

        .stat-card.unlinked::before {
            background: #dc2626;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
        }

        .stat-note {
            color: #94a3b8;
            font-size: 12px;
            margin-top: 8px;
        }

        /* ==============================
           البحث والفلاتر
        ============================== */

        .filters-box {
            background: white;
            border-radius: 15px;
            padding: 22px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        }

        .filters-title {
            font-size: 18px;
            margin-bottom: 17px;
            color: #111827;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .filter-group label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            font-size: 14px;
            background: white;
            outline: none;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            border-color: #2563eb;
        }

        .filter-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .reset-btn {
            padding: 10px 16px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #334155;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
        }

        .reset-btn:hover {
            background: #e2e8f0;
        }

        .results-count {
            color: #64748b;
            font-size: 13px;
        }

        /* ==============================
           جدول الطلاب
        ============================== */

        .table-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-header h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .table-header span {
            color: #6b7280;
            font-size: 14px;
        }

        .table-responsive {
            width: 100%;
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
            white-space: nowrap;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-size: 14px;
        }

        td {
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .student-name {
            font-weight: 600;
            color: #111827;
        }

        .student-number {
            background: #eef2ff;
            color: #3730a3;
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 600;
            display: inline-block;
        }

        .section-badge {
            background: #f3f4f6;
            color: #374151;
            padding: 6px 10px;
            border-radius: 7px;
            display: inline-block;
        }

        .parent-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .parent-name {
            font-weight: 600;
            color: #1e293b;
        }

        .parent-phone {
            color: #64748b;
            font-size: 12px;
            direction: ltr;
            text-align: right;
        }

        .not-linked {
            color: #9ca3af;
            font-size: 13px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .edit-btn {
            display: inline-block;
            text-decoration: none;
            background: #2563eb;
            color: white;
            padding: 7px 12px;
            border-radius: 7px;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }

        .edit-btn:hover {
            background: #1d4ed8;
        }

        .resend-btn {
            display: inline-block;
            background: #0f766e;
            color: white;
            padding: 7px 12px;
            border-radius: 7px;
            border: none;
            font-size: 13px;
            cursor: pointer;
            white-space: nowrap;
        }

        .resend-btn:hover {
            background: #115e59;
        }

        .resend-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .resend-form {
            display: inline-block;
            margin: 0;
        }

        .disabled-btn {
            background: #e5e7eb;
            color: #6b7280;
            padding: 7px 12px;
            border-radius: 7px;
            border: none;
            font-size: 13px;
            cursor: not-allowed;
        }

        .empty {
            text-align: center;
            padding: 45px 20px;
            color: #6b7280;
        }

        .empty h3 {
            color: #374151;
            margin-bottom: 8px;
        }

        .no-results {
            display: none;
            padding: 35px 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filters-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1000px) {
            .sidebar {
                width: 230px;
                min-width: 230px;
            }

            .main {
                padding: 20px 20px 20px 10px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .add-btn {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 700px) {
            .layout {
                display: block;
            }

            .sidebar {
                width: calc(100% - 24px);
                min-width: 0;
                height: auto;
                position: relative;
                top: 0;
            }

            .main {
                padding: 20px 12px;
            }

            .stats-grid,
            .filters-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

@php
    $totalStudents = $students->count();

    $activeStudents = $students->filter(function ($student) {
        return (bool) $student->user?->is_active;
    })->count();

    $pendingStudents = $students->filter(function ($student) {
        $user = $student->user;

        return $user
            && !$user->is_active
            && $user->password === null;
    })->count();

    $unlinkedStudents = $students->filter(function ($student) {
        return $student->parents->isEmpty();
    })->count();

    $sectionOptions = $students
        ->filter(fn ($student) => $student->section !== null)
        ->map(function ($student) {
            return [
                'id' => $student->section->id,
                'label' =>
                    ($student->section->schoolClass?->name ?? 'بدون صف')
                    . ' - '
                    . $student->section->name,
            ];
        })
        ->unique('id')
        ->sortBy('label');
@endphp

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="topbar">
            <div>
                <h2>إدارة الطلاب</h2>
                <p>
                    عرض وإدارة بيانات الطلاب وأولياء أمورهم المسجلين في النظام
                </p>
            </div>

            <a href="{{ route('students.create') }}" class="add-btn">
                + إضافة طالب جديد
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- البطاقات الإحصائية --}}

        <div class="stats-grid">

            <div class="stat-card total">
                <div class="stat-label">إجمالي الطلاب</div>
                <div class="stat-value">{{ $totalStudents }}</div>
                <div class="stat-note">جميع الطلاب المسجلين</div>
            </div>

            <div class="stat-card active">
                <div class="stat-label">الحسابات النشطة</div>
                <div class="stat-value">{{ $activeStudents }}</div>
                <div class="stat-note">طلاب لديهم حسابات مفعّلة</div>
            </div>

            <div class="stat-card pending">
                <div class="stat-label">بانتظار التفعيل</div>
                <div class="stat-value">{{ $pendingStudents }}</div>
                <div class="stat-note">لم يكملوا تفعيل حساباتهم</div>
            </div>

            <div class="stat-card unlinked">
                <div class="stat-label">بدون ولي أمر مرتبط</div>
                <div class="stat-value">{{ $unlinkedStudents }}</div>
                <div class="stat-note">طلاب لا يرتبطون بأي ولي أمر</div>
            </div>

        </div>

        {{-- البحث والفلاتر --}}

        <div class="filters-box">

            <h3 class="filters-title">البحث وتصفية الطلاب</h3>

            <div class="filters-grid">

                <div class="filter-group">
                    <label for="studentSearch">
                        البحث عن طالب
                    </label>

                    <input
                        type="search"
                        id="studentSearch"
                        placeholder="اسم الطالب أو الرقم الطلابي أو البريد الإلكتروني"
                        autocomplete="off"
                    >
                </div>

                <div class="filter-group">
                    <label for="sectionFilter">
                        الصف والشعبة
                    </label>

                    <select id="sectionFilter">
                        <option value="">جميع الصفوف والشعب</option>

                        @foreach($sectionOptions as $sectionOption)
                            <option value="{{ $sectionOption['id'] }}">
                                {{ $sectionOption['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="statusFilter">
                        حالة الحساب
                    </label>

                    <select id="statusFilter">
                        <option value="">جميع الحالات</option>
                        <option value="active">نشط</option>
                        <option value="pending">بانتظار التفعيل</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>

            </div>

            <div class="filter-actions">

                <button
                    type="button"
                    id="resetFilters"
                    class="reset-btn"
                >
                    إعادة ضبط البحث
                </button>

                <span id="filterResults" class="results-count" aria-live="polite">
                    عرض {{ $totalStudents }} من {{ $totalStudents }} طالب
                </span>

            </div>

        </div>

        {{-- جدول الطلاب --}}

        <div class="table-box">

            <div class="table-header">
                <div>
                    <h3>قائمة الطلاب</h3>

                    <span>
                        عدد الطلاب: {{ $totalStudents }}
                    </span>
                </div>
            </div>

            @if($students->isEmpty())

                <div class="empty">
                    <h3>لا يوجد طلاب</h3>
                    <p>لم تتم إضافة أي طالب حتى الآن.</p>
                </div>

            @else

                <div class="table-responsive">

                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الطالب</th>
                                <th>الرقم الطلابي</th>
                                <th>البريد الإلكتروني</th>
                                <th>الصف والشعبة</th>
                                <th>الأب</th>
                                <th>الأم</th>
                                <th>تاريخ الميلاد</th>
                                <th>الجنس</th>
                                <th>حالة الحساب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody id="studentsTableBody">

                            @foreach($students as $index => $student)

                                @php
                                    $father = $student->parents->first(
                                        fn ($parent) => $parent->pivot?->relation === 'father'
                                    );

                                    $mother = $student->parents->first(
                                        fn ($parent) => $parent->pivot?->relation === 'mother'
                                    );

                                    $studentUser = $student->user;

                                    $isPendingActivation =
                                        $studentUser
                                        && !$studentUser->is_active
                                        && $studentUser->password === null;

                                    $accountStatus = $studentUser?->is_active
                                        ? 'active'
                                        : ($isPendingActivation ? 'pending' : 'inactive');
                                @endphp

                                <tr
                                    class="student-row"
                                    data-search="{{ $studentUser?->name ?? '' }} {{ $student->student_number ?? '' }} {{ $studentUser?->email ?? '' }}"
                                    data-section="{{ $student->section_id ?? '' }}"
                                    data-status="{{ $accountStatus }}"
                                >

                                    <td>{{ $index + 1 }}</td>

                                    <td class="student-name">
                                        {{ $studentUser?->name ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="student-number">
                                            {{ $student->student_number ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $studentUser?->email ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="section-badge">
                                            {{ $student->section?->schoolClass?->name ?? 'بدون صف' }}
                                            -
                                            {{ $student->section?->name ?? 'بدون شعبة' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($father)
                                            <div class="parent-info">
                                                <span class="parent-name">
                                                    {{ $father->user?->name ?? 'بدون اسم' }}
                                                </span>

                                                <span class="parent-phone">
                                                    {{ $father->user?->phone ?: 'بدون رقم هاتف' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="not-linked">
                                                غير مرتبط
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($mother)
                                            <div class="parent-info">
                                                <span class="parent-name">
                                                    {{ $mother->user?->name ?? 'بدون اسم' }}
                                                </span>

                                                <span class="parent-phone">
                                                    {{ $mother->user?->phone ?: 'بدون رقم هاتف' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="not-linked">
                                                غير مرتبط
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $student->date_of_birth ?? '-' }}
                                    </td>

                                    <td>
                                        @if($student->gender === 'male')
                                            ذكر
                                        @elseif($student->gender === 'female')
                                            أنثى
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        @if($studentUser?->is_active)

                                            <span class="status-badge status-active">
                                                نشط
                                            </span>

                                        @elseif($isPendingActivation)

                                            <span class="status-badge status-pending">
                                                بانتظار التفعيل
                                            </span>

                                        @else

                                            <span class="status-badge status-inactive">
                                                غير نشط
                                            </span>

                                        @endif
                                    </td>

                                    <td>
                                        <div class="actions">

                                            <a
                                                href="{{ route('students.edit', $student) }}"
                                                class="edit-btn"
                                            >
                                                تعديل
                                            </a>

                                            @if($isPendingActivation)

                                                <form
                                                    action="{{ route('students.resend-activation', $student) }}"
                                                    method="POST"
                                                    class="resend-form"
                                                    onsubmit="return confirmResendActivation(this);"
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="resend-btn"
                                                        title="إعادة إرسال رابط تفعيل حساب الطالب"
                                                    >
                                                        إعادة إرسال التفعيل
                                                    </button>
                                                </form>

                                            @endif

                                            <button
                                                type="button"
                                                class="disabled-btn"
                                                disabled
                                                title="الحذف النهائي متوقف لحماية السجلات الأكاديمية"
                                            >
                                                حذف
                                            </button>

                                        </div>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>

                </div>

                <div id="noSearchResults" class="no-results">
                    لا توجد نتائج مطابقة للبحث أو الفلاتر المحددة.
                </div>

            @endif

        </div>

    </main>

</div>

<script>
    /*
    |--------------------------------------------------------------------------
    | تأكيد إعادة إرسال رابط التفعيل
    |--------------------------------------------------------------------------
    */

    function confirmResendActivation(form) {

        const confirmed = confirm(
            'هل تريدين إعادة إرسال رابط التفعيل إلى البريد الإلكتروني لهذا الطالب؟'
        );

        if (!confirmed) {
            return false;
        }

        const button = form.querySelector('button[type="submit"]');

        if (button) {
            button.disabled = true;
            button.textContent = 'جارٍ الإرسال...';
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | البحث الفوري والفلاتر
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function () {

        const searchInput = document.getElementById('studentSearch');
        const sectionFilter = document.getElementById('sectionFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetButton = document.getElementById('resetFilters');
        const resultsLabel = document.getElementById('filterResults');
        const noResults = document.getElementById('noSearchResults');

        const rows = Array.from(
            document.querySelectorAll('.student-row')
        );

        function normalizeText(value) {
            return String(value || '')
                .trim()
                .toLocaleLowerCase()
                .replace(/[\u064B-\u065F\u0670]/g, '')
                .replace(/ـ/g, '');
        }

        function filterStudents() {

            const searchValue = normalizeText(searchInput.value);
            const selectedSection = sectionFilter.value;
            const selectedStatus = statusFilter.value;

            let visibleCount = 0;

            rows.forEach(function (row) {

                const rowSearch = normalizeText(row.dataset.search);
                const rowSection = row.dataset.section || '';
                const rowStatus = row.dataset.status || '';

                const matchesSearch =
                    searchValue === '' ||
                    rowSearch.includes(searchValue);

                const matchesSection =
                    selectedSection === '' ||
                    rowSection === selectedSection;

                const matchesStatus =
                    selectedStatus === '' ||
                    rowStatus === selectedStatus;

                const shouldShow =
                    matchesSearch &&
                    matchesSection &&
                    matchesStatus;

                row.hidden = !shouldShow;

                if (shouldShow) {
                    visibleCount++;
                }
            });

            resultsLabel.textContent =
                'عرض ' + visibleCount +
                ' من ' + rows.length + ' طالب';

            if (noResults) {
                noResults.style.display =
                    rows.length > 0 && visibleCount === 0
                        ? 'block'
                        : 'none';
            }
        }

        searchInput.addEventListener('input', filterStudents);
        sectionFilter.addEventListener('change', filterStudents);
        statusFilter.addEventListener('change', filterStudents);

        resetButton.addEventListener('click', function () {
            searchInput.value = '';
            sectionFilter.value = '';
            statusFilter.value = '';

            filterStudents();
            searchInput.focus();
        });

        filterStudents();
    });
</script>

</body>
</html>