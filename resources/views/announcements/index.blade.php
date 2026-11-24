<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | الإعلانات المدرسية</title>

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

        /* القائمة الجانبية */

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
            gap: 20px;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 6px;
        }

        .topbar p {
            color: #6b7280;
            line-height: 1.8;
            font-size: 14px;
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

        /* الرسائل */

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
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

        /* توضيح وظيفة الصفحة */

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            line-height: 1.9;
            font-size: 14px;
        }

        /* الإحصائيات */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 13px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            width: 5px;
        }

        .stat-card.total::before {
            background: #2563eb;
        }

        .stat-card.published::before {
            background: #16a34a;
        }

        .stat-card.scheduled::before {
            background: #7c3aed;
        }

        .stat-card.expired::before {
            background: #d97706;
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

        /* البحث والتصفية */

        .filters-box {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 15px;
            padding: 22px;
            margin-bottom: 24px;
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

        .results-count {
            color: #64748b;
            font-size: 13px;
        }

        /* جدول الإعلانات */

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
            overflow-x: auto;
            width: 100%;
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

        .announcement-title {
            font-weight: 600;
            color: #111827;
        }

        .target-badge {
            display: inline-block;
            padding: 6px 10px;
            margin: 3px;
            border-radius: 20px;
            background: #eef2ff;
            color: #3730a3;
            font-size: 12px;
            line-height: 1.7;
        }

        .priority-badge,
        .status-badge {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 12px;
            white-space: nowrap;
        }

        .priority-normal {
            background: #f1f5f9;
            color: #475569;
        }

        .priority-important {
            background: #fef3c7;
            color: #92400e;
        }

        .priority-urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-published {
            background: #dcfce7;
            color: #166534;
        }

        .status-scheduled {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-draft {
            background: #f1f5f9;
            color: #475569;
        }

        .status-expired {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap;
        }

        .view-btn {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 13px;
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

        .delete-btn {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 13px;
            cursor: pointer;
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
    $currentTime = now();

    $roleLabels = [
        'all' => 'جميع مستخدمي النظام',
        'parents' => 'أولياء الأمور',
        'teachers' => 'المعلمون والمعلمات',
        'students' => 'الطلاب والطالبات',
        'deputies' => 'الوكلاء',
        'supervisors' => 'مشرفو المراحل',
        'academic_council' => 'المجلس الأكاديمي',
        'quality' => 'التطوير والجودة',
        'administrative_staff' => 'الإداريون والخدمات',
    ];

    $getStatus = function ($announcement) use ($currentTime) {
        if ($announcement->status === 'draft') {
            return 'draft';
        }

        if (
            $announcement->expires_at &&
            $announcement->expires_at->lte($currentTime)
        ) {
            return 'expired';
        }

        if (
            $announcement->published_at &&
            $announcement->published_at->gt($currentTime)
        ) {
            return 'scheduled';
        }

        if (
            $announcement->published_at &&
            $announcement->published_at->lte($currentTime)
        ) {
            return 'published';
        }

        return 'draft';
    };

    $totalAnnouncements = $announcements->count();

    $publishedAnnouncements = $announcements
        ->filter(fn ($announcement) => $getStatus($announcement) === 'published')
        ->count();

    $scheduledAnnouncements = $announcements
        ->filter(fn ($announcement) => $getStatus($announcement) === 'scheduled')
        ->count();

    $expiredAnnouncements = $announcements
        ->filter(fn ($announcement) => $getStatus($announcement) === 'expired')
        ->count();
@endphp

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        {{-- عنوان الصفحة --}}

        <div class="topbar">

            <div>
                <h2>الإعلانات المدرسية</h2>

                <p>
                    إنشاء الإعلانات المدرسية وإدارتها وتحديد
                    الفئات المستهدفة داخل نظام صلة.
                </p>
            </div>

            <a
                href="{{ route('announcements.create') }}"
                class="add-btn"
            >
                + إضافة إعلان جديد
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

        {{-- توضيح وظيفة الصفحة --}}

        <div class="info-box">
            تتيح هذه الصفحة لمدير النظام نشر الإعلانات المدرسية
            وتحديد الفئات المستهدفة، مثل أولياء الأمور والمعلمين
            والطلاب والوكلاء والمشرفين.
            <br>
            يمكن حفظ الإعلان كمسودة أو نشره فورًا أو تحديد موعد لنشره.
            ويمكن تخصيصه حسب القسم أو المرحلة أو الصف أو الشعبة.
        </div>

        {{-- الإحصائيات --}}

        <div class="stats-grid">

            <div class="stat-card total">
                <div class="stat-label">إجمالي الإعلانات</div>
                <div class="stat-value">
                    {{ $totalAnnouncements }}
                </div>
                <div class="stat-note">
                    جميع الإعلانات المسجلة في النظام
                </div>
            </div>

            <div class="stat-card published">
                <div class="stat-label">الإعلانات المنشورة</div>
                <div class="stat-value">
                    {{ $publishedAnnouncements }}
                </div>
                <div class="stat-note">
                    الإعلانات المتاحة حاليًا
                </div>
            </div>

            <div class="stat-card scheduled">
                <div class="stat-label">الإعلانات المجدولة</div>
                <div class="stat-value">
                    {{ $scheduledAnnouncements }}
                </div>
                <div class="stat-note">
                    إعلانات تنتظر موعد النشر
                </div>
            </div>

            <div class="stat-card expired">
                <div class="stat-label">الإعلانات المنتهية</div>
                <div class="stat-value">
                    {{ $expiredAnnouncements }}
                </div>
                <div class="stat-note">
                    إعلانات انتهت صلاحيتها
                </div>
            </div>

        </div>

        {{-- البحث والتصفية --}}

        <div class="filters-box">

            <h3 class="filters-title">
                البحث وتصفية الإعلانات
            </h3>

            <div class="filters-grid">

                <div class="filter-group">
                    <label for="announcementSearch">
                        البحث عن إعلان
                    </label>

                    <input
                        type="search"
                        id="announcementSearch"
                        placeholder="عنوان الإعلان أو محتواه"
                        autocomplete="off"
                    >
                </div>

                <div class="filter-group">
                    <label for="statusFilter">
                        حالة الإعلان
                    </label>

                    <select id="statusFilter">
                        <option value="">جميع الحالات</option>
                        <option value="published">منشور</option>
                        <option value="scheduled">مجدول</option>
                        <option value="draft">مسودة</option>
                        <option value="expired">منتهي</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="priorityFilter">
                        أولوية الإعلان
                    </label>

                    <select id="priorityFilter">
                        <option value="">جميع الأولويات</option>
                        <option value="normal">عادي</option>
                        <option value="important">مهم</option>
                        <option value="urgent">عاجل</option>
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
                    عرض {{ $totalAnnouncements }}
                    من {{ $totalAnnouncements }} إعلان
                </span>

            </div>

        </div>

        {{-- قائمة الإعلانات --}}

        <div class="table-box">

            <div class="table-header">
                <h3>قائمة الإعلانات المدرسية</h3>

                <p>
                    إجمالي الإعلانات:
                    {{ $totalAnnouncements }}
                </p>
            </div>

            @if($announcements->isEmpty())

                <div class="empty">
                    لا توجد إعلانات مدرسية حتى الآن.
                </div>

            @else

                <div class="table-responsive">

                    <table>

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان الإعلان</th>
                                <th>الفئات المستهدفة</th>
                                <th>الأولوية</th>
                                <th>الحالة</th>
                                <th>تاريخ النشر</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($announcements as $announcement)

                                @php
                                    $status = $getStatus($announcement);

                                    $statusLabel = match ($status) {
                                        'published' => 'منشور',
                                        'scheduled' => 'مجدول',
                                        'expired' => 'منتهي',
                                        default => 'مسودة',
                                    };

                                    $priorityClass = match ($announcement->priority) {
                                        'important' => 'priority-important',
                                        'urgent' => 'priority-urgent',
                                        default => 'priority-normal',
                                    };
                                @endphp

                                <tr
                                    class="announcement-row"
                                    data-search="{{ $announcement->title }} {{ $announcement->content }}"
                                    data-status="{{ $status }}"
                                    data-priority="{{ $announcement->priority }}"
                                >

                                    <td>
                                        {{ $announcement->id }}
                                    </td>

                                    <td class="announcement-title">
                                        {{ $announcement->title }}
                                    </td>

                                    <td>

                                        @forelse($announcement->targets as $target)

                                            <span class="target-badge">

                                                {{ $roleLabels[$target->role] ?? $target->role }}

                                                @if($target->schoolClass)
                                                    —
                                                    {{ $target->schoolClass->name }}
                                                @endif

                                                @if($target->section)
                                                    /
                                                    شعبة {{ $target->section->name }}
                                                @endif

                                            </span>

                                        @empty
                                            <span>غير محدد</span>
                                        @endforelse

                                    </td>

                                    <td>
                                        <span class="priority-badge {{ $priorityClass }}">
                                            {{ $announcement->priority_label }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="status-badge status-{{ $status }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $announcement->published_at?->format('Y-m-d H:i') ?? 'لم يُنشر' }}
                                    </td>

                                    <td>

                                        <div class="actions">

                                            <a
                                                href="{{ route('announcements.show', $announcement) }}"
                                                class="view-btn"
                                            >
                                                عرض
                                            </a>

                                            <a
                                                href="{{ route('announcements.edit', $announcement) }}"
                                                class="edit-btn"
                                            >
                                                تعديل
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('announcements.destroy', $announcement) }}"
                                                class="delete-announcement-form"
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

                <div id="noSearchResults" class="no-results">
                    لا توجد إعلانات مطابقة للبحث أو التصفية المحددة.
                </div>

            @endif

        </div>

    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('announcementSearch');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const resetButton = document.getElementById('resetFilters');
    const resultsLabel = document.getElementById('filterResults');
    const noResults = document.getElementById('noSearchResults');

    const rows = Array.from(
        document.querySelectorAll('.announcement-row')
    );

    function normalizeText(value) {
        return String(value || '')
            .trim()
            .toLocaleLowerCase()
            .replace(/[\u064B-\u065F\u0670]/g, '')
            .replace(/ـ/g, '');
    }

    function filterAnnouncements() {

        const searchValue = normalizeText(searchInput.value);
        const selectedStatus = statusFilter.value;
        const selectedPriority = priorityFilter.value;

        let visibleCount = 0;

        rows.forEach(function (row) {

            const matchesSearch =
                searchValue === '' ||
                normalizeText(row.dataset.search).includes(searchValue);

            const matchesStatus =
                selectedStatus === '' ||
                row.dataset.status === selectedStatus;

            const matchesPriority =
                selectedPriority === '' ||
                row.dataset.priority === selectedPriority;

            const shouldShow =
                matchesSearch &&
                matchesStatus &&
                matchesPriority;

            row.hidden = !shouldShow;

            if (shouldShow) {
                visibleCount++;
            }
        });

        resultsLabel.textContent =
            'عرض ' + visibleCount +
            ' من ' + rows.length + ' إعلان';

        if (noResults) {
            noResults.style.display =
                rows.length > 0 && visibleCount === 0
                    ? 'block'
                    : 'none';
        }
    }

    searchInput.addEventListener('input', filterAnnouncements);
    statusFilter.addEventListener('change', filterAnnouncements);
    priorityFilter.addEventListener('change', filterAnnouncements);

    resetButton.addEventListener('click', function () {

        searchInput.value = '';
        statusFilter.value = '';
        priorityFilter.value = '';

        filterAnnouncements();
        searchInput.focus();
    });

    document.querySelectorAll('.delete-announcement-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                if (form.dataset.submitting === 'true') {
                    event.preventDefault();
                    return;
                }

                const confirmed = confirm(
                    'هل أنت متأكد من حذف الإعلان المدرسي؟'
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

    filterAnnouncements();

});
</script>

</body>
</html>