<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | الأدوار والصلاحيات</title>

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

        .permission-badge {
            display: inline-block;
            background: #eef2ff;
            color: #3730a3;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            margin: 2px;
        }

        .system-role {
            display: inline-block;
            background: #f3f4f6;
            color: #6b7280;
            padding: 7px 10px;
            border-radius: 7px;
            font-size: 13px;
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

        .delete-btn {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 7px 12px;
            border-radius: 7px;
            cursor: pointer;
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
                <h2>الأدوار والصلاحيات</h2>
                <p>إدارة أدوار المستخدمين وتحديد الصلاحيات المسموحة لكل دور</p>
            </div>

            <a href="{{ route('roles.create') }}" class="add-btn">
                + إضافة دور
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

        <div class="table-box">

            <table>

                <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الدور</th>
                    <th>الصلاحيات</th>
                    <th>عدد المستخدمين</th>
                    <th>الإجراءات</th>
                </tr>
                </thead>

                <tbody>

                @php
                    $protectedRoles = [
                        'admin',
                        'supervisor',
                        'teacher',
                        'student',
                        'parent',
                        'principal',
                        'vice_principal',
                    ];
                @endphp

                @forelse($roles as $role)

                    <tr>

                        <td>{{ $role->id }}</td>

                        <td>{{ $role->name }}</td>

                        <td>
                            @forelse($role->permissions as $permission)

                                <span class="permission-badge">
                                    {{ $permission->name }}
                                </span>

                            @empty
                                -
                            @endforelse
                        </td>

                        <td>
                            {{ $role->users_count }}
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('roles.edit', $role) }}"
                                    class="edit-btn"
                                >
                                    تعديل
                                </a>

                                @if(!in_array($role->name, $protectedRoles, true))

                                    <form
                                        method="POST"
                                        action="{{ route('roles.destroy', $role) }}"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-btn">
                                            حذف
                                        </button>

                                    </form>

                                @else

                                    <span class="system-role">
                                        دور أساسي
                                    </span>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="empty">
                            لا توجد أدوار حاليًا.
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