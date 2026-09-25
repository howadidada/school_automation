<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | المواد الدراسية</title>

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
                <h2>إدارة المواد الدراسية</h2>
                <p>إضافة وتعديل وحذف المواد الدراسية وربطها بالمعلمين</p>
            </div>

            <a href="{{ route('subjects.create') }}" class="add-btn">
                + إضافة مادة
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
                    <th>اسم المادة</th>
                    <th>الوصف</th>
                    <th>عدد المعلمين</th>
                    <th>الإجراءات</th>
                </tr>
                </thead>

                <tbody>

                @forelse($subjects as $subject)

                    <tr>

                        <td>{{ $subject->id }}</td>

                        <td>{{ $subject->name }}</td>

                        <td>{{ $subject->description ?? '-' }}</td>

                        <td>{{ $subject->teachers_count }}</td>

                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('subjects.edit', $subject) }}"
                                    class="edit-btn"
                                >
                                    تعديل
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('subjects.destroy', $subject) }}"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="delete-btn">
                                        حذف
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="empty">
                            لا توجد مواد دراسية حاليًا.
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