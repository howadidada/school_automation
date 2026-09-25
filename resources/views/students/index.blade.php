<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | إدارة الطلاب</title>

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
                <h2>إدارة الطلاب</h2>
                <p>عرض وإضافة وتعديل وحذف بيانات الطلاب</p>
            </div>

            <a href="{{ route('students.create') }}" class="add-btn">
                + إضافة طالب
            </a>

        </div>

        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-box">

            <table>

                <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الطالب</th>
                    <th>الرقم الطلابي</th>
                    <th>البريد الإلكتروني</th>
                    <th>الفصل</th>
                    <th>الشعبة</th>
                    <th>الجنس</th>
                    <th>الإجراءات</th>
                </tr>
                </thead>

                <tbody>

                @forelse($students as $student)

                    <tr>

                        <td>{{ $student->id }}</td>

                        <td>{{ $student->user?->name ?? '-' }}</td>

                        <td>{{ $student->student_number }}</td>

                        <td>{{ $student->user?->email ?? '-' }}</td>

                        <td>
                            {{ $student->section?->schoolClass?->name ?? '-' }}
                        </td>

                        <td>
                            {{ $student->section?->name ?? '-' }}
                        </td>

                        <td>
                            @if($student->gender === 'male')
                                ذكر
                            @elseif($student->gender === 'female')
                                أنثى
                            @else
                                -
                            @endif
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('students.edit', $student) }}"
                                    class="edit-btn"
                                >
                                    تعديل
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('students.destroy', $student) }}"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟ سيتم حذف حسابه أيضًا.');"
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
                        <td colspan="8" class="empty">
                            لا يوجد طلاب حاليًا.
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