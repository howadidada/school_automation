<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | رفع الجدول الدراسي</title>

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

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            line-height: 1.8;
            font-size: 14px;
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
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
        }

        .hint {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.7;
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

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
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
                <h2>رفع ملف الجدول الدراسي</h2>
                <p>رفع الجدول الدراسي الجاهز وتحديد الشعبة الخاصة به</p>
            </div>

            <a href="{{ route('schedule-files.index') }}" class="back-btn">
                العودة للجدول الدراسي
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
            يتم رفع ملف الجدول الدراسي الجاهز فقط.
            اختر الفصل والشعبة التي سيتم ربط الجدول بها.
        </div>

        <div class="form-box">

            <form
                method="POST"
                action="{{ route('schedule-files.store') }}"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="form-group">

                    <label for="title">
                        عنوان الجدول
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="مثال: الجدول الدراسي للفصل الأول"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="section_id">
                        الفصل والشعبة
                    </label>

                    <select
                        id="section_id"
                        name="section_id"
                        required
                    >
                        <option value="">
                            اختر الفصل والشعبة
                        </option>

                        @foreach($sections as $section)
                            <option
                                value="{{ $section->id }}"
                                {{ old('section_id') == $section->id ? 'selected' : '' }}
                            >
                                {{ $section->schoolClass?->name ?? 'فصل غير محدد' }}
                                —
                                شعبة {{ $section->name }}
                            </option>
                        @endforeach

                    </select>

                    <span class="hint">
                        اختر الشعبة التي سيظهر لها هذا الجدول الدراسي.
                    </span>

                </div>

                <div class="form-group">

                    <label for="file">
                        ملف الجدول
                    </label>

                    <input
                        type="file"
                        id="file"
                        name="file"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                        required
                    >

                    <span class="hint">
                        الملفات المسموحة:
                        PDF، JPG، PNG، Word، Excel.
                        الحد الأقصى لحجم الملف 10 ميجابايت.
                    </span>

                </div>

                <button type="submit" class="save-btn">
                    رفع الملف
                </button>

            </form>

        </div>

    </main>

</div>

</body>
</html>