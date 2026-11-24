<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إدارة المواد الدراسية</title>

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

        /* ==========================
           العنوان
        ========================== */

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
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

        .add-btn {
            display: inline-block;
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
        }

        .add-btn:hover {
            background: #1f2937;
        }

        /* ==========================
           التنبيهات
        ========================== */

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert ul {
            padding-right: 20px;
        }

        /* ==========================
           الإحصائيات
        ========================== */

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
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
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

        .stat-card.linked::before {
            background: #16a34a;
        }

        .stat-card.unlinked::before {
            background: #d97706;
        }

        .stat-card.teachers::before {
            background: #7c3aed;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
        }

        .stat-note {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 8px;
            line-height: 1.7;
        }

        /* ==========================
           البحث والتصفية
        ========================== */

        .filters-box {
            background: white;
            border-radius: 15px;
            padding: 22px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
        }

        .filters-title {
            font-size: 18px;
            margin-bottom: 17px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
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
            background: white;
            font-size: 14px;
            outline: none;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            border-color: #2563eb;
        }

        .filter-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            cursor: pointer;
            font-size: 13px;
        }

        .reset-btn:hover {
            background: #e2e8f0;
        }

        .results-count {
            color: #64748b;
            font-size: 13px;
        }

        /* ==========================
           جدول المواد
        ========================== */

        .table-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-header h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .table-header p {
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
            font-size: 14px;
        }

        th {
            background: #f9fafb;
            color: #374151;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        tr[hidden] {
            display: none !important;
        }

        .subject-name {
            font-weight: 600;
            color: #111827;
        }

        .description {
            color: #64748b;
            max-width: 350px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .count-badge {
            display: inline-block;
            background: #eef2ff;
            color: #3730a3;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.linked {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.unlinked {
            background: #fef3c7;
            color: #92400e;
        }

        .actions {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
            align-items: center;
        }

        .edit-btn {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 13px;
        }

        .edit-btn:hover {
            background: #c7d2fe;
        }

        .delete-btn {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 13px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #fecaca;
        }

        .delete-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .empty,
        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .no-results {
            display: none;
        }

        /* ==========================
           تجاوب الواجهة
        ========================== */

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
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

            .add-btn {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 650px) {
            .stats-grid,
            .filters-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | إحصائيات المواد الدراسية
    |--------------------------------------------------------------------------
    */

    $totalSubjects = $subjects->count();

    $linkedSubjects = $subjects
        ->filter(fn ($subject) => $subject->teachers_count > 0)
        ->count();

    $unlinkedSubjects = $subjects
        ->filter(fn ($subject) => $subject->teachers_count == 0)
        ->count();

    $totalTeacherLinks = $subjects->sum('teachers_count');
@endphp

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        {{-- العنوان وزر الإضافة --}}

        <div class="topbar">

            <div>
                <h2>إدارة المواد الدراسية</h2>

                <p>
                    إدارة المواد الدراسية ومتابعة ارتباطها بالمعلمين
                    في نظام صلة.
                </p>
            </div>

            <a
                href="{{ route('subjects.create') }}"
                class="add-btn"
            >
                + إضافة مادة
            </a>

        </div>

        {{-- رسائل النظام --}}

        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert error">
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
                <div class="stat-label">إجمالي المواد الدراسية</div>

                <div class="stat-value">
                    {{ $totalSubjects }}
                </div>

                <div class="stat-note">
                    جميع المواد المسجلة في النظام
                </div>
            </div>

            <div class="stat-card linked">
                <div class="stat-label">مواد مرتبطة بمعلمين</div>

                <div class="stat-value">
                    {{ $linkedSubjects }}
                </div>

                <div class="stat-note">
                    مواد لديها معلم واحد على الأقل
                </div>
            </div>

            <div class="stat-card unlinked">
                <div class="stat-label">مواد غير مرتبطة</div>

                <div class="stat-value">
                    {{ $unlinkedSubjects }}
                </div>

                <div class="stat-note">
                    مواد لم يُحدد لها معلم أساسي
                </div>
            </div>

            <div class="stat-card teachers">
                <div class="stat-label">ارتباطات المعلمين بالمواد</div>

                <div class="stat-value">
                    {{ $totalTeacherLinks }}
                </div>

                <div class="stat-note">
                    مجموع ارتباطات المعلمين بالمواد الدراسية
                </div>
            </div>

        </div>

        {{-- البحث والتصفية --}}

        <div class="filters-box">

            <h3 class="filters-title">
                البحث وتصفية المواد الدراسية
            </h3>

            <div class="filters-grid">

                <div class="filter-group">

                    <label for="subjectSearch">
                        البحث عن مادة
                    </label>

                    <input
                        type="search"
                        id="subjectSearch"
                        placeholder="اسم المادة أو وصفها"
                        autocomplete="off"
                    >

                </div>

                <div class="filter-group">

                    <label for="statusFilter">
                        ارتباط المادة بالمعلمين
                    </label>

                    <select id="statusFilter">

                        <option value="">
                            جميع المواد
                        </option>

                        <option value="linked">
                            مرتبطة بمعلمين
                        </option>

                        <option value="unlinked">
                            غير مرتبطة بمعلمين
                        </option>

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

                <span
                    id="filterResults"
                    class="results-count"
                    aria-live="polite"
                >
                    عرض {{ $totalSubjects }} من {{ $totalSubjects }} مادة
                </span>

            </div>

        </div>

        {{-- جدول المواد الدراسية --}}

        <div class="table-box">

            <div class="table-header">

                <h3>
                    قائمة المواد الدراسية
                </h3>

                <p>
                    إجمالي المواد: {{ $totalSubjects }}
                </p>

            </div>

            @if($subjects->isEmpty())

                <div class="empty">
                    لا توجد مواد دراسية حاليًا.
                </div>

            @else

                <div class="table-responsive">

                    <table>

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم المادة</th>
                                <th>الوصف</th>
                                <th>عدد المعلمين</th>
                                <th>حالة الارتباط</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($subjects as $subject)

                                @php
                                    $teachersCount = (int) $subject->teachers_count;

                                    $isLinked = $teachersCount > 0;

                                    $linkStatus = $isLinked
                                        ? 'linked'
                                        : 'unlinked';
                                @endphp

                                <tr
                                    class="subject-row"
                                    data-search="{{ $subject->name }} {{ $subject->description ?? '' }}"
                                    data-status="{{ $linkStatus }}"
                                >

                                    <td>
                                        {{ $subject->id }}
                                    </td>

                                    <td class="subject-name">
                                        {{ $subject->name }}
                                    </td>

                                    <td class="description">
                                        {{ $subject->description ?: '-' }}
                                    </td>

                                    <td>
                                        <span class="count-badge">
                                            {{ $teachersCount }}
                                        </span>
                                    </td>

                                    <td>

                                        @if($isLinked)

                                            <span class="status-badge linked">
                                                مرتبطة بمعلمين
                                            </span>

                                        @else

                                            <span class="status-badge unlinked">
                                                غير مرتبطة
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div class="actions">

                                            <a
                                                href="{{ route('subjects.edit', $subject) }}"
                                                class="edit-btn"
                                            >
                                                تعديل
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('subjects.destroy', $subject) }}"
                                                class="delete-subject-form"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="delete-btn"
                                                >
                                                    حذف
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div
                    id="noSearchResults"
                    class="no-results"
                >
                    لا توجد مواد مطابقة للبحث أو التصفية المحددة.
                </div>

            @endif

        </div>

    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | عناصر البحث
        |--------------------------------------------------------------------------
        */

        const searchInput = document.getElementById('subjectSearch');

        const statusFilter = document.getElementById('statusFilter');

        const resetButton = document.getElementById('resetFilters');

        const resultsLabel = document.getElementById('filterResults');

        const noResults = document.getElementById('noSearchResults');

        const rows = Array.from(
            document.querySelectorAll('.subject-row')
        );

        /*
        |--------------------------------------------------------------------------
        | تحسين البحث باللغة العربية
        |--------------------------------------------------------------------------
        */

        function normalizeText(value) {

            return String(value || '')
                .trim()
                .toLocaleLowerCase()
                .replace(/[\u064B-\u065F\u0670]/g, '')
                .replace(/ـ/g, '');
        }

        /*
        |--------------------------------------------------------------------------
        | تنفيذ البحث والتصفية
        |--------------------------------------------------------------------------
        */

        function filterSubjects() {

            const searchValue = normalizeText(searchInput.value);

            const selectedStatus = statusFilter.value;

            let visibleCount = 0;

            rows.forEach(function (row) {

                const matchesSearch =
                    searchValue === '' ||
                    normalizeText(row.dataset.search).includes(searchValue);

                const matchesStatus =
                    selectedStatus === '' ||
                    row.dataset.status === selectedStatus;

                const shouldShow =
                    matchesSearch && matchesStatus;

                row.hidden = !shouldShow;

                if (shouldShow) {
                    visibleCount++;
                }

            });

            resultsLabel.textContent =
                'عرض ' + visibleCount +
                ' من ' + rows.length + ' مادة';

            if (noResults) {
                noResults.style.display =
                    rows.length > 0 && visibleCount === 0
                        ? 'block'
                        : 'none';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | أحداث البحث
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('input', filterSubjects);

        statusFilter.addEventListener('change', filterSubjects);

        /*
        |--------------------------------------------------------------------------
        | إعادة ضبط البحث
        |--------------------------------------------------------------------------
        */

        resetButton.addEventListener('click', function () {

            searchInput.value = '';
            statusFilter.value = '';

            filterSubjects();

            searchInput.focus();
        });

        /*
        |--------------------------------------------------------------------------
        | تأكيد حذف المادة
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.delete-subject-form')
            .forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    if (form.dataset.submitting === 'true') {
                        event.preventDefault();
                        return;
                    }

                    const confirmed = confirm(
                        'هل أنت متأكد من حذف هذه المادة الدراسية؟'
                    );

                    if (!confirmed) {
                        event.preventDefault();
                        return;
                    }

                    form.dataset.submitting = 'true';

                    const button = form.querySelector('.delete-btn');

                    if (button) {
                        button.disabled = true;
                        button.textContent = 'جارٍ الحذف...';
                    }
                });

            });

        /*
        |--------------------------------------------------------------------------
        | تشغيل البحث عند فتح الصفحة
        |--------------------------------------------------------------------------
        */

        filterSubjects();

    });
</script>

</body>
</html>