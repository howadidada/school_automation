<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | إضافة طالب</title>

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

        .back-btn {
            background: #e5e7eb;
            color: #1f2937;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
        }

        .form-box {
            max-width: 900px;
            background: white;
            padding: 28px;
            border-radius: 15px;
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

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
        }

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .save-btn {
            margin-top: 22px;
            background: #111827;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .errors ul {
            padding-right: 20px;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="topbar">

            <div>
                <h2>إضافة طالب</h2>
                <p>إضافة بيانات الطالب وربطه بالشعبة الدراسية المناسبة</p>
            </div>

            <a href="{{ route('students.index') }}" class="back-btn">
                العودة للطلاب
            </a>

        </div>

        @if($errors->any())
            <div class="errors">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-box">
            الرقم الطلابي يتم إنشاؤه تلقائيًا عند حفظ الطالب.
        </div>

        <div class="form-box">

            <form method="POST" action="{{ route('students.store') }}">

                @csrf

                <div class="form-grid">

                    <div class="form-group">
                        <label for="name">اسم الطالب</label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="section_id">الشعبة</label>

                        <select
                            id="section_id"
                            name="section_id"
                            required
                        >
                            <option value="">اختر الشعبة</option>

                            @foreach($sections as $section)

                                <option
                                    value="{{ $section->id }}"
                                    {{ old('section_id') == $section->id ? 'selected' : '' }}
                                >
                                    {{ $section->schoolClass?->name ?? '-' }}
                                    -
                                    {{ $section->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gender">الجنس</label>

                        <select
                            id="gender"
                            name="gender"
                        >
                            <option value="">اختر الجنس</option>

                            <option
                                value="male"
                                {{ old('gender') === 'male' ? 'selected' : '' }}
                            >
                                ذكر
                            </option>

                            <option
                                value="female"
                                {{ old('gender') === 'female' ? 'selected' : '' }}
                            >
                                أنثى
                            </option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">تاريخ الميلاد</label>

                        <input
                            type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            value="{{ old('date_of_birth') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة المرور</label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">تأكيد كلمة المرور</label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                        >
                    </div>

                </div>

                <button type="submit" class="save-btn">
                    حفظ الطالب
                </button>

            </form>

        </div>

    </main>

</div>

</body>
</html>