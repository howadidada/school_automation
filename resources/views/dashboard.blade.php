<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>صلة | لوحة التحكم</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
        }

        :root {
            --primary: #3478f6;
            --primary-dark: #245fc9;
            --primary-soft: #eaf3ff;
            --primary-soft-2: #f4f8ff;

            --background: #f4f8fd;
            --card: #ffffff;

            --text: #20304a;
            --muted: #8793a5;

            --border: #e5edf7;

            --sidebar: #17345e;
            --sidebar-hover: #224b83;

            --green-soft: #eaf8f0;
            --green: #44a76b;

            --orange-soft: #fff1e8;
            --orange: #e88548;

            --purple-soft: #f1edff;
            --purple: #7868d8;
        }

        body {
            background: var(--background);
            color: var(--text);
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }


        /* ==========================================
           Sidebar
        ========================================== */

        .sidebar {
            width: 260px;
            min-height: 100vh;

            background:
                linear-gradient(
                    180deg,
                    #17345e 0%,
                    #142b4e 100%
                );

            color: white;

            padding: 25px 18px;

            display: flex;
            flex-direction: column;

            flex-shrink: 0;

            box-shadow:
                -4px 0 18px rgba(21, 57, 99, 0.08);
        }

        .brand {
            margin-bottom: 35px;
            padding: 0 10px;
        }

        .brand h1 {
            font-size: 31px;
            margin-bottom: 5px;
            color: white;
        }

        .brand span {
            color: #d8e6fb;
            font-size: 13px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            color: #e1ebfa;

            text-decoration: none;

            padding: 13px 14px;

            border-radius: 11px;

            transition: 0.2s;
        }

        .menu-item:hover,
        .menu-item.active {
            background: var(--sidebar-hover);
            color: white;
        }

        .logout-area {
            margin-top: auto;
            padding-top: 25px;
        }

        .logout-btn {
            width: 100%;

            padding: 11px;

            border: 1px solid rgba(255, 255, 255, 0.18);

            border-radius: 10px;

            color: white;

            background: rgba(255, 255, 255, 0.07);

            cursor: pointer;

            transition: 0.2s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.14);
        }


        /* ==========================================
           Main
        ========================================== */

        .main {
            flex: 1;
            min-width: 0;

            padding: 26px 28px 40px;

            position: relative;
            overflow: hidden;
        }

        /*
        زخارف خفيفة مثل تطبيق صلة
        */

        .main::before {
            content: "";

            position: absolute;

            width: 220px;
            height: 220px;

            border-radius: 50%;

            background: #e6f1ff;

            top: -90px;
            left: -70px;

            opacity: 0.65;

            z-index: 0;
        }

        .main::after {
            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            border-radius: 50%;

            background: #e8f3ff;

            bottom: -80px;
            right: 20px;

            opacity: 0.45;

            z-index: 0;
        }

        .main > * {
            position: relative;
            z-index: 1;
        }


        /* ==========================================
           Topbar
        ========================================== */

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 20px;

            margin-bottom: 24px;
        }

        .welcome-title h1 {
            font-size: 27px;
            font-weight: 750;
            margin-bottom: 6px;

            color: #20304a;
        }

        .welcome-title p {
            color: var(--muted);
            font-size: 14px;
        }

        .admin-box {
            display: flex;
            align-items: center;

            gap: 11px;

            background: rgba(255, 255, 255, 0.92);

            border: 1px solid var(--border);

            border-radius: 16px;

            padding: 10px 15px;

            box-shadow:
                0 6px 18px rgba(56, 112, 190, 0.08);
        }

        .admin-avatar {
            width: 43px;
            height: 43px;

            border-radius: 50%;

            background: var(--primary-soft);

            color: var(--primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;
            font-weight: 700;
        }

        .admin-info strong {
            display: block;

            color: #20304a;

            font-size: 14px;

            margin-bottom: 3px;
        }

        .admin-info span {
            font-size: 12px;
            color: var(--muted);
        }


        /* ==========================================
           Main Statistics
        ========================================== */

        .main-stats {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(190px, 1fr));

            gap: 16px;

            margin-bottom: 18px;
        }

        .big-stat {
            background: rgba(255, 255, 255, 0.94);

            border-radius: 19px;

            padding: 20px;

            display: flex;
            align-items: center;

            gap: 16px;

            min-height: 125px;

            border: 1px solid var(--border);

            box-shadow:
                0 8px 22px rgba(57, 107, 171, 0.07);

            transition: 0.2s;
        }

        .big-stat:hover {
            transform: translateY(-2px);

            box-shadow:
                0 10px 26px rgba(57, 107, 171, 0.10);
        }

        .big-stat-icon {
            width: 54px;
            height: 54px;

            border-radius: 16px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }

        .big-stat-icon svg {
            width: 26px;
            height: 26px;
        }

        .icon-students {
            background: var(--purple-soft);
            color: var(--purple);
        }

        .icon-teachers {
            background: var(--green-soft);
            color: var(--green);
        }

        .icon-classes {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .icon-subjects {
            background: var(--orange-soft);
            color: var(--orange);
        }

        .big-stat-data {
            min-width: 0;
        }

        .big-stat-number {
            font-size: 29px;

            font-weight: 750;

            color: #20304a;

            margin-bottom: 5px;
        }

        .big-stat-label {
            color: #53627a;

            font-size: 14px;

            font-weight: 600;
        }

        .big-stat-note {
            color: #9aa5b4;

            font-size: 11px;

            margin-top: 6px;
        }


        /* ==========================================
           Secondary Statistics
        ========================================== */

        .small-stats {
            display: grid;

            grid-template-columns:
                repeat(5, minmax(145px, 1fr));

            gap: 14px;

            margin-bottom: 22px;
        }

        .small-stat {
            background: rgba(255, 255, 255, 0.94);

            border: 1px solid var(--border);

            border-radius: 16px;

            padding: 16px 18px;

            box-shadow:
                0 6px 17px rgba(57, 107, 171, 0.05);
        }

        .small-stat span {
            display: block;

            color: #7d899a;

            font-size: 12px;

            margin-bottom: 8px;
        }

        .small-stat strong {
            font-size: 23px;
            color: #20304a;
        }


        /* ==========================================
           Main Dashboard Grid
        ========================================== */

        .content-grid {
            display: grid;

            grid-template-columns:
                minmax(0, 2fr)
                minmax(280px, 1fr);

            gap: 18px;
        }

        .panel {
            background: rgba(255, 255, 255, 0.95);

            border-radius: 19px;

            padding: 20px;

            border: 1px solid var(--border);

            box-shadow:
                0 8px 22px rgba(57, 107, 171, 0.06);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            margin-bottom: 20px;
        }

        .panel-header h3 {
            font-size: 17px;
            color: #20304a;
        }

        .panel-header span {
            color: #9aa5b4;
            font-size: 12px;
        }


        /* ==========================================
           Chart
        ========================================== */

        .chart-area {
            min-height: 290px;

            display: flex;
            align-items: flex-end;

            gap: 14px;

            padding:
                22px 6px
                8px;

            border-top:
                1px solid #edf3fb;
        }

        .chart-column {
            flex: 1;

            min-width: 45px;

            text-align: center;
        }

        .chart-number {
            display: block;

            font-size: 12px;

            color: #69788e;

            margin-bottom: 7px;
        }

        .chart-track {
            height: 190px;

            display: flex;
            align-items: flex-end;
            justify-content: center;

            border-bottom:
                1px solid #e6edf7;
        }

        .chart-bar {
            width: 46px;

            min-height: 4px;

            background:
                linear-gradient(
                    180deg,
                    #7bb2ff 0%,
                    #3478f6 100%
                );

            border-radius:
                10px 10px 4px 4px;

            box-shadow:
                0 5px 14px rgba(52, 120, 246, 0.18);

            transition: 0.2s;
        }

        .chart-bar:hover {
            opacity: 0.88;
        }

        .chart-label {
            margin-top: 10px;

            font-size: 11px;

            color: #6f7d90;

            line-height: 1.5;
        }

        .empty-chart {
            width: 100%;

            text-align: center;

            color: #9aa5b4;

            padding: 80px 20px;
        }


        /* ==========================================
           Summary
        ========================================== */

        .summary-list {
            display: flex;
            flex-direction: column;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            padding: 14px 0;

            border-bottom:
                1px solid #eef3f9;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #68778c;
            font-size: 13px;
        }

        .summary-value {
            min-width: 35px;

            text-align: center;

            padding: 5px 9px;

            border-radius: 9px;

            background: var(--primary-soft);

            color: var(--primary-dark);

            font-weight: 700;

            font-size: 13px;
        }


        /* ==========================================
           Schedule File
        ========================================== */

        .schedule-box {
            margin-top: 18px;

            padding: 17px;

            background:
                linear-gradient(
                    135deg,
                    #f5f9ff,
                    #edf5ff
                );

            border-radius: 14px;

            border: 1px solid #deebfa;
        }

        .schedule-box small {
            color: #8c98a8;

            display: block;

            margin-bottom: 7px;
        }

        .schedule-box strong {
            display: block;

            color: #20304a;

            font-size: 14px;

            margin-bottom: 5px;
        }

        .schedule-box p {
            color: #78869a;

            font-size: 12px;
        }

        .schedule-link {
            margin-top: 12px;

            display: inline-block;

            color: var(--primary);

            font-size: 12px;

            text-decoration: none;

            font-weight: 700;
        }


        /* ==========================================
           Responsive
        ========================================== */

        @media (max-width: 1250px) {

            .main-stats {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .small-stats {
                grid-template-columns:
                    repeat(3, 1fr);
            }

        }

        @media (max-width: 950px) {

            .sidebar {
                width: 220px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 700px) {

            .layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
            }

            .main {
                padding: 18px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .main-stats {
                grid-template-columns: 1fr;
            }

            .small-stats {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .chart-area {
                overflow-x: auto;
            }

        }

    </style>

</head>


<body>

<div class="layout">

    @include('partials.sidebar')


    <main class="main">


        <!-- ==========================================
             Topbar
        =========================================== -->

        <div class="topbar">

            <div class="welcome-title">

                <h1>
                    صباح الخير، {{ auth()->user()->name }}
                </h1>

                <p>
                    إليك نظرة عامة على بيانات نظام صلة.
                </p>

            </div>


            <div class="admin-box">

                <div class="admin-avatar">
                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                </div>

                <div class="admin-info">

                    <strong>
                        {{ auth()->user()->name }}
                    </strong>

                    <span>
                        @if(auth()->user()->role?->name === 'admin')
                            مدير النظام
                        @else
                            {{ auth()->user()->role?->name ?? 'مستخدم' }}
                        @endif
                    </span>

                </div>

            </div>

        </div>



        <!-- ==========================================
             Main Statistics
        =========================================== -->

        <div class="main-stats">


            <div class="big-stat">

                <div class="big-stat-icon icon-students">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M2 9l10-5 10 5-10 5L2 9z"/>
                        <path d="M6 11v5c3 2 9 2 12 0v-5"/>
                    </svg>

                </div>

                <div class="big-stat-data">

                    <div class="big-stat-number">
                        {{ $stats['students'] ?? 0 }}
                    </div>

                    <div class="big-stat-label">
                        طالب وطالبة
                    </div>

                    <div class="big-stat-note">
                        إجمالي الطلاب المسجلين
                    </div>

                </div>

            </div>



            <div class="big-stat">

                <div class="big-stat-icon icon-teachers">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M5 21v-2a7 7 0 0114 0v2"/>
                    </svg>

                </div>

                <div class="big-stat-data">

                    <div class="big-stat-number">
                        {{ $stats['teachers'] ?? 0 }}
                    </div>

                    <div class="big-stat-label">
                        معلم ومعلمة
                    </div>

                    <div class="big-stat-note">
                        إجمالي المعلمين
                    </div>

                </div>

            </div>



            <div class="big-stat">

                <div class="big-stat-icon icon-classes">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M3 6h18v13H3z"/>
                        <path d="M8 6V3h8v3"/>
                        <path d="M8 11h8"/>
                        <path d="M8 15h5"/>
                    </svg>

                </div>

                <div class="big-stat-data">

                    <div class="big-stat-number">
                        {{ $stats['classes'] ?? 0 }}
                    </div>

                    <div class="big-stat-label">
                        فصل دراسي
                    </div>

                    <div class="big-stat-note">
                        الفصول المسجلة
                    </div>

                </div>

            </div>



            <div class="big-stat">

                <div class="big-stat-icon icon-subjects">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M4 4h14a2 2 0 012 2v14H6a2 2 0 01-2-2V4z"/>
                        <path d="M8 8h8"/>
                        <path d="M8 12h8"/>
                        <path d="M8 16h5"/>
                    </svg>

                </div>

                <div class="big-stat-data">

                    <div class="big-stat-number">
                        {{ $stats['subjects'] ?? 0 }}
                    </div>

                    <div class="big-stat-label">
                        مادة دراسية
                    </div>

                    <div class="big-stat-note">
                        المواد المسجلة
                    </div>

                </div>

            </div>

        </div>



        <!-- ==========================================
             Secondary Statistics
        =========================================== -->

        <div class="small-stats">

            <div class="small-stat">
                <span>المستخدمون</span>
                <strong>{{ $stats['users'] ?? 0 }}</strong>
            </div>

            <div class="small-stat">
                <span>أولياء الأمور</span>
                <strong>{{ $stats['parents'] ?? 0 }}</strong>
            </div>

            <div class="small-stat">
                <span>الشعب</span>
                <strong>{{ $stats['sections'] ?? 0 }}</strong>
            </div>

            <div class="small-stat">
                <span>إسنادات المعلمين</span>
                <strong>{{ $stats['assignments'] ?? 0 }}</strong>
            </div>

            <div class="small-stat">
                <span>ملفات الجدول</span>
                <strong>{{ $stats['schedule_files'] ?? 0 }}</strong>
            </div>

        </div>



        <!-- ==========================================
             Lower Dashboard
        =========================================== -->

        <div class="content-grid">


            <section class="panel">

                <div class="panel-header">

                    <h3>
                        أعداد الطلاب حسب الشعب
                    </h3>

                    <span>
                        توزيع الطلاب المسجلين
                    </span>

                </div>


                @if($sectionsStats->count() > 0)

                    <div class="chart-area">

                        @foreach($sectionsStats as $section)

                            @php

                                $barHeight =
                                    ($section->students_count /
                                    $maxSectionStudents) * 100;

                            @endphp


                            <div class="chart-column">

                                <span class="chart-number">
                                    {{ $section->students_count }}
                                </span>


                                <div class="chart-track">

                                    <div
                                        class="chart-bar"
                                        style="
                                            height:
                                            {{ max($barHeight, 3) }}%;
                                        "
                                    ></div>

                                </div>


                                <div class="chart-label">

                                    {{ $section->schoolClass?->name ?? '' }}

                                    <br>

                                    {{ $section->name }}

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-chart">
                        لا توجد بيانات شعب لعرضها حالياً.
                    </div>

                @endif

            </section>



            <section class="panel">

                <div class="panel-header">

                    <h3>
                        ملخص النظام
                    </h3>

                    <span>
                        Sprint 1
                    </span>

                </div>


                <div class="summary-list">

                    <div class="summary-row">
                        <span class="summary-label">
                            الطلاب
                        </span>

                        <span class="summary-value">
                            {{ $stats['students'] ?? 0 }}
                        </span>
                    </div>


                    <div class="summary-row">
                        <span class="summary-label">
                            المعلمون
                        </span>

                        <span class="summary-value">
                            {{ $stats['teachers'] ?? 0 }}
                        </span>
                    </div>


                    <div class="summary-row">
                        <span class="summary-label">
                            أولياء الأمور
                        </span>

                        <span class="summary-value">
                            {{ $stats['parents'] ?? 0 }}
                        </span>
                    </div>


                    <div class="summary-row">
                        <span class="summary-label">
                            الفصول والشعب
                        </span>

                        <span class="summary-value">
                            {{ ($stats['classes'] ?? 0) + ($stats['sections'] ?? 0) }}
                        </span>
                    </div>


                    <div class="summary-row">
                        <span class="summary-label">
                            المواد
                        </span>

                        <span class="summary-value">
                            {{ $stats['subjects'] ?? 0 }}
                        </span>
                    </div>


                    <div class="summary-row">
                        <span class="summary-label">
                            الإسنادات
                        </span>

                        <span class="summary-value">
                            {{ $stats['assignments'] ?? 0 }}
                        </span>
                    </div>

                </div>


                <div class="schedule-box">

                    <small>
                        آخر ملف جدول دراسي
                    </small>

                    @if($latestScheduleFile)

                        <strong>
                            {{ $latestScheduleFile->title }}
                        </strong>

                        <p>
                            تم رفع الملف وحفظه في النظام.
                        </p>

                        @if(auth()->user()->hasPermission('رفع الجدول الدراسي'))

                            <a
                                href="{{ route('schedule-files.index') }}"
                                class="schedule-link"
                            >
                                عرض ملفات الجدول
                            </a>

                        @endif

                    @else

                        <strong>
                            لا يوجد ملف حتى الآن
                        </strong>

                        <p>
                            لم يتم رفع جدول دراسي.
                        </p>

                    @endif

                </div>

            </section>

        </div>

    </main>

</div>

</body>

</html>