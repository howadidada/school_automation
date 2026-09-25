<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تعديل بيانات الطالب</title>

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

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h2 {
            font-size: 26px;
            margin-bottom: 6px;
        }

        .page-header p {
            color: #6b7280;
        }

        .form-box {
            max-width: 850px;
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            font-size: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #111827;
        }

        .readonly-input {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        .info {
            background: #eff6ff;
            color: #1e40af;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .errors ul {
            padding-right: 18px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .save-btn {
            background: #111827;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 8px;
            cursor: pointer;
        }

        .cancel-btn {
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            padding: 11px 20px;
            border-radius: 8px;
        }

        @media (max-width: 800px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                width: 220px;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="page-header">
            <h2>تعديل بيانات الطالب</h2>
            <p>تعديل بيانات الطالب وحسابه والشعبة المرتبط بها</p>
        </div>

        <div class="form-box">

            <div class="info">
                رقم الطالب يتم إنشاؤه تلقائيًا بواسطة النظام ولا يمكن تعديله.
            </div>

            @if ($errors->any())
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('students.update', $student) }}"
            >

                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="form-group">
                        <label>رقم الطالب</label>

                        <input
                            type="text"
                            value="{{ $student->student_number }}"
                            class="readonly-input"
                            readonly
                        >
                    </div>

                    <div class="form-group">
                        <label for="name">اسم الطالب</label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $student->user?->name) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $student->user?->email) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $student->user?->phone) }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="section_id">الشعبة</label>

                        <select
                            id="section_id"
                            name="section_id"
                            required
                        >

                            <option value="">
                                اختر الشعبة
                            </option>

                            @foreach($sections as $section)

                                <option
                                    value="{{ $section->id }}"
                                    {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}
                                >
                                    {{ $section->schoolClass?->name ?? '-' }}
                                    -
                                    {{ $section->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">
                            تأكيد كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                        >
                    </div>

                </div>

                <div class="buttons">

                    <button type="submit" class="save-btn">
                        حفظ التعديلات
                    </button>

                    <a
                        href="{{ route('students.index') }}"
                        class="cancel-btn"
                    >
                        إلغاء
                    </a>

                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>