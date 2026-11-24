<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إدارة الجداول الدراسية</title>

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

        .stat-card.teachers::before {
            background: #7c3aed;
        }

        .stat-card.students::before {
            background: #16a34a;
        }

        .stat-card.exams::before {
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
            grid-template-columns: 2fr 1fr 1fr 1fr;
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

        /* جدول الملفات */

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

        .file-title {
            font-weight: 600;
            color: #111827;
        }

        .type-badge,
        .target-badge {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 12px;
            white-space: nowrap;
        }

        .type-teacher {
            background: #ede9fe;
            color: #6d28d9;
        }

        .type-student {
            background: #dcfce7;
            color: #166534;
        }

        .type-exam {
            background: #fef3c7;
            color: #92400e;
        }

        .type-unknown {
            background: #f1f5f9;
            color: #475569;
        }

        .target-badge {
            background: #eef2ff;
            color: #3730a3;
            white-space: normal;
            line-height: 1.7;
        }

        .muted {
            color: #94a3b8;
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

        @media (max-width: 1300px) {
            .filters-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
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
    /*
    |--------------------------------------------------------------------------
    | الإحصائيات
    |--------------------------------------------------------------------------
    */

    $totalFiles = $scheduleFiles->count();

    $teacherSchedules = $scheduleFiles
        ->where('type', 'teacher_schedule')
        ->count();

    $studentSchedules = $scheduleFiles
        ->where('type', 'student_schedule')
        ->count();

    $examSchedules = $scheduleFiles
        ->where('type', 'exam_schedule')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | خيارات الفصول والشعب
    |--------------------------------------------------------------------------
    */

    $classes = $scheduleFiles
        ->filter(fn ($file) => $file->section?->schoolClass)
        ->map(fn ($file) => [
            'id' => $file->section->schoolClass->id,
            'name' => $file->section->schoolClass->name,
        ])
        ->unique('id')
        ->sortBy('name')
        ->values();

    $sections = $scheduleFiles
        ->filter(fn ($file) => $file->section)
        ->map(fn ($file) => [
            'id' => $file->section->id,
            'name' => $file->section->name,
            'class_id' => $file->section->school_class_id,
            'class_name' => $file->section->schoolClass?->name ?? '',
        ])
        ->unique('id')
        ->sortBy('name')
        ->values();
@endphp

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        {{-- العنوان --}}

        <div class="topbar">

            <div>
                <h2>إدارة الجداول الدراسية</h2>

                <p>
                    إدارة جداول حصص المعلمين، وجداول حصص الطلاب،
                    وجداول الامتحانات.
                </p>
            </div>

            <a
                href="{{ route('schedule-files.create') }}"
                class="add-btn"
            >
                + رفع جدول جديد
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
            يتم رفع ملفات الجداول الجاهزة وتحديد نوع كل جدول والجهة المستهدفة.
            <br>
            <strong>جدول حصص المعلم:</strong>
            يرتبط بمعلم محدد ويوضح توزيع حصصه.
            <br>
            <strong>جدول حصص الطالب:</strong>
            يرتبط بشعبة محددة ويوضح حصص الطلاب.
            <br>
            <strong>جدول الامتحانات:</strong>
            يرتبط بشعبة محددة ويوضح مواعيد الامتحانات.
        </div>

        {{-- الإحصائيات --}}

        <div class="stats-grid">

            <div class="stat-card total">
                <div class="stat-label">إجمالي ملفات الجداول</div>
                <div class="stat-value">{{ $totalFiles }}</div>
                <div class="stat-note">جميع الملفات المسجلة في النظام</div>
            </div>

            <div class="stat-card teachers">
                <div class="stat-label">جداول حصص المعلمين</div>
                <div class="stat-value">{{ $teacherSchedules }}</div>
                <div class="stat-note">ملفات مرتبطة بمعلمين محددين</div>
            </div>

            <div class="stat-card students">
                <div class="stat-label">جداول حصص الطلاب</div>
                <div class="stat-value">{{ $studentSchedules }}</div>
                <div class="stat-note">ملفات مرتبطة بالشعب الدراسية</div>
            </div>

            <div class="stat-card exams">
                <div class="stat-label">جداول الامتحانات</div>
                <div class="stat-value">{{ $examSchedules }}</div>
                <div class="stat-note">ملفات مواعيد الامتحانات</div>
            </div>

        </div>

        {{-- البحث والتصفية --}}

        <div class="filters-box">

            <h3 class="filters-title">
                البحث وتصفية الجداول
            </h3>

            <div class="filters-grid">

                <div class="filter-group">

                    <label for="scheduleSearch">
                        البحث عن جدول
                    </label>

                    <input
                        type="search"
                        id="scheduleSearch"
                        placeholder="العنوان أو المعلم أو الصف أو الشعبة"
                        autocomplete="off"
                    >

                </div>

                <div class="filter-group">

                    <label for="typeFilter">
                        نوع الجدول
                    </label>

                    <select id="typeFilter">
                        <option value="">جميع الأنواع</option>
                        <option value="teacher_schedule">جدول حصص المعلم</option>
                        <option value="student_schedule">جدول حصص الطالب</option>
                        <option value="exam_schedule">جدول الامتحانات</option>
                        <option value="unknown">غير محدد (ملفات قديمة)</option>
                    </select>

                </div>

                <div class="filter-group">

                    <label for="classFilter">
                        الصف الدراسي
                    </label>

                    <select id="classFilter">

                        <option value="">
                            جميع الصفوف
                        </option>

                        @foreach($classes as $class)
                            <option value="{{ $class['id'] }}">
                                {{ $class['name'] }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="filter-group">

                    <label for="sectionFilter">
                        الشعبة
                    </label>

                    <select id="sectionFilter">

                        <option value="">
                            جميع الشعب
                        </option>

                        @foreach($sections as $section)
                            <option
                                value="{{ $section['id'] }}"
                                data-class="{{ $section['class_id'] }}"
                            >
                                {{ $section['class_name'] }}
                                —
                                شعبة {{ $section['name'] }}
                            </option>
                        @endforeach

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
                    عرض {{ $totalFiles }} من {{ $totalFiles }} ملف
                </span>

            </div>

        </div>

        {{-- قائمة الجداول --}}

        <div class="table-box">

            <div class="table-header">

                <h3>قائمة ملفات الجداول</h3>

                <p>
                    إجمالي الملفات: {{ $totalFiles }}
                </p>

            </div>

            @if($scheduleFiles->isEmpty())

                <div class="empty">
                    لا توجد جداول دراسية مرفوعة حاليًا.
                </div>

            @else

                <div class="table-responsive">

                    <table>

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان الجدول</th>
                                <th>نوع الجدول</th>
                                <th>الجهة المستهدفة</th>
                                <th>تم الرفع بواسطة</th>
                                <th>تاريخ الرفع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($scheduleFiles as $file)

                                @php
                                    $type = $file->type;

                                    $typeLabel = match ($type) {
                                        'teacher_schedule' => 'جدول حصص المعلم',
                                        'student_schedule' => 'جدول حصص الطالب',
                                        'exam_schedule' => 'جدول الامتحانات',
                                        default => 'غير محدد',
                                    };

                                    $typeClass = match ($type) {
                                        'teacher_schedule' => 'type-teacher',
                                        'student_schedule' => 'type-student',
                                        'exam_schedule' => 'type-exam',
                                        default => 'type-unknown',
                                    };

                                    $teacherName = $file->teacher?->user?->name ?? '';

                                    $className = $file->section?->schoolClass?->name ?? '';

                                    $sectionName = $file->section?->name ?? '';

                                    $targetLabel = match ($type) {
                                        'teacher_schedule' => $teacherName !== ''
                                            ? $teacherName
                                            : 'معلم غير محدد',

                                        'student_schedule', 'exam_schedule' =>
                                            $className !== '' || $sectionName !== ''
                                                ? trim($className . ' — شعبة ' . $sectionName)
                                                : 'شعبة غير محددة',

                                        default =>
                                            $className !== '' || $sectionName !== ''
                                                ? trim($className . ' — شعبة ' . $sectionName)
                                                : 'غير محدد',
                                    };

                                    $filterType = in_array(
                                        $type,
                                        ['teacher_schedule', 'student_schedule', 'exam_schedule'],
                                        true
                                    ) ? $type : 'unknown';
                                @endphp

                                <tr
                                    class="schedule-row"
                                    data-search="{{ $file->title }} {{ $typeLabel }} {{ $teacherName }} {{ $className }} {{ $sectionName }}"
                                    data-type="{{ $filterType }}"
                                    data-class="{{ $file->section?->schoolClass?->id ?? '' }}"
                                    data-section="{{ $file->section_id ?? '' }}"
                                >

                                    <td>{{ $file->id }}</td>

                                    <td class="file-title">
                                        {{ $file->title }}
                                    </td>

                                    <td>
                                        <span class="type-badge {{ $typeClass }}">
                                            {{ $typeLabel }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="target-badge">
                                            {{ $targetLabel }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $file->uploader?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $file->created_at?->format('Y-m-d H:i') ?? '-' }}
                                    </td>

                                    <td>
                                        <div class="actions">

                                            <a
                                                href="{{ route('schedule-files.show', $file) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="view-btn"
                                            >
                                                عرض
                                            </a>

                                            <a
                                                href="{{ route('schedule-files.edit', $file) }}"
                                                class="edit-btn"
                                            >
                                                تعديل
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('schedule-files.destroy', $file) }}"
                                                class="delete-schedule-form"
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
                    لا توجد جداول مطابقة للبحث أو التصفية المحددة.
                </div>

            @endif

        </div>

    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const searchInput = document.getElementById('scheduleSearch');
        const typeFilter = document.getElementById('typeFilter');
        const classFilter = document.getElementById('classFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const resetButton = document.getElementById('resetFilters');
        const resultsLabel = document.getElementById('filterResults');
        const noResults = document.getElementById('noSearchResults');

        const rows = Array.from(
            document.querySelectorAll('.schedule-row')
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
        | تحديث خيارات الشعب حسب الصف
        |--------------------------------------------------------------------------
        */

        function updateSectionOptions() {

            const selectedClass = classFilter.value;

            Array.from(sectionFilter.options).forEach(function (option) {

                if (option.value === '') {
                    option.hidden = false;
                    option.disabled = false;
                    return;
                }

                const matches =
                    selectedClass === '' ||
                    option.dataset.class === selectedClass;

                option.hidden = !matches;
                option.disabled = !matches;
            });

            const selectedOption = sectionFilter.selectedOptions[0];

            if (selectedOption && selectedOption.disabled) {
                sectionFilter.value = '';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | البحث والتصفية
        |--------------------------------------------------------------------------
        */

        function filterSchedules() {

            const searchValue = normalizeText(searchInput.value);
            const selectedType = typeFilter.value;
            const selectedClass = classFilter.value;
            const selectedSection = sectionFilter.value;

            let visibleCount = 0;

            rows.forEach(function (row) {

                const matchesSearch =
                    searchValue === '' ||
                    normalizeText(row.dataset.search).includes(searchValue);

                const matchesType =
                    selectedType === '' ||
                    row.dataset.type === selectedType;

                const matchesClass =
                    selectedClass === '' ||
                    row.dataset.class === selectedClass;

                const matchesSection =
                    selectedSection === '' ||
                    row.dataset.section === selectedSection;

                const shouldShow =
                    matchesSearch &&
                    matchesType &&
                    matchesClass &&
                    matchesSection;

                row.hidden = !shouldShow;

                if (shouldShow) {
                    visibleCount++;
                }
            });

            resultsLabel.textContent =
                'عرض ' + visibleCount +
                ' من ' + rows.length + ' ملف';

            if (noResults) {
                noResults.style.display =
                    rows.length > 0 && visibleCount === 0
                        ? 'block'
                        : 'none';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | الأحداث
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('input', filterSchedules);

        typeFilter.addEventListener('change', filterSchedules);

        classFilter.addEventListener('change', function () {
            updateSectionOptions();
            filterSchedules();
        });

        sectionFilter.addEventListener('change', filterSchedules);

        resetButton.addEventListener('click', function () {
            searchInput.value = '';
            typeFilter.value = '';
            classFilter.value = '';
            sectionFilter.value = '';

            updateSectionOptions();
            filterSchedules();

            searchInput.focus();
        });

        /*
        |--------------------------------------------------------------------------
        | تأكيد الحذف
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.delete-schedule-form')
            .forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    if (form.dataset.submitting === 'true') {
                        event.preventDefault();
                        return;
                    }

                    const confirmed = confirm(
                        'هل أنت متأكد من حذف ملف الجدول الدراسي؟'
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

        updateSectionOptions();
        filterSchedules();
    });
</script>

</body>
</html>