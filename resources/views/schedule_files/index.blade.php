<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | الجدول الدراسي</title>

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
            line-height: 1.7;
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

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            line-height: 1.8;
            font-size: 14px;
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
            align-items: center;
            flex-wrap: wrap;
        }

        .view-btn {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 7px;
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
                <h2>الجدول الدراسي</h2>
                <p>
                    رفع وعرض وتعديل ملفات الجدول الدراسي الجاهزة
                </p>
            </div>

            <a
                href="{{ route('schedule-files.create') }}"
                class="add-btn"
            >
                + رفع ملف جدول
            </a>

        </div>

        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        <div class="info-box">
            هذه الصفحة مخصصة لرفع ملف الجدول الدراسي الجاهز فقط،
            ويمكن تحديد الفصل والشعبة الخاصة بالجدول وتعديل الملف أو استبداله.
        </div>

        <div class="table-box">

            <table>

                <thead>
                <tr>
                    <th>#</th>
                    <th>عنوان الملف</th>
                    <th>الفصل</th>
                    <th>الشعبة</th>
                    <th>تم الرفع بواسطة</th>
                    <th>تاريخ الرفع</th>
                    <th>الإجراءات</th>
                </tr>
                </thead>

                <tbody>

                @forelse($scheduleFiles as $file)

                    <tr>

                        <td>
                            {{ $file->id }}
                        </td>

                        <td>
                            {{ $file->title }}
                        </td>

                        <td>
                            {{ $file->section?->schoolClass?->name ?? '-' }}
                        </td>

                        <td>
                            {{ $file->section?->name ?? '-' }}
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
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الملف؟');"
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
                        <td
                            colspan="7"
                            class="empty"
                        >
                            لا توجد ملفات للجدول الدراسي حاليًا.
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