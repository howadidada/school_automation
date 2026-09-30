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

        /* =========================================================
           توزيع الصفحة
        ========================================================= */

        .layout {
            display: flex;
            flex-direction: row;
            width: 100%;
            min-height: 100vh;
            background: #f5f7fb;
        }

        /* =========================================================
           القائمة الجانبية
        ========================================================= */

        .sidebar {
            width: 275px;
            min-width: 275px;
            height: calc(100vh - 24px);

            background: #111827;
            color: white;

            margin: 12px;
            padding: 28px 20px;

            border-radius: 12px;

            display: flex;
            flex-direction: column;

            flex-shrink: 0;

            position: sticky;
            top: 12px;
        }

        .brand {
            margin-bottom: 35px;
            padding: 0 10px;
        }

        .brand h1 {
            font-size: 30px;
            color: white;
            margin-bottom: 5px;
        }

        .brand span {
            display: block;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.7;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            display: block;

            text-decoration: none;

            color: #e5e7eb;

            padding: 13px 14px;

            border-radius: 9px;

            font-size: 15px;

            transition: 0.2s;
        }

        .menu-item:hover {
            background: #1f2937;
            color: white;
        }

        .menu-item.active {
            background: #1f2937;
            color: white;
        }

        .logout-area {
            margin-top: auto;
            padding-top: 30px;
        }

        .logout-btn {
            width: 100%;

            padding: 12px;

            border: none;
            border-radius: 8px;

            background: #ff4747;
            color: white;

            font-size: 14px;

            cursor: pointer;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        /* =========================================================
           المحتوى الرئيسي
        ========================================================= */

        .main {
            flex: 1;
            min-width: 0;
            padding: 30px 30px 30px 18px;
        }

        /* =========================================================
           أعلى الصفحة
        ========================================================= */

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 28px;
            margin-bottom: 6px;
            color: #111827;
        }

        .topbar p {
            color: #6b7280;
            font-size: 14px;
        }

        /* =========================================================
           زر إضافة طالب
        ========================================================= */

        .add-btn {
            display: inline-block;
            text-decoration: none;

            background: #111827;
            color: white;

            padding: 12px 20px;

            border-radius: 9px;

            font-size: 14px;
            font-weight: 600;

            transition: 0.2s;

            white-space: nowrap;
        }

        .add-btn:hover {
            background: #1f2937;
        }

        /* =========================================================
           التنبيهات
        ========================================================= */

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

        /* =========================================================
           بطاقة الجدول
        ========================================================= */

        .table-box {
            background: white;

            border-radius: 15px;

            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);

            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 20px 22px;

            border-bottom: 1px solid #e5e7eb;
        }

        .table-header h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .table-header span {
            color: #6b7280;
            font-size: 14px;
        }

        /* =========================================================
           الجدول
        ========================================================= */

        .table-responsive {
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

            white-space: nowrap;
        }

        th {
            background: #f9fafb;

            color: #374151;

            font-size: 14px;
        }

        td {
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .student-name {
            font-weight: 600;
            color: #111827;
        }

        .student-number {
            background: #eef2ff;
            color: #3730a3;

            padding: 5px 10px;

            border-radius: 6px;

            font-weight: 600;

            display: inline-block;
        }

        .section-badge {
            background: #f3f4f6;

            color: #374151;

            padding: 6px 10px;

            border-radius: 7px;

            display: inline-block;
        }

        /* =========================================================
           العمليات
        ========================================================= */

        .actions {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .edit-btn {
            display: inline-block;

            text-decoration: none;

            background: #2563eb;
            color: white;

            padding: 7px 12px;

            border-radius: 7px;

            font-size: 13px;

            border: none;

            cursor: pointer;
        }

        .edit-btn:hover {
            background: #1d4ed8;
        }

        .delete-btn {
            background: #ef4444;

            color: white;

            padding: 7px 12px;

            border-radius: 7px;

            border: none;

            font-size: 13px;

            cursor: pointer;
        }

        .delete-btn:hover {
            background: #dc2626;
        }

        /* =========================================================
           حالة عدم وجود طلاب
        ========================================================= */

        .empty {
            text-align: center;

            padding: 45px 20px;

            color: #6b7280;
        }

        .empty h3 {
            color: #374151;

            margin-bottom: 8px;
        }

        /* =========================================================
           شاشات صغيرة
        ========================================================= */

        @media (max-width: 1000px) {

            .sidebar {
                width: 230px;
                min-width: 230px;
            }

            .main {
                padding: 20px 20px 20px 10px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .add-btn {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 700px) {

            .layout {
                display: block;
            }

            .sidebar {
                width: calc(100% - 24px);
                min-width: 0;
                height: auto;

                position: relative;
                top: 0;
            }

            .main {
                padding: 20px 12px;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    {{-- =========================================================
         القائمة الجانبية
    ========================================================= --}}
    @include('partials.sidebar')


    {{-- =========================================================
         المحتوى الرئيسي
    ========================================================= --}}

    <main class="main">

        {{-- أعلى الصفحة --}}
        <div class="topbar">

            <div>

                <h2>
                    إدارة الطلاب
                </h2>

                <p>
                    عرض وإدارة بيانات الطلاب المسجلين في النظام
                </p>

            </div>


            <a
                href="{{ route('students.create') }}"
                class="add-btn"
            >
                + إضافة طالب جديد
            </a>

        </div>


        {{-- =========================================================
             رسالة النجاح
        ========================================================= --}}

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif


        {{-- =========================================================
             رسائل الأخطاء
        ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
             جدول الطلاب
        ========================================================= --}}

        <div class="table-box">


            {{-- رأس الجدول --}}
            <div class="table-header">

                <div>

                    <h3>
                        قائمة الطلاب
                    </h3>

                    <span>

                        عدد الطلاب:

                        {{ $students->count() }}

                    </span>

                </div>

            </div>


            {{-- إذا لم يوجد طلاب --}}
            @if($students->isEmpty())

                <div class="empty">

                    <h3>
                        لا يوجد طلاب
                    </h3>

                    <p>
                        لم تتم إضافة أي طالب حتى الآن.
                    </p>

                </div>


            @else


                {{-- جدول الطلاب --}}
                <div class="table-responsive">

                    <table>

                        <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                اسم الطالب
                            </th>

                            <th>
                                الرقم الطلابي
                            </th>

                            <th>
                                البريد الإلكتروني
                            </th>

                            <th>
                                رقم الهاتف
                            </th>

                            <th>
                                الصف والشعبة
                            </th>

                            <th>
                                تاريخ الميلاد
                            </th>

                            <th>
                                الجنس
                            </th>

                            <th>
                                الإجراءات
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($students as $index => $student)

                            <tr>


                                {{-- رقم الصف --}}
                                <td>

                                    {{ $index + 1 }}

                                </td>


                                {{-- اسم الطالب --}}
                                <td class="student-name">

                                    {{ $student->user->name ?? '-' }}

                                </td>


                                {{-- الرقم الطلابي --}}
                                <td>

                                    <span class="student-number">

                                        {{ $student->student_number ?? '-' }}

                                    </span>

                                </td>


                                {{-- البريد الإلكتروني --}}
                                <td>

                                    {{ $student->user->email ?? '-' }}

                                </td>


                                {{-- الهاتف --}}
                                <td>

                                    {{ $student->user->phone ?? '-' }}

                                </td>


                                {{-- الصف والشعبة --}}
                                <td>

                                    <span class="section-badge">

                                        {{ $student->section->schoolClass->name ?? 'بدون صف' }}

                                        -

                                        {{ $student->section->name ?? 'بدون شعبة' }}

                                    </span>

                                </td>


                                {{-- تاريخ الميلاد --}}
                                <td>

                                    {{ $student->date_of_birth ?? '-' }}

                                </td>


                                {{-- الجنس --}}
                                <td>

                                    @if($student->gender === 'male')

                                        ذكر

                                    @elseif($student->gender === 'female')

                                        أنثى

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- الإجراءات --}}
                                <td>

                                    <div class="actions">


                                        {{-- تعديل --}}
                                        <a
                                            href="{{ route('students.edit', $student) }}"
                                            class="edit-btn"
                                        >
                                            تعديل
                                        </a>


                                        {{-- حذف --}}
                                        <form
                                            action="{{ route('students.destroy', $student) }}"
                                            method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟');"
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

            @endif

        </div>

    </main>

</div>

</body>

</html>