<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | تعديل إسناد المعلم</title>

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

        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            background: white;
        }

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

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="topbar">

            <div>
                <h2>تعديل إسناد المعلم</h2>
                <p>تعديل المعلم أو الشعبة المرتبطة بالإسناد</p>
            </div>

            <a href="{{ route('teacher-assignments.index') }}" class="back-btn">
                العودة للإسنادات
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

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="info-box">
            كل معلم مرتبط بمادة دراسية واحدة، ويمكن إسناده إلى أكثر من شعبة.
            لذلك يتم تعديل المعلم أو الشعبة فقط.
        </div>

        <div class="form-box">

            <form
                method="POST"
                action="{{ route('teacher-assignments.update', $teacherAssignment) }}"
            >

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label for="teacher_id">
                        المعلم
                    </label>

                    <select
                        id="teacher_id"
                        name="teacher_id"
                        required
                    >

                        <option value="">
                            اختر المعلم
                        </option>

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{
                                    old(
                                        'teacher_id',
                                        $teacherAssignment->teacher_id
                                    ) == $teacher->id
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                {{ $teacher->user?->name ?? '-' }}
                                -
                                {{ $teacher->subject?->name ?? 'بدون مادة' }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="form-group">

                    <label for="section_id">
                        الشعبة
                    </label>

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
                                {{
                                    old(
                                        'section_id',
                                        $teacherAssignment->section_id
                                    ) == $section->id
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                {{ $section->schoolClass?->name ?? '-' }}
                                -
                                {{ $section->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <button type="submit" class="save-btn">
                    حفظ التعديلات
                </button>

            </form>

        </div>

    </main>

</div>

</body>
</html>