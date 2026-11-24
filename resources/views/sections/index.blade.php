<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إدارة الشعب</title>

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
            width: 100%;
        }

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
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 5px;
        }

        .topbar p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.8;
        }

        .buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .add-btn {
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
            font-size: 14px;
        }

        .back-btn {
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
            font-size: 14px;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .info {
            background: #eef2ff;
            color: #3730a3;
            border-right: 4px solid #6366f1;
            line-height: 1.9;
        }

        /* بطاقات الإحصائيات */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 13px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
            border: 1px solid #eef0f4;
        }

        .stat-card .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }

        .stat-card.available .stat-value {
            color: #15803d;
        }

        .stat-card.full .stat-value {
            color: #dc2626;
        }

        /* الجدول */

        .table-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1350px;
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
            font-size: 13px;
            font-weight: 700;
        }

        td {
            font-size: 14px;
        }

        tbody tr:hover {
            background: #fafbff;
        }

        .section-name {
            font-weight: 700;
        }

        .number {
            font-weight: 600;
        }

        .seats-available {
            color: #15803d;
            font-weight: 700;
        }

        .seats-full {
            color: #dc2626;
            font-weight: 700;
        }

        /* المرحلة والقسم */

        .stage-badge {
            display: inline-block;
            background: #f3f4f6;
            color: #374151;
            padding: 7px 11px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .gender-badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .gender-boys {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .gender-girls {
            background: #fce7f3;
            color: #be185d;
        }

        .gender-unknown {
            background: #fef3c7;
            color: #92400e;
        }

        /* المشرف المسؤول */

        .supervisor-cell {
            min-width: 220px;
        }

        .supervisor-name {
            font-weight: 700;
            color: #111827;
        }

        .supervisor-details {
            display: block;
            color: #6b7280;
            font-size: 12px;
            margin-top: 5px;
        }

        .supervisor-unassigned {
            color: #b45309;
            font-size: 12px;
            font-weight: 600;
        }

        /* حالة الشعبة */

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-available {
            background: #dcfce7;
            color: #166534;
        }

        .status-full {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-invalid {
            background: #fef3c7;
            color: #92400e;
        }

        /* الإجراءات */

        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center;
        }

        .edit-btn {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 7px;
        }

        .delete-btn {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 7px 12px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 14px;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #6b7280;
        }

        @media (max-width: 1200px) {
            .stats {
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
                min-height: auto;
            }
        }

        @media (max-width: 768px) {
            .main {
                padding: 15px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .topbar h2 {
                font-size: 22px;
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
                <h2>إدارة الشعب</h2>

                <p>
                    متابعة الشعب الدراسية ومراحلها وأقسامها
                    والمشرفين المسؤولين وسعة كل شعبة
                </p>
            </div>

            <div class="buttons">

                <a
                    href="{{ route('classes.index') }}"
                    class="back-btn"
                >
                    الفصول
                </a>

                <a
                    href="{{ route('sections.create') }}"
                    class="add-btn"
                >
                    + إضافة شعبة
                </a>

            </div>

        </div>

        <!-- رسائل النجاح والخطأ -->

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

        <!-- ملاحظة الربط بالمشرفين -->

        <div class="alert info">
            <strong>ملاحظة:</strong>
            يتم تحديد المشرف المسؤول وفق المرحلة الدراسية
            والقسم (بنين أو بنات).

            حتى اكتمال ربط حسابات المشرفين،
            ستظهر عبارة «لم يتم تعيين مشرف».
        </div>

        <!-- حساب الإحصائيات -->

        @php
            $totalSections = $sections->count();

            $totalStudents = $sections->sum('students_count');

            $totalAvailableSeats = $sections->sum(function ($section) {
                $capacity = (int) $section->capacity;
                $studentsCount = (int) $section->students_count;

                return max(0, $capacity - $studentsCount);
            });

            $fullSections = $sections->filter(function ($section) {
                $capacity = (int) $section->capacity;
                $studentsCount = (int) $section->students_count;

                return $capacity > 0 && $studentsCount >= $capacity;
            })->count();
        @endphp

        <!-- بطاقات الملخص -->

        <div class="stats">

            <div class="stat-card">
                <div class="stat-label">إجمالي الشعب</div>
                <div class="stat-value">
                    {{ $totalSections }}
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">الطلاب المسجلون</div>
                <div class="stat-value">
                    {{ $totalStudents }}
                </div>
            </div>

            <div class="stat-card available">
                <div class="stat-label">المقاعد المتاحة</div>
                <div class="stat-value">
                    {{ $totalAvailableSeats }}
                </div>
            </div>

            <div class="stat-card full">
                <div class="stat-label">الشعب الممتلئة</div>
                <div class="stat-value">
                    {{ $fullSections }}
                </div>
            </div>

        </div>

        <!-- جدول الشعب -->

        <div class="table-box">

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصف الدراسي</th>
                        <th>المرحلة</th>
                        <th>الشعبة</th>
                        <th>القسم</th>
                        <th>المشرف المسؤول</th>
                        <th>السعة</th>
                        <th>الطلاب المسجلون</th>
                        <th>المقاعد المتاحة</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($sections as $section)

                    @php
                        $capacity = (int) $section->capacity;

                        $studentsCount = (int) $section->students_count;

                        $availableSeats = max(
                            0,
                            $capacity - $studentsCount
                        );

                        $isFull = $capacity > 0
                            && $studentsCount >= $capacity;

                        /*
                         * المرحلة مأخوذة من الصف الدراسي.
                         */
                        $stage = $section->schoolClass?->education_stage;

                        $stageLabel = match ($stage) {
                            'primary', 'ابتدائي', 'الابتدائية' => 'الابتدائية',
                            'middle', 'preparatory', 'إعدادي', 'الإعدادية' => 'الإعدادية',
                            'secondary', 'ثانوي', 'الثانوية' => 'الثانوية',
                            default => $stage ?: 'غير محددة',
                        };

                        /*
                         * القسم مأخوذ من بيانات الشعبة.
                         */
                        $gender = $section->gender_section;

                        $genderLabel = match ($gender) {
                            'boys' => 'بنين',
                            'girls' => 'بنات',
                            default => 'غير محدد',
                        };

                        /*
                         * الربط الفعلي بالمشرفين لم يُنفذ بعد.
                         * لا نعرض أسماء افتراضية.
                         */
                        $supervisorName = null;
                    @endphp

                    <tr>

                        <!-- رقم الشعبة -->
                        <td>
                            {{ $section->id }}
                        </td>

                        <!-- الصف الدراسي -->
                        <td>
                            {{ $section->schoolClass?->name ?? '-' }}
                        </td>

                        <!-- المرحلة -->
                        <td>
                            <span class="stage-badge">
                                {{ $stageLabel }}
                            </span>
                        </td>

                        <!-- الشعبة -->
                        <td class="section-name">
                            {{ $section->name }}
                        </td>

                        <!-- القسم -->
                        <td>

                            @if($gender === 'boys')

                                <span class="gender-badge gender-boys">
                                    بنين
                                </span>

                            @elseif($gender === 'girls')

                                <span class="gender-badge gender-girls">
                                    بنات
                                </span>

                            @else

                                <span class="gender-badge gender-unknown">
                                    غير محدد
                                </span>

                            @endif

                        </td>

                        <!-- المشرف المسؤول -->
                        <td class="supervisor-cell">

                            @if($supervisorName)

                                <span class="supervisor-name">
                                    {{ $supervisorName }}
                                </span>

                                <span class="supervisor-details">
                                    {{ $stageLabel }} – {{ $genderLabel }}
                                </span>

                            @else

                                <span class="supervisor-unassigned">
                                    لم يتم تعيين مشرف
                                </span>

                            @endif

                        </td>

                        <!-- السعة -->
                        <td class="number">
                            {{ $capacity }}
                        </td>

                        <!-- الطلاب المسجلون -->
                        <td class="number">
                            {{ $studentsCount }}
                        </td>

                        <!-- المقاعد المتاحة -->
                        <td>
                            <span class="{{ $availableSeats > 0 ? 'seats-available' : 'seats-full' }}">
                                {{ $availableSeats }}
                            </span>
                        </td>

                        <!-- الحالة -->
                        <td>

                            @if($capacity < 1)

                                <span class="status status-invalid">
                                    السعة غير محددة
                                </span>

                            @elseif($isFull)

                                <span class="status status-full">
                                    ممتلئة
                                </span>

                            @else

                                <span class="status status-available">
                                    متاحة
                                </span>

                            @endif

                        </td>

                        <!-- الإجراءات -->
                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('sections.edit', $section) }}"
                                    class="edit-btn"
                                >
                                    تعديل
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('sections.destroy', $section) }}"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الشعبة؟');"
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

                @empty

                    <tr>
                        <td colspan="11" class="empty">
                            لا توجد شعب حاليًا.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>
</html>