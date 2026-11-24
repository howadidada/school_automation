<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | توزيع المعلمين</title>

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

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 15px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            line-height: 1.9;
            font-size: 14px;
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

        .stat-card.teachers::before {
            background: #16a34a;
        }

        .stat-card.sections::before {
            background: #d97706;
        }

        .stat-card.subjects::before {
            background: #7c3aed;
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
            grid-template-columns: 2fr repeat(4, minmax(0, 1fr));
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

        .reset-btn:hover {
            background: #e2e8f0;
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

        th,
        td {
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
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .stage-badge {
            display: inline-block;
            background: #ecfeff;
            color: #155e75;
            padding: 6px 11px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap;
        }

        .edit-btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 13px;
        }

        .edit-btn:hover {
            background: #1d4ed8;
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

        @media (max-width: 1400px) {
            .filters-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
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
    /*
    |--------------------------------------------------------------------------
    | الإحصائيات
    |--------------------------------------------------------------------------
    */

    $totalAssignments = $assignments->count();

    $assignedTeachers = $assignments
        ->pluck('teacher_id')
        ->unique()
        ->count();

    $assignedSections = $assignments
        ->pluck('section_id')
        ->unique()
        ->count();

    $assignedSubjects = $assignments
        ->pluck('subject_id')
        ->unique()
        ->count();

    /*
    |--------------------------------------------------------------------------
    | خيارات الفلاتر
    |--------------------------------------------------------------------------
    */

    $subjectOptions = $assignments
        ->filter(fn ($assignment) => $assignment->subject !== null)
        ->map(fn ($assignment) => [
            'id' => $assignment->subject_id,
            'name' => $assignment->subject->name,
        ])
        ->unique('id')
        ->sortBy('name');

    $classOptions = $assignments
        ->filter(fn ($assignment) => $assignment->section?->schoolClass !== null)
        ->map(fn ($assignment) => [
            'id' => $assignment->section->schoolClass->id,
            'name' => $assignment->section->schoolClass->name,
            'stage' => $assignment->section->schoolClass->education_stage,
        ])
        ->unique('id')
        ->sortBy('name');

    $sectionOptions = $assignments
        ->filter(fn ($assignment) => $assignment->section !== null)
        ->map(fn ($assignment) => [
            'id' => $assignment->section_id,
            'name' =>
                ($assignment->section->schoolClass?->name ?? 'بدون صف')
                . ' - '
                . $assignment->section->name,
            'class_id' => $assignment->section->schoolClass?->id,
            'stage' => $assignment->section->schoolClass?->education_stage,
        ])
        ->unique('id')
        ->sortBy('name');
@endphp

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        {{-- العنوان وزر الإضافة --}}

        <div class="topbar">

            <div>
                <h2>توزيع المعلمين على الصفوف والشعب</h2>

                <p>
                    تحديد الصفوف والشعب التي يدرّس فيها كل معلم
                    وفق مادته الأساسية ومرحلته التعليمية.
                </p>
            </div>

            <a
                href="{{ route('teacher-assignments.create') }}"
                class="add-btn"
            >
                + توزيع معلم
            </a>

        </div>

        {{-- الرسائل --}}

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

        {{-- شرح آلية التوزيع --}}

        <div class="info-box">
            <strong>آلية توزيع المعلمين:</strong>
            لكل معلم مادة أساسية واحدة تُحدد في صفحة إدارة المعلمين،
            ويمكن توزيعه لتدريس هذه المادة في أكثر من صف وشعبة
            ضمن مرحلته التعليمية.
            لا يُسمح بتوزيع المعلم على مادة مختلفة أو تكرار توزيعه على الشعبة نفسها.
        </div>

        {{-- البطاقات الإحصائية --}}

        <div class="stats-grid">

            <div class="stat-card total">
                <div class="stat-label">إجمالي التوزيعات</div>
                <div class="stat-value">{{ $totalAssignments }}</div>
                <div class="stat-note">جميع التوزيعات التدريسية المسجلة</div>
            </div>

            <div class="stat-card teachers">
                <div class="stat-label">المعلمون الموزعون</div>
                <div class="stat-value">{{ $assignedTeachers }}</div>
                <div class="stat-note">عدد المعلمين المرتبطين بشعب</div>
            </div>

            <div class="stat-card sections">
                <div class="stat-label">الشعب المشمولة</div>
                <div class="stat-value">{{ $assignedSections }}</div>
                <div class="stat-note">عدد الشعب التي لديها توزيعات</div>
            </div>

            <div class="stat-card subjects">
                <div class="stat-label">المواد الموزعة</div>
                <div class="stat-value">{{ $assignedSubjects }}</div>
                <div class="stat-note">عدد المواد المختلفة في التوزيعات</div>
            </div>

        </div>

        {{-- البحث والفلاتر --}}

        <div class="filters-box">

            <h3 class="filters-title">
                البحث وتصفية توزيعات المعلمين
            </h3>

            <div class="filters-grid">

                {{-- البحث --}}

                <div class="filter-group">
                    <label for="assignmentSearch">البحث</label>

                    <input
                        type="search"
                        id="assignmentSearch"
                        placeholder="المعلم، الرقم الوظيفي، المادة، الصف أو الشعبة"
                        autocomplete="off"
                    >
                </div>

                {{-- المرحلة التعليمية --}}

                <div class="filter-group">
                    <label for="stageFilter">المرحلة التعليمية</label>

                    <select id="stageFilter">
                        <option value="">جميع المراحل</option>
                        <option value="primary">الابتدائية</option>
                        <option value="middle">الإعدادية</option>
                        <option value="secondary">الثانوية</option>
                    </select>
                </div>

                {{-- المادة --}}

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

                {{-- الصف --}}

                <div class="filter-group">
                    <label for="classFilter">الصف</label>

                    <select id="classFilter">
                        <option value="">جميع الصفوف</option>

                        @foreach($classOptions as $classOption)
                            <option
                                value="{{ $classOption['id'] }}"
                                data-stage="{{ $classOption['stage'] }}"
                            >
                                {{ $classOption['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- الشعبة --}}

                <div class="filter-group">
                    <label for="sectionFilter">الشعبة</label>

                    <select id="sectionFilter">
                        <option value="">جميع الشعب</option>

                        @foreach($sectionOptions as $sectionOption)
                            <option
                                value="{{ $sectionOption['id'] }}"
                                data-class="{{ $sectionOption['class_id'] }}"
                                data-stage="{{ $sectionOption['stage'] }}"
                            >
                                {{ $sectionOption['name'] }}
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
                    عرض {{ $totalAssignments }} من {{ $totalAssignments }} توزيع
                </span>

            </div>

        </div>

        {{-- جدول التوزيعات --}}

        <div class="table-box">

            <div class="table-header">
                <h3>قائمة توزيعات المعلمين</h3>
                <p>عدد التوزيعات: {{ $totalAssignments }}</p>
            </div>

            @if($assignments->isEmpty())

                <div class="empty">
                    لا توجد توزيعات للمعلمين حاليًا.
                </div>

            @else

                <div class="table-responsive">

                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المعلم</th>
                                <th>الرقم الوظيفي</th>
                                <th>المادة</th>
                                <th>المرحلة التعليمية</th>
                                <th>الصف</th>
                                <th>الشعبة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($assignments as $index => $assignment)

                                @php
                                    $teacherName =
                                        $assignment->teacher?->user?->name ?? '';

                                    $employeeNumber =
                                        $assignment->teacher?->employee_number ?? '';

                                    $subjectName =
                                        $assignment->subject?->name ?? '';

                                    $schoolClass =
                                        $assignment->section?->schoolClass;

                                    $className =
                                        $schoolClass?->name ?? '';

                                    $sectionName =
                                        $assignment->section?->name ?? '';

                                    $educationStage =
                                        $schoolClass?->education_stage ?? '';

                                    $stageLabel = match ($educationStage) {
                                        'primary' => 'الابتدائية',
                                        'middle' => 'الإعدادية',
                                        'secondary' => 'الثانوية',
                                        default => 'غير محددة',
                                    };
                                @endphp

                                <tr
                                    class="assignment-row"
                                    data-search="{{ $teacherName }} {{ $employeeNumber }} {{ $subjectName }} {{ $className }} {{ $sectionName }} {{ $stageLabel }}"
                                    data-stage="{{ $educationStage }}"
                                    data-subject="{{ $assignment->subject_id ?? '' }}"
                                    data-class="{{ $schoolClass?->id ?? '' }}"
                                    data-section="{{ $assignment->section_id ?? '' }}"
                                >

                                    <td>{{ $index + 1 }}</td>

                                    <td class="teacher-name">
                                        {{ $teacherName ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $employeeNumber ?: '-' }}
                                    </td>

                                    <td>
                                        <span class="subject-badge">
                                            {{ $subjectName ?: '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="stage-badge">
                                            {{ $stageLabel }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $className ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $sectionName ?: '-' }}
                                    </td>

                                    <td>
                                        <div class="actions">

                                            <a
                                                href="{{ route('teacher-assignments.edit', $assignment) }}"
                                                class="edit-btn"
                                            >
                                                تعديل
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('teacher-assignments.destroy', $assignment) }}"
                                                class="delete-assignment-form"
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
                    لا توجد توزيعات مطابقة للبحث أو الفلاتر المحددة.
                </div>

            @endif

        </div>

    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | عناصر البحث والتصفية
        |--------------------------------------------------------------------------
        */

        const searchInput = document.getElementById('assignmentSearch');
        const stageFilter = document.getElementById('stageFilter');
        const subjectFilter = document.getElementById('subjectFilter');
        const classFilter = document.getElementById('classFilter');
        const sectionFilter = document.getElementById('sectionFilter');

        const resetButton = document.getElementById('resetFilters');
        const resultsLabel = document.getElementById('filterResults');
        const noResults = document.getElementById('noSearchResults');

        const rows = Array.from(
            document.querySelectorAll('.assignment-row')
        );

        /*
        |--------------------------------------------------------------------------
        | توحيد النصوص لتحسين البحث
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
        | تحديث خيارات الصفوف حسب المرحلة
        |--------------------------------------------------------------------------
        */

        function updateClassOptions() {

            const selectedStage = stageFilter.value;

            Array.from(classFilter.options).forEach(function (option) {

                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                const matchesStage =
                    selectedStage === '' ||
                    option.dataset.stage === selectedStage;

                option.hidden = !matchesStage;
            });

            const selectedOption = classFilter.selectedOptions[0];

            if (selectedOption && selectedOption.hidden) {
                classFilter.value = '';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | تحديث خيارات الشعب حسب المرحلة والصف
        |--------------------------------------------------------------------------
        */

        function updateSectionOptions() {

            const selectedStage = stageFilter.value;
            const selectedClass = classFilter.value;

            Array.from(sectionFilter.options).forEach(function (option) {

                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                const matchesStage =
                    selectedStage === '' ||
                    option.dataset.stage === selectedStage;

                const matchesClass =
                    selectedClass === '' ||
                    option.dataset.class === selectedClass;

                option.hidden = !(matchesStage && matchesClass);
            });

            const selectedOption = sectionFilter.selectedOptions[0];

            if (selectedOption && selectedOption.hidden) {
                sectionFilter.value = '';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | تنفيذ البحث والتصفية
        |--------------------------------------------------------------------------
        */

        function filterAssignments() {

            const searchValue = normalizeText(searchInput.value);

            const selectedStage = stageFilter.value;
            const selectedSubject = subjectFilter.value;
            const selectedClass = classFilter.value;
            const selectedSection = sectionFilter.value;

            let visibleCount = 0;

            rows.forEach(function (row) {

                const matchesSearch =
                    searchValue === '' ||
                    normalizeText(row.dataset.search).includes(searchValue);

                const matchesStage =
                    selectedStage === '' ||
                    row.dataset.stage === selectedStage;

                const matchesSubject =
                    selectedSubject === '' ||
                    row.dataset.subject === selectedSubject;

                const matchesClass =
                    selectedClass === '' ||
                    row.dataset.class === selectedClass;

                const matchesSection =
                    selectedSection === '' ||
                    row.dataset.section === selectedSection;

                const shouldShow =
                    matchesSearch &&
                    matchesStage &&
                    matchesSubject &&
                    matchesClass &&
                    matchesSection;

                row.hidden = !shouldShow;

                if (shouldShow) {
                    visibleCount++;
                }
            });

            resultsLabel.textContent =
                'عرض ' + visibleCount +
                ' من ' + rows.length + ' توزيع';

            if (noResults) {
                noResults.style.display =
                    rows.length > 0 && visibleCount === 0
                        ? 'block'
                        : 'none';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ربط الأحداث
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('input', filterAssignments);

        stageFilter.addEventListener('change', function () {
            updateClassOptions();
            updateSectionOptions();
            filterAssignments();
        });

        subjectFilter.addEventListener('change', filterAssignments);

        classFilter.addEventListener('change', function () {
            updateSectionOptions();
            filterAssignments();
        });

        sectionFilter.addEventListener('change', filterAssignments);

        /*
        |--------------------------------------------------------------------------
        | إعادة ضبط الفلاتر
        |--------------------------------------------------------------------------
        */

        resetButton.addEventListener('click', function () {

            searchInput.value = '';
            stageFilter.value = '';
            subjectFilter.value = '';
            classFilter.value = '';
            sectionFilter.value = '';

            updateClassOptions();
            updateSectionOptions();
            filterAssignments();

            searchInput.focus();
        });

        /*
        |--------------------------------------------------------------------------
        | حماية نموذج الحذف من الإرسال المتكرر
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.delete-assignment-form')
            .forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    if (form.dataset.submitting === 'true') {
                        event.preventDefault();
                        return;
                    }

                    const confirmed = confirm(
                        'هل أنت متأكد من حذف هذا التوزيع التدريسي؟'
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
        | تشغيل الفلاتر عند فتح الصفحة
        |--------------------------------------------------------------------------
        */

        updateClassOptions();
        updateSectionOptions();
        filterAssignments();

    });
</script>

</body>
</html>