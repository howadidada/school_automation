<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إدارة أولياء الأمور</title>

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

        /* ==============================
           التخطيط العام
        ============================== */

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ==============================
           الشريط الجانبي
        ============================== */

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
            color: white;
            margin-bottom: 5px;
        }

        .sidebar .brand span {
            display: inline-block;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.8;
        }

        .sidebar .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .sidebar .menu-item {
            display: block;
            padding: 12px 15px;
            border-radius: 9px;
            text-decoration: none;
            color: #cbd5e1;
            font-size: 14px;
            transition: 0.2s;
        }

        .sidebar .menu-item:hover {
            background: #1f2937;
            color: white;
        }

        .sidebar .menu-item.active {
            background: #2563eb;
            color: white;
            font-weight: 600;
        }

        .sidebar .logout-area {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #374151;
        }

        .sidebar .logout-area form {
            width: 100%;
        }

        .sidebar .logout-btn {
            width: 100%;
            padding: 12px;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 9px;
            font-size: 14px;
            cursor: pointer;
        }

        .sidebar .logout-btn:hover {
            background: #b91c1c;
        }

        /* ==============================
           المحتوى الرئيسي
        ============================== */

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

        /* ==============================
           التنبيهات
        ============================== */

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
           بطاقة الجدول
        ============================== */

        .table-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
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
            font-size: 14px;
            color: #6b7280;
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

        .parent-name {
            font-weight: 600;
            color: #111827;
        }

        .parent-phone {
            direction: ltr;
            unicode-bidi: isolate;
            display: inline-block;
        }

        /* ==============================
           الطلاب المرتبطون
        ============================== */

        .students-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 190px;
        }

        .student-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 9px 11px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
        }

        .student-name {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
        }

        .relation-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .relation-father {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .relation-mother {
            background: #fce7f3;
            color: #9d174d;
        }

        .relation-other {
            background: #f3f4f6;
            color: #374151;
        }

        .not-linked {
            color: #9ca3af;
            font-size: 13px;
        }

        /* ==============================
           حالة الحساب
        ============================== */

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
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
           الإجراءات
        ============================== */

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
            border: none;
            cursor: pointer;
        }

        .edit-btn:hover {
            background: #1d4ed8;
        }

        .resend-form {
            display: inline-block;
            margin: 0;
        }

        .resend-btn {
            display: inline-block;
            background: #0f766e;
            color: white;
            border: none;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 13px;
            cursor: pointer;
            white-space: nowrap;
            transition: 0.2s;
        }

        .resend-btn:hover {
            background: #115e59;
        }

        .resend-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .disabled-btn {
            background: #e5e7eb;
            color: #6b7280;
            border: none;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 13px;
            cursor: not-allowed;
        }

        /* ==============================
           عدم وجود بيانات
        ============================== */

        .empty {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .no-results {
            display: none;
            padding: 35px 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        /* ==============================
           الشاشات الصغيرة
        ============================== */

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
                min-height: auto;
            }

            .sidebar .menu {
                flex: initial;
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

            .filters-box {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | حساب الإحصائيات من بيانات أولياء الأمور
    |--------------------------------------------------------------------------
    */

    $totalParents = $parents->count();

    $activeParents = $parents->filter(function ($parent) {
        return (bool) $parent->user?->is_active;
    })->count();

    $pendingParents = $parents->filter(function ($parent) {
        $user = $parent->user;

        return $user
            && !$user->is_active
            && $user->password === null;
    })->count();

    $unlinkedParents = $parents->filter(function ($parent) {
        return $parent->students->isEmpty();
    })->count();
@endphp

<div class="layout">

    {{-- الشريط الجانبي --}}
    @include('partials.sidebar')

    <main class="main">

        {{-- عنوان الصفحة --}}

        <div class="topbar">

            <div>
                <h2>إدارة أولياء الأمور</h2>

                <p>
                    عرض بيانات أولياء الأمور
                    والطلاب المرتبطين بكل حساب
                </p>
            </div>

            <a
                href="{{ route('parents.create') }}"
                class="add-btn"
            >
                + إضافة ولي أمر
            </a>

        </div>

        {{-- رسائل النجاح --}}

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- رسائل الخطأ --}}

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- أخطاء التحقق --}}

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ==============================
             البطاقات الإحصائية
        ============================== --}}

        <div class="stats-grid">

            <div class="stat-card total">
                <div class="stat-label">
                    إجمالي أولياء الأمور
                </div>

                <div class="stat-value">
                    {{ $totalParents }}
                </div>

                <div class="stat-note">
                    جميع أولياء الأمور المسجلين
                </div>
            </div>

            <div class="stat-card active">
                <div class="stat-label">
                    الحسابات النشطة
                </div>

                <div class="stat-value">
                    {{ $activeParents }}
                </div>

                <div class="stat-note">
                    أولياء أمور لديهم حسابات مفعّلة
                </div>
            </div>

            <div class="stat-card pending">
                <div class="stat-label">
                    بانتظار التفعيل
                </div>

                <div class="stat-value">
                    {{ $pendingParents }}
                </div>

                <div class="stat-note">
                    لم يكملوا تفعيل حساباتهم
                </div>
            </div>

            <div class="stat-card unlinked">
                <div class="stat-label">
                    بدون أبناء مرتبطين
                </div>

                <div class="stat-value">
                    {{ $unlinkedParents }}
                </div>

                <div class="stat-note">
                    حسابات لا ترتبط بأي طالب حاليًا
                </div>
            </div>

        </div>

        {{-- ==============================
             البحث والفلاتر
        ============================== --}}

        <div class="filters-box">

            <h3 class="filters-title">
                البحث وتصفية أولياء الأمور
            </h3>

            <div class="filters-grid">

                {{-- البحث --}}

                <div class="filter-group">

                    <label for="parentSearch">
                        البحث عن ولي أمر
                    </label>

                    <input
                        type="search"
                        id="parentSearch"
                        placeholder="الاسم أو البريد الإلكتروني أو رقم الهاتف"
                        autocomplete="off"
                    >

                </div>

                {{-- حالة الحساب --}}

                <div class="filter-group">

                    <label for="statusFilter">
                        حالة الحساب
                    </label>

                    <select id="statusFilter">

                        <option value="">
                            جميع الحالات
                        </option>

                        <option value="active">
                            نشط
                        </option>

                        <option value="pending">
                            بانتظار التفعيل
                        </option>

                        <option value="inactive">
                            غير نشط
                        </option>

                    </select>

                </div>

                {{-- حالة الارتباط --}}

                <div class="filter-group">

                    <label for="linkFilter">
                        ارتباط الأبناء
                    </label>

                    <select id="linkFilter">

                        <option value="">
                            جميع أولياء الأمور
                        </option>

                        <option value="linked">
                            لديهم أبناء مرتبطون
                        </option>

                        <option value="unlinked">
                            بدون أبناء مرتبطين
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
                    عرض {{ $totalParents }} من {{ $totalParents }} ولي أمر
                </span>

            </div>

        </div>

        {{-- ==============================
             جدول أولياء الأمور
        ============================== --}}

        <div class="table-box">

            <div class="table-header">

                <h3>قائمة أولياء الأمور</h3>

                <p>
                    عدد أولياء الأمور: {{ $totalParents }}
                </p>

            </div>

            @if($parents->isEmpty())

                <div class="empty">
                    لا يوجد أولياء أمور مسجلون حاليًا.
                </div>

            @else

                <div class="table-responsive">

                    <table>

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم ولي الأمر</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهاتف</th>
                                <th>الطلاب المرتبطون وصلة القرابة</th>
                                <th>حالة الحساب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody id="parentsTableBody">

                            @foreach($parents as $index => $parent)

                                @php
                                    $parentUser = $parent->user;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | حالة حساب ولي الأمر
                                    |--------------------------------------------------------------------------
                                    */

                                    $isPendingActivation =
                                        $parentUser
                                        && !$parentUser->is_active
                                        && $parentUser->password === null;

                                    $accountStatus = $parentUser?->is_active
                                        ? 'active'
                                        : ($isPendingActivation ? 'pending' : 'inactive');

                                    /*
                                    |--------------------------------------------------------------------------
                                    | حالة ارتباط الأبناء
                                    |--------------------------------------------------------------------------
                                    */

                                    $hasStudents = $parent->students->isNotEmpty();

                                    $linkStatus = $hasStudents
                                        ? 'linked'
                                        : 'unlinked';
                                @endphp

                                <tr
                                    class="parent-row"
                                    data-search="{{ $parentUser?->name ?? '' }} {{ $parentUser?->email ?? '' }} {{ $parentUser?->phone ?? '' }}"
                                    data-status="{{ $accountStatus }}"
                                    data-link="{{ $linkStatus }}"
                                >

                                    {{-- الرقم التسلسلي --}}

                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- اسم ولي الأمر --}}

                                    <td class="parent-name">
                                        {{ $parentUser?->name ?? '-' }}
                                    </td>

                                    {{-- البريد الإلكتروني --}}

                                    <td>
                                        {{ $parentUser?->email ?? '-' }}
                                    </td>

                                    {{-- رقم الهاتف --}}

                                    <td>
                                        <span class="parent-phone">
                                            {{ $parentUser?->phone ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- الأبناء وصلة القرابة --}}

                                    <td>

                                        @if($hasStudents)

                                            <div class="students-list">

                                                @foreach($parent->students as $student)

                                                    @php
                                                        $relation = $student->pivot?->relation;

                                                        $relationLabel = match ($relation) {
                                                            'father' => 'أب',
                                                            'mother' => 'أم',
                                                            default => $relation ?: 'غير محددة',
                                                        };

                                                        $relationClass = match ($relation) {
                                                            'father' => 'relation-father',
                                                            'mother' => 'relation-mother',
                                                            default => 'relation-other',
                                                        };
                                                    @endphp

                                                    <div class="student-item">

                                                        <span class="student-name">
                                                            {{ $student->user?->name ?? '-' }}
                                                        </span>

                                                        <span
                                                            class="relation-badge {{ $relationClass }}"
                                                        >
                                                            {{ $relationLabel }}
                                                        </span>

                                                    </div>

                                                @endforeach

                                            </div>

                                        @else

                                            <span class="not-linked">
                                                لا يوجد أبناء مرتبطون
                                            </span>

                                        @endif

                                    </td>

                                    {{-- حالة الحساب --}}

                                    <td>

                                        @if($parentUser?->is_active)

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

                                    {{-- الإجراءات --}}

                                    <td>

                                        <div class="actions">

                                            {{-- تعديل ولي الأمر --}}

                                            <a
                                                href="{{ route('parents.edit', $parent) }}"
                                                class="edit-btn"
                                            >
                                                تعديل
                                            </a>

                                            {{-- إعادة إرسال التفعيل --}}

                                            @if($isPendingActivation)

                                                <form
                                                    action="{{ route('parents.resend-activation', $parent) }}"
                                                    method="POST"
                                                    class="resend-form"
                                                    onsubmit="return confirmResendActivation(this);"
                                                >
                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="resend-btn"
                                                        title="إعادة إرسال رابط تفعيل حساب ولي الأمر"
                                                    >
                                                        إعادة إرسال التفعيل
                                                    </button>
                                                </form>

                                            @endif

                                            {{-- الحذف متوقف --}}

                                            <button
                                                type="button"
                                                class="disabled-btn"
                                                disabled
                                                title="الحذف متوقف مؤقتًا لحماية ارتباطات الطلاب"
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

                {{-- لا توجد نتائج مطابقة للبحث --}}

                <div
                    id="noSearchResults"
                    class="no-results"
                >
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
            'هل تريدين إعادة إرسال رابط التفعيل إلى البريد الإلكتروني لولي الأمر؟'
        );

        if (!confirmed) {
            return false;
        }

        const button = form.querySelector(
            'button[type="submit"]'
        );

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

        const searchInput = document.getElementById(
            'parentSearch'
        );

        const statusFilter = document.getElementById(
            'statusFilter'
        );

        const linkFilter = document.getElementById(
            'linkFilter'
        );

        const resetButton = document.getElementById(
            'resetFilters'
        );

        const resultsLabel = document.getElementById(
            'filterResults'
        );

        const noResults = document.getElementById(
            'noSearchResults'
        );

        const rows = Array.from(
            document.querySelectorAll('.parent-row')
        );

        /*
        |--------------------------------------------------------------------------
        | توحيد النص لتحسين البحث العربي
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

        function filterParents() {

            const searchValue = normalizeText(
                searchInput.value
            );

            const selectedStatus = statusFilter.value;

            const selectedLink = linkFilter.value;

            let visibleCount = 0;

            rows.forEach(function (row) {

                const rowSearch = normalizeText(
                    row.dataset.search
                );

                const rowStatus = row.dataset.status || '';

                const rowLink = row.dataset.link || '';

                /*
                | البحث بالاسم أو البريد أو الهاتف
                */

                const matchesSearch =
                    searchValue === '' ||
                    rowSearch.includes(searchValue);

                /*
                | فلتر حالة الحساب
                */

                const matchesStatus =
                    selectedStatus === '' ||
                    rowStatus === selectedStatus;

                /*
                | فلتر ارتباط الأبناء
                */

                const matchesLink =
                    selectedLink === '' ||
                    rowLink === selectedLink;

                const shouldShow =
                    matchesSearch &&
                    matchesStatus &&
                    matchesLink;

                /*
                | إظهار الصف أو إخفاؤه
                */

                row.hidden = !shouldShow;

                if (shouldShow) {
                    visibleCount++;
                }
            });

            /*
            |--------------------------------------------------------------------------
            | تحديث عداد النتائج
            |--------------------------------------------------------------------------
            */

            resultsLabel.textContent =
                'عرض ' + visibleCount +
                ' من ' + rows.length +
                ' ولي أمر';

            /*
            |--------------------------------------------------------------------------
            | إظهار رسالة عدم وجود نتائج
            |--------------------------------------------------------------------------
            */

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

        searchInput.addEventListener(
            'input',
            filterParents
        );

        statusFilter.addEventListener(
            'change',
            filterParents
        );

        linkFilter.addEventListener(
            'change',
            filterParents
        );

        /*
        |--------------------------------------------------------------------------
        | إعادة ضبط البحث
        |--------------------------------------------------------------------------
        */

        resetButton.addEventListener('click', function () {

            searchInput.value = '';

            statusFilter.value = '';

            linkFilter.value = '';

            filterParents();

            searchInput.focus();
        });

        /*
        |--------------------------------------------------------------------------
        | تشغيل الفلاتر عند تحميل الصفحة
        |--------------------------------------------------------------------------
        */

        filterParents();
    });
</script>

</body>
</html>