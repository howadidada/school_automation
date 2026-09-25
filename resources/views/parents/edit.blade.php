<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | تعديل ولي الأمر</title>

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

        input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }

        input:focus {
            border-color: #4f46e5;
        }

        .students-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 15px;
            background: #f9fafb;
            max-height: 280px;
            overflow-y: auto;
        }

        .student-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .student-item:last-child {
            border-bottom: none;
        }

        .student-item input {
            width: auto;
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

        .hint {
            color: #6b7280;
            font-size: 12px;
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
                <h2>تعديل ولي الأمر</h2>
                <p>تعديل بيانات ولي الأمر والطلاب المرتبطين به</p>
            </div>

            <a href="{{ route('parents.index') }}" class="back-btn">
                العودة لأولياء الأمور
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

            <form method="POST" action="{{ route('parents.update', $parent) }}">

                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="form-group">
                        <label for="name">اسم ولي الأمر</label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $parent->user->name) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $parent->user->email) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $parent->user->phone) }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="relation">صلة القرابة</label>

                        <input
                            type="text"
                            id="relation"
                            name="relation"
                            value="{{ old('relation', optional(optional($parent->students->first())->pivot)->relation) }}"
                            placeholder="مثال: أب، أم، أخ"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة المرور الجديدة</label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                        >

                        <span class="hint">
                            اتركها فارغة إذا لم ترغب في تغيير كلمة المرور.
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                        >
                    </div>

                    <div class="form-group full">

                        <label>الطلاب المرتبطون بولي الأمر</label>

                        <span class="hint">
                            يمكن اختيار أكثر من طالب.
                        </span>

                        <div class="students-box">

                            @forelse($students as $student)

                                <label class="student-item">

                                    <input
                                        type="checkbox"
                                        name="students[]"
                                        value="{{ $student->id }}"
                                        {{
                                            in_array(
                                                $student->id,
                                                old(
                                                    'students',
                                                    $parent->students->pluck('id')->toArray()
                                                )
                                            )
                                            ? 'checked'
                                            : ''
                                        }}
                                    >

                                    <span>
                                        {{ $student->user?->name ?? '-' }}
                                        -
                                        {{ $student->student_number }}
                                    </span>

                                </label>

                            @empty

                                <p>لا يوجد طلاب متاحون حاليًا.</p>

                            @endforelse

                        </div>

                    </div>

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