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
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .role-badge {
            display: inline-block;
            background: #eef2ff;
            color: #3730a3;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-active {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-inactive {
            display: inline-block;
            background: #fee2e2;
            color: #991b1b;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

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

        .disable-btn {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 7px 12px;
            border-radius: 7px;
            cursor: pointer;
        }

        .enable-btn {
            background: #dcfce7;
            color: #166534;
            border: none;
            padding: 7px 12px;
            border-radius: 7px;
            cursor: pointer;
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
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

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
                @endphp

                @forelse($users as $user)

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

                            @else

                                <span class="status-inactive">
                                    معطل
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="actions">

                                @if(
                                    $user->role &&
                                    in_array(
                                        $user->role->name,
                                        $specialRoles,
                                        true
                                    )
                                )

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


                                @if(auth()->id() !== $user->id)

                                    <form
                                        method="POST"
                                        action="{{ route('users.toggle-status', $user) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if($user->is_active)

                                            <button
                                                type="submit"
                                                class="disable-btn"
                                                onclick="return confirm('هل أنت متأكد من تعطيل هذا المستخدم؟');"
                                            >
                                                تعطيل
                                            </button>

                                        @else

                                            <button
                                                type="submit"
                                                class="enable-btn"
                                                onclick="return confirm('هل أنت متأكد من تفعيل هذا المستخدم؟');"
                                            >
                                                تفعيل
                                            </button>

                                        @endif

                                    </form>

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
                            لا يوجد مستخدمون حاليًا.
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