<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إدارة المستخدمين</title>

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
            gap: 15px;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 5px;
        }

        .topbar p {
            color: #6b7280;
        }

        .add-btn {
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
            white-space: nowrap;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ========================================
           بطاقات إحصائيات المستخدمين
        ======================================== */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            border-right: 4px solid #111827;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(0,0,0,0.08);
        }

        .stat-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 14px;
        }

        .stat-card .stat-number {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
        }

        .stat-total {
            border-right-color: #6366f1;
        }

        .stat-active {
            border-right-color: #22c55e;
        }

        .stat-inactive {
            border-right-color: #ef4444;
        }

        .stat-pending {
            border-right-color: #f59e0b;
        }

        /* ========================================
           البحث والتصفية
        ======================================== */

        .filter-box {
            background: white;
            padding: 22px;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            margin-bottom: 22px;
        }

        .filter-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto auto;
            gap: 12px;
            align-items: end;
        }

        .filter-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 0;
        }

        .filter-field label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .filter-field input,
        .filter-field select {
            width: 100%;
            height: 42px;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            color: #1f2937;
            font-size: 14px;
        }

        .filter-field input:focus,
        .filter-field select:focus {
            outline: none;
            border-color: #6366f1;
        }

        .search-btn,
        .reset-btn {
            height: 42px;
            padding: 0 17px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            white-space: nowrap;
        }

        .search-btn {
            background: #111827;
            color: white;
            border: none;
        }

        .reset-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            text-decoration: none;
        }

        .results-info {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 12px;
        }

        /* ========================================
           جدول المستخدمين
        ======================================== */

        .table-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
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
            white-space: nowrap;
        }

        .role-badge {
            display: inline-block;
            background: #eef2ff;
            color: #3730a3;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* ========================================
           حالات الحساب
        ======================================== */

        .status-active,
        .status-inactive,
        .status-pending {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            white-space: nowrap;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* ========================================
           أزرار الإجراءات
        ======================================== */

        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center;
        }

        .actions form {
            margin: 0;
        }

        .edit-btn,
        .disable-btn,
        .enable-btn,
        .resend-btn {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 7px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .edit-btn {
            background: #e0e7ff;
            color: #3730a3;
        }

        .disable-btn {
            background: #fee2e2;
            color: #991b1b;
        }

        .enable-btn {
            background: #dcfce7;
            color: #166534;
        }

        .resend-btn {
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 600;
        }

        .resend-btn:hover {
            background: #bfdbfe;
        }

        .resend-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .special-user {
            display: inline-block;
            background: #f3f4f6;
            color: #6b7280;
            padding: 7px 10px;
            border-radius: 7px;
            font-size: 12px;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #6b7280;
        }

        .pagination-area {
            margin-top: 20px;
        }

        /* ========================================
           توافق الواجهة مع الشاشات
        ======================================== */

        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-form {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .main {
                padding: 16px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar h2 {
                font-size: 22px;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            th,
            td {
                padding: 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <!-- عنوان الصفحة -->

        <div class="topbar">

            <div>
                <h2>إدارة المستخدمين</h2>

                <p>
                    إدارة حسابات مدير النظام والمشرف ومدير المدرسة والوكيل
                </p>
            </div>

            <a href="{{ route('users.create') }}" class="add-btn">
                + إضافة مستخدم
            </a>

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

        @if($errors->any())
            <div class="alert error">
                <ul style="padding-right: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ========================================
             بطاقات إحصائيات المستخدمين
        ======================================== --}}

        <div class="stats-grid">

            <!-- إجمالي المستخدمين -->

            <div class="stat-card stat-total">
                <h3>👥 إجمالي المستخدمين</h3>

                <div class="stat-number">
                    {{ $totalUsers }}
                </div>
            </div>

            <!-- الحسابات النشطة -->

            <div class="stat-card stat-active">
                <h3>🟢 الحسابات النشطة</h3>

                <div class="stat-number">
                    {{ $activeUsers }}
                </div>
            </div>

            <!-- الحسابات المعطلة -->

            <div class="stat-card stat-inactive">
                <h3>🔴 الحسابات المعطّلة</h3>

                <div class="stat-number">
                    {{ $inactiveUsers }}
                </div>
            </div>

            <!-- الحسابات التي تنتظر التفعيل -->

            <div class="stat-card stat-pending">
                <h3>🟠 بانتظار التفعيل</h3>

                <div class="stat-number">
                    {{ $pendingUsers }}
                </div>
            </div>

        </div>

        {{-- ========================================
             شريط البحث والتصفية
        ======================================== --}}

        <section class="filter-box">

            <h3 class="filter-title">
                البحث والتصفية
            </h3>

            <form
                method="GET"
                action="{{ route('users.index') }}"
                class="filter-form"
            >

                <!-- البحث بالاسم أو البريد -->

                <div class="filter-field">
                    <label for="search">
                        الاسم أو البريد الإلكتروني
                    </label>

                    <input
                        type="search"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="ابحث عن مستخدم..."
                        maxlength="255"
                    >
                </div>

                <!-- التصفية حسب الدور -->

                <div class="filter-field">
                    <label for="role_id">
                        الدور الوظيفي
                    </label>

                    <select id="role_id" name="role_id">

                        <option value="">
                            جميع الأدوار
                        </option>

                        @foreach($roles as $role)

                            <option
                                value="{{ $role->id }}"
                                @selected(
                                    (string) request('role_id') ===
                                    (string) $role->id
                                )
                            >
                                {{ $role->name }}
                            </option>

                        @endforeach

                    </select>
                </div>

                <!-- التصفية حسب الحالة -->

                <div class="filter-field">
                    <label for="status">
                        حالة الحساب
                    </label>

                    <select id="status" name="status">

                        <option value="">
                            جميع الحالات
                        </option>

                        <option
                            value="active"
                            @selected(request('status') === 'active')
                        >
                            نشط
                        </option>

                        <option
                            value="inactive"
                            @selected(request('status') === 'inactive')
                        >
                            معطّل
                        </option>

                        <option
                            value="pending"
                            @selected(request('status') === 'pending')
                        >
                            بانتظار التفعيل
                        </option>

                    </select>
                </div>

                <!-- أزرار البحث -->

                <button type="submit" class="search-btn">
                    بحث
                </button>

                <a
                    href="{{ route('users.index') }}"
                    class="reset-btn"
                >
                    إعادة ضبط
                </a>

            </form>

        </section>

        <!-- عدد نتائج البحث -->

        <div class="results-info">
            عدد النتائج المطابقة: {{ $users->total() }}
        </div>

        {{-- ========================================
             جدول المستخدمين
        ======================================== --}}

        <div class="table-box">

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الدور</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>

                @php
                    $specialRoles = [
                        'teacher',
                        'student',
                        'parent',
                    ];

                    $allowedRoles = [
                        'admin',
                        'supervisor',
                        'principal',
                        'vice_principal',
                    ];
                @endphp

                @forelse($users as $user)

                    @php
                        $isPendingActivation =
                            !$user->is_active &&
                            $user->password === null;

                        $isSpecialRole =
                            $user->role &&
                            in_array(
                                $user->role->name,
                                $specialRoles,
                                true
                            );

                        $canResendActivation =
                            $isPendingActivation &&
                            $user->role &&
                            in_array(
                                $user->role->name,
                                $allowedRoles,
                                true
                            ) &&
                            !empty($user->email);
                    @endphp

                    <tr>

                        <td>
                            {{ $user->id }}
                        </td>

                        <td>
                            {{ $user->name }}
                        </td>

                        <td>
                            {{ $user->email }}
                        </td>

                        <td>
                            {{ $user->phone ?? '-' }}
                        </td>

                        <td>
                            <span class="role-badge">
                                {{ $user->role?->name ?? '-' }}
                            </span>
                        </td>

                        <td>

                            @if($user->is_active)

                                <span class="status-active">
                                    نشط
                                </span>

                            @elseif($isPendingActivation)

                                <span class="status-pending">
                                    بانتظار التفعيل
                                </span>

                            @else

                                <span class="status-inactive">
                                    معطّل
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="actions">

                                <!-- تعديل المستخدم -->

                                @if($isSpecialRole)

                                    <span class="special-user">
                                        إدارة من الصفحة المخصصة
                                    </span>

                                @else

                                    <a
                                        href="{{ route('users.edit', $user) }}"
                                        class="edit-btn"
                                    >
                                        تعديل
                                    </a>

                                @endif

                                <!-- إعادة إرسال رابط التفعيل -->

                                @if($canResendActivation)

                                    <form
                                        method="POST"
                                        action="{{ route('users.resend-activation', $user) }}"
                                        class="resend-activation-form"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="resend-btn"
                                        >
                                            إعادة إرسال رابط التفعيل
                                        </button>

                                    </form>

                                @endif

                                <!-- تفعيل أو تعطيل الحساب -->

                                @if(auth()->id() !== $user->id)

                                    @if($user->is_active)

                                        <form
                                            method="POST"
                                            action="{{ route('users.toggle-status', $user) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="disable-btn"
                                                onclick="return confirm('هل أنت متأكد من تعطيل هذا المستخدم؟');"
                                            >
                                                تعطيل
                                            </button>

                                        </form>

                                    @elseif(!$isPendingActivation)

                                        <form
                                            method="POST"
                                            action="{{ route('users.toggle-status', $user) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="enable-btn"
                                                onclick="return confirm('هل أنت متأكد من تفعيل هذا المستخدم؟');"
                                            >
                                                تفعيل
                                            </button>

                                        </form>

                                    @endif

                                @else

                                    <span class="special-user">
                                        الحساب الحالي
                                    </span>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="empty">
                            لا توجد نتائج مطابقة للبحث أو التصفية.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <!-- ترقيم الصفحات -->

        @if($users->hasPages())

            <div class="pagination-area">
                {{ $users->links() }}
            </div>

        @endif

    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const forms = document.querySelectorAll(
            '.resend-activation-form'
        );

        forms.forEach(function (form) {

            form.addEventListener('submit', function (event) {

                const confirmed = confirm(
                    'هل تريد إعادة إرسال رابط التفعيل إلى البريد الإلكتروني لهذا المستخدم؟\n\n' +
                    'سيتم إنشاء رابط جديد، ولن يعود الرابط السابق صالحًا.'
                );

                if (!confirmed) {
                    event.preventDefault();
                    return;
                }

                const button = form.querySelector(
                    'button[type="submit"]'
                );

                if (button) {
                    button.disabled = true;
                    button.textContent = 'جارٍ الإرسال...';
                }

            });

        });

    });
</script>

</body>
</html>