<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | إضافة شعبة</title>

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
            max-width: 750px;
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 18px;
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

        .save-btn {
            margin-top: 10px;
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
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="topbar">

            <div>
                <h2>إضافة شعبة</h2>
                <p>إضافة شعبة جديدة وربطها بالفصل الدراسي المناسب</p>
            </div>

            <a href="{{ route('sections.index') }}" class="back-btn">
                العودة للشعب
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

        <div class="form-box">

            <form method="POST" action="{{ route('sections.store') }}">

                @csrf

                <div class="form-group">

                    <label for="school_class_id">
                        الفصل الدراسي
                    </label>

                    <select
                        id="school_class_id"
                        name="school_class_id"
                        required
                    >

                        <option value="">
                            اختر الفصل
                        </option>

                        @foreach($classes as $schoolClass)

                            <option
                                value="{{ $schoolClass->id }}"
                                {{ old('school_class_id') == $schoolClass->id ? 'selected' : '' }}
                            >
                                {{ $schoolClass->name }}
                                @if($schoolClass->grade_level)
                                    - {{ $schoolClass->grade_level }}
                                @endif
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="form-group">

                    <label for="name">
                        اسم الشعبة
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="مثال: أ"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="capacity">
                        سعة الشعبة
                    </label>

                    <input
                        type="number"
                        id="capacity"
                        name="capacity"
                        value="{{ old('capacity') }}"
                        min="1"
                        placeholder="مثال: 30"
                    >

                </div>

                <button type="submit" class="save-btn">
                    حفظ الشعبة
                </button>

            </form>

        </div>

    </main>

</div>

</body>
</html>