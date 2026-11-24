<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | إدارة المعلمين</title>

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

        .stat-card.active::before {
            background: #16a34a;
        }

        .stat-card.pending::before {
            background: #d97706;
        }

        .stat-card.unassigned::before {
            background: #dc2626;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 12px;
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
            line-height: 1.7;
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
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
        }

        .filters-title {
            font-size: 18px;
            margin-bottom: 17px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
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

        .results-count {
            color: #64748b;
            font-size: 13px;
        }

        /* ==============================
           الجدول
        ============================== */

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

        th, td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
            font-size: 14px;
            white-space: nowrap;
        }

        th {
            background: #f9fafb;
            color: #374151;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        tr[hidden] {
            display: none !important;
        }

        .teacher-name {
            font-weight: 600;
            color: #111827;
        }

        .subject-badge {
            display: inline-block;
            background: #eef2ff;
            color: #3730a3;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
        }

        .count-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 6px 11px;
            border-radius: 7px;
            font-weight: 600;
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

        /* ==============================
           أزرار الإجراءات
        ============================== */

        .actions {
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap;
        }

        .actions form {
            margin: 0;
        }

        .action-btn {
            display: inline-block;
            border: none;
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            font-family: inherit;
        }

        .edit-btn {
            background: #2563eb;
            color: white;
        }

        .edit-btn:hover {
            background: #1d4ed8;
        }

        .resend-btn {
            background: #d97706;
            color: white;
        }

        .resend-btn:hover {
            background: #b45309;
        }

        .deactivate-btn {
            background: #dc2626;
            color: white;
        }

        .deactivate-btn:hover {
            background: #b91c1c;
        }

        .activate-btn {
            background: #16a34a;
            color: white;
        }

        .activate-btn:hover {
            background: #15803d;
        }

        .action-btn:disabled {
            opacity: 0.65;
            cursor: wait;
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

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filters-grid {
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
    $totalTeachers = $teachers->count();

    $activeTeachers = $teachers->filter(function ($teacher) {
        return (bool) $teacher->user?->is_active;
    })->count();

    $pendingTeachers = $teachers->filter(function ($teacher) {
        $user = $teacher->user;

        return $user
            && !$user->is_active
            && $user->password === null;
    })->count();

    $unassignedTeachers = $teachers->filter(function ($teacher) {
        return (int) $teacher->assignments_count === 0;
    })->count();

    $subjectOptions = $teachers
        ->filter(fn ($teacher) => $teacher->subject !== null)
        ->map(fn ($teacher) => [
            'id' => $teacher->subject->id,
            'name' => $teacher->subject->name,
        ])
        ->unique('id')
        ->sortBy('name');
@endphp

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="topbar">
            <div>
                <h2>إدارة المعلمين</h2>
                <p>
                    عرض وإدارة بيانات المعلمين وتحديد المادة الأساسية
                    والمرحلة التعليمية لكل معلم.
                </p>
            </div>

            <a href="{{ route('teachers.create') }}" class="add-btn">
                + إضافة معلم
            </a>
        </div>

        {{-- رسائل النجاح والأخطاء --}}

        @if(session('success'))
            <div class="alert success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert error" role="alert">
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
                <div class="stat-label">إجمالي المعلمين</div>
                <div class="stat-value">{{ $totalTeachers }}</div>
                <div class="stat-note">جميع المعلمين المسجلين</div>
            </div>

            <div class="stat-card active">
                <div class="stat-label">الحسابات النشطة</div>
                <div class="stat-value">{{ $activeTeachers }}</div>
                <div class="stat-note">معلمون لديهم حسابات مفعّلة</div>
            </div>

            <div class="stat-card pending">
                <div class="stat-label">بانتظار التفعيل</div>
                <div class="stat-value">{{ $pendingTeachers }}</div>
                <div class="stat-note">حسابات لم تكمل التفعيل</div>
            </div>

            <div class="stat-card unassigned">
                <div class="stat-label">بدون توزيع تدريسي</div>
                <div class="stat-value">{{ $unassignedTeachers }}</div>
                <div class="stat-note">معلمون لم يتم توزيعهم على شعب</div>
            </div>

        </div>

        {{-- البحث والفلاتر --}}

        <div class="filters-box">

            <h3 class="filters-title">
                البحث وتصفية المعلمين
            </h3>

            <div class="filters-grid">

                <div class="filter-group">
                    <label for="teacherSearch">البحث عن معلم</label>

                    <input
                        type="search"
                        id="teacherSearch"
                        placeholder="اسم المعلم أو البريد الإلكتروني أو رقم الهاتف"
                        autocomplete="off"
                    >
                </div>

                <div class="filter-group">
                    <label for="subjectFilter">المادة</label>

                    <select id="subjectFilter">
                        <option value="">جميع المواد</option>

                        @foreach($subjectOptions as $subjectOption)
                            <option value="{{ $subjectOption['id'] }}">
                                {{ $subjectOption['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="statusFilter">حالة الحساب</label>

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
                    عرض {{ $totalTeachers }} من {{ $totalTeachers }} معلم
                </span>

            </div>

        </div>

        {{-- جدول المعلمين --}}

        <div class="table-box">

            <div class="table-header">
                <h3>قائمة المعلمين</h3>
                <p>عدد المعلمين: {{ $totalTeachers }}</p>
            </div>

            @if($teachers->isEmpty())

                <div class="empty">
                    لا يوجد معلمون مسجلون حاليًا.
                </div>

            @else

                <div class="table-responsive">

                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم المعلم</th>
                                <th>الرقم الوظيفي</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهاتف</th>
                                <th>المادة</th>
                                <th>المرحلة التعليمية</th>
                                <th>التخصص</th>
                                <th>عدد الشعب</th>
                                <th>حالة الحساب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($teachers as $index => $teacher)

                                @php
                                    $teacherUser = $teacher->user;

                                    $isPendingActivation =
                                        $teacherUser
                                        && !$teacherUser->is_active
                                        && $teacherUser->password === null;

                                    $accountStatus = $teacherUser?->is_active
                                        ? 'active'
                                        : ($isPendingActivation ? 'pending' : 'inactive');

                                    $stageLabel = match ($teacher->education_stage) {
                                        'primary' => 'الابتدائية',
                                        'middle' => 'الإعدادية',
                                        'secondary' => 'الثانوية',
                                        default => 'غير محددة',
                                    };
                                @endphp

                                <tr
                                    class="teacher-row"
                                    data-search="{{ $teacherUser?->name ?? '' }} {{ $teacherUser?->email ?? '' }} {{ $teacherUser?->phone ?? '' }}"
                                    data-subject="{{ $teacher->subject_id ?? '' }}"
                                    data-status="{{ $accountStatus }}"
                                >

                                    <td>{{ $index + 1 }}</td>

                                    <td class="teacher-name">
                                        {{ $teacherUser?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $teacher->employee_number ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $teacherUser?->email ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $teacherUser?->phone ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="subject-badge">
                                            {{ $teacher->subject?->name ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $stageLabel }}
                                    </td>

                                    <td>
                                        {{ $teacher->specialization ?: '-' }}
                                    </td>

                                    <td>
                                        <span class="count-badge">
                                            {{ $teacher->assignments_count ?? 0 }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($teacherUser?->is_active)
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

                                            {{-- تعديل بيانات المعلم --}}

                                            <a
                                                href="{{ route('teachers.edit', $teacher) }}"
                                                class="action-btn edit-btn"
                                            >
                                                تعديل
                                            </a>

                                            {{-- إعادة إرسال رابط التفعيل --}}

                                            @if($isPendingActivation)

                                                <form
                                                    method="POST"
                                                    action="{{ route('teachers.resend-activation', $teacher) }}"
                                                    class="action-form"
                                                    data-confirm="هل تريد إعادة إرسال رابط التفعيل إلى بريد هذا المعلم؟"
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="action-btn resend-btn"
                                                    >
                                                        إعادة إرسال التفعيل
                                                    </button>
                                                </form>

                                            {{-- تعطيل الحساب النشط --}}

                                            @elseif($teacherUser?->is_active)

                                                <form
                                                    method="POST"
                                                    action="{{ route('teachers.toggle-status', $teacher) }}"
                                                    class="action-form"
                                                    data-confirm="هل تريد تعطيل حساب هذا المعلم؟ ستبقى بياناته وتوزيعاته محفوظة."
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="action-btn deactivate-btn"
                                                    >
                                                        تعطيل الحساب
                                                    </button>
                                                </form>

                                            {{-- إعادة تنشيط الحساب المعطّل --}}

                                            @elseif($teacherUser && $teacherUser->password !== null)

                                                <form
                                                    method="POST"
                                                    action="{{ route('teachers.toggle-status', $teacher) }}"
                                                    class="action-form"
                                                    data-confirm="هل تريد إعادة تنشيط حساب هذا المعلم؟"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="action-btn activate-btn"
                                                    >
                                                        إعادة التنشيط
                                                    </button>
                                                </form>

                                            @endif

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
    document.addEventListener('DOMContentLoaded', function () {

        const searchInput = document.getElementById('teacherSearch');
        const subjectFilter = document.getElementById('subjectFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetButton = document.getElementById('resetFilters');
        const resultsLabel = document.getElementById('filterResults');
        const noResults = document.getElementById('noSearchResults');

        const rows = Array.from(
            document.querySelectorAll('.teacher-row')
        );

        function normalizeText(value) {
            return String(value || '')
                .trim()
                .toLocaleLowerCase()
                .replace(/[\u064B-\u065F\u0670]/g, '')
                .replace(/ـ/g, '');
        }

        function filterTeachers() {

            const searchValue = normalizeText(searchInput.value);
            const selectedSubject = subjectFilter.value;
            const selectedStatus = statusFilter.value;

            let visibleCount = 0;

            rows.forEach(function (row) {

                const matchesSearch =
                    searchValue === '' ||
                    normalizeText(row.dataset.search).includes(searchValue);

                const matchesSubject =
                    selectedSubject === '' ||
                    row.dataset.subject === selectedSubject;

                const matchesStatus =
                    selectedStatus === '' ||
                    row.dataset.status === selectedStatus;

                const shouldShow =
                    matchesSearch &&
                    matchesSubject &&
                    matchesStatus;

                row.hidden = !shouldShow;

                if (shouldShow) {
                    visibleCount++;
                }
            });

            resultsLabel.textContent =
                'عرض ' + visibleCount +
                ' من ' + rows.length + ' معلم';

            if (noResults) {
                noResults.style.display =
                    rows.length > 0 && visibleCount === 0
                        ? 'block'
                        : 'none';
            }
        }

        searchInput.addEventListener('input', filterTeachers);
        subjectFilter.addEventListener('change', filterTeachers);
        statusFilter.addEventListener('change', filterTeachers);

        resetButton.addEventListener('click', function () {
            searchInput.value = '';
            subjectFilter.value = '';
            statusFilter.value = '';

            filterTeachers();
            searchInput.focus();
        });

        /*
        |--------------------------------------------------------------------------
        | تأكيد الإجراءات ومنع الضغط المتكرر
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.action-form').forEach(function (form) {

            form.addEventListener('submit', function (event) {

                const confirmationMessage = form.dataset.confirm;

                if (
                    confirmationMessage &&
                    !window.confirm(confirmationMessage)
                ) {
                    event.preventDefault();
                    return;
                }

                const submitButton = form.querySelector(
                    'button[type="submit"]'
                );

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'جارٍ التنفيذ...';
                }
            });
        });

        filterTeachers();
    });
</script>

</body>
</html>