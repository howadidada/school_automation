<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تعديل المعلم</title>

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
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
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

        .save-btn:hover {
            background: #1f2937;
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
            line-height: 1.5;
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
                <h2>تعديل المعلم</h2>

                <p>
                    تعديل بيانات المعلم وحساب تسجيل الدخول والمرحلة التعليمية
                </p>
            </div>

            <a
                href="{{ route('teachers.index') }}"
                class="back-btn"
            >
                العودة للمعلمين
            </a>

        </div>

        {{-- ===================================================== --}}
        {{-- أخطاء التحقق --}}
        {{-- ===================================================== --}}

        @if($errors->any())

            <div class="errors">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <div class="form-box">

            <form
                method="POST"
                action="{{ route('teachers.update', $teacher) }}"
            >

                @csrf
                @method('PUT')

                <div class="form-grid">

                    {{-- ================================================= --}}
                    {{-- اسم المعلم --}}
                    {{-- ================================================= --}}

                    <div class="form-group">

                        <label for="name">
                            اسم المعلم
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $teacher->user->name) }}"
                            required
                        >

                    </div>

                    {{-- ================================================= --}}
                    {{-- اسم المستخدم --}}
                    {{-- ================================================= --}}

                    <div class="form-group">

                        <label for="username">
                            اسم المستخدم
                        </label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username', $teacher->user->username) }}"
                            autocomplete="off"
                            required
                        >

                        <span class="hint">
                            يستخدم المعلم هذا الاسم لتسجيل الدخول إلى التطبيق.
                        </span>

                    </div>

                    {{-- ================================================= --}}
                    {{-- البريد الإلكتروني --}}
                    {{-- ================================================= --}}

                    <div class="form-group">

                        <label for="email">
                            البريد الإلكتروني
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $teacher->user->email) }}"
                            required
                        >

                    </div>

                    {{-- ================================================= --}}
                    {{-- رقم الهاتف --}}
                    {{-- ================================================= --}}

                    <div class="form-group">

                        <label for="phone">
                            رقم الهاتف
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $teacher->user->phone) }}"
                        >

                    </div>

                    {{-- ================================================= --}}
                    {{-- المادة الدراسية --}}
                    {{-- ================================================= --}}

                    <div class="form-group">

                        <label for="subject_id">
                            المادة الدراسية
                        </label>

                        <select
                            id="subject_id"
                            name="subject_id"
                            required
                        >

                            <option value="">
                                اختر المادة
                            </option>

                            @foreach($subjects as $subject)

                                <option
                                    value="{{ $subject->id }}"
                                    {{ old('subject_id', $teacher->subject_id) == $subject->id ? 'selected' : '' }}
                                >
                                    {{ $subject->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- ================================================= --}}
                    {{-- المرحلة التعليمية --}}
                    {{-- ================================================= --}}

                    <div class="form-group">

                        <label for="education_stage">
                            المرحلة التعليمية
                        </label>

                        <select
                            id="education_stage"
                            name="education_stage"
                            required
                        >

                            <option value="">
                                اختر المرحلة التعليمية
                            </option>

                            <option
                                value="primary"
                                {{ old('education_stage', $teacher->education_stage) === 'primary' ? 'selected' : '' }}
                            >
                                المرحلة الابتدائية
                            </option>

                            <option
                                value="middle"
                                {{ old('education_stage', $teacher->education_stage) === 'middle' ? 'selected' : '' }}
                            >
                                المرحلة المتوسطة / الإعدادية
                            </option>

                            <option
                                value="secondary"
                                {{ old('education_stage', $teacher->education_stage) === 'secondary' ? 'selected' : '' }}
                            >
                                المرحلة الثانوية
                            </option>

                        </select>

                    </div>

                    {{-- ================================================= --}}
                    {{-- التخصص --}}
                    {{-- ================================================= --}}

                    <div class="form-group full">

                        <label for="specialization">
                            التخصص
                        </label>

                        <input
                            type="text"
                            id="specialization"
                            name="specialization"
                            value="{{ old('specialization', $teacher->specialization) }}"
                        >

                    </div>

                    {{-- ================================================= --}}
                    {{-- كلمة المرور الجديدة --}}
                    {{-- ================================================= --}}

                    <div class="form-group">

                        <label for="password">
                            كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                        >

                        <span class="hint">
                            اتركها فارغة إذا لم ترغب في تغيير كلمة المرور.
                        </span>

                    </div>

                    {{-- ================================================= --}}
                    {{-- تأكيد كلمة المرور الجديدة --}}
                    {{-- ================================================= --}}

                    <div class="form-group">

                        <label for="password_confirmation">
                            تأكيد كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                        >

                    </div>

                </div>

                {{-- ================================================= --}}
                {{-- حفظ التعديلات --}}
                {{-- ================================================= --}}

                <button
                    type="submit"
                    class="save-btn"
                >
                    حفظ التعديلات
                </button>

            </form>

        </div>

    </main>

</div>

</body>
</html>