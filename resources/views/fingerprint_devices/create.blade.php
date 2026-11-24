<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إضافة جهاز بصمة</title>

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
            flex-shrink: 0;
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
            min-width: 0;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 6px;
        }

        .topbar p {
            color: #6b7280;
            font-size: 14px;
        }

        .back-btn {
            display: inline-block;
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 28px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            border: 1px solid #eef0f4;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .required {
            color: #dc2626;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            font-size: 14px;
            background: white;
            color: #1f2937;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .hint {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.7;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            padding-right: 20px;
        }

        .info-box {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            font-size: 14px;
            line-height: 1.9;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .save-btn {
            background: #16a34a;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 9px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .save-btn:hover {
            background: #15803d;
        }

        .cancel-btn {
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 9px;
        }

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main {
                padding: 15px;
            }

            .sidebar {
                width: 210px;
            }

            .form-card {
                padding: 18px;
            }

            .topbar h2 {
                font-size: 22px;
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
                <h2>إضافة جهاز بصمة</h2>
                <p>
                    تسجيل جهاز جديد لإدارة بصمة حضور وانصراف الطلاب والمعلمين
                </p>
            </div>

            <a
                href="{{ route('fingerprint-devices.index') }}"
                class="back-btn"
            >
                العودة للأجهزة
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <strong>يرجى تصحيح الأخطاء التالية:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-box">
            هذه الصفحة مخصصة لمدير النظام لتسجيل أجهزة البصمة وإدارتها.
            إضافة الجهاز هنا لا تعني أنه متصل فعليًا بالنظام؛
            سيتم إعداد الاتصال واستقبال سجلات البصمة في مرحلة الربط.
        </div>

        <div class="form-card">

            <form
                action="{{ route('fingerprint-devices.store') }}"
                method="POST"
            >
                @csrf

                <h3 class="section-title">
                    البيانات الأساسية للجهاز
                </h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="name">
                            اسم الجهاز
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="مثال: جهاز بصمة المدخل الرئيسي"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="device_code">
                            رمز الجهاز
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="device_code"
                            name="device_code"
                            value="{{ old('device_code') }}"
                            placeholder="مثال: FP-001"
                            maxlength="255"
                            required
                            dir="ltr"
                        >

                        <span class="hint">
                            رمز فريد لتمييز الجهاز عن بقية الأجهزة.
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="device_type">
                            نوع الجهاز
                            <span class="required">*</span>
                        </label>

                        <select
                            id="device_type"
                            name="device_type"
                            required
                        >
                            <option value="">
                                اختر نوع الجهاز
                            </option>

                            <option
                                value="physical"
                                {{ old('device_type') === 'physical' ? 'selected' : '' }}
                            >
                                جهاز بصمة فعلي
                            </option>

                            <option
                                value="flutter_simulator"
                                {{ old('device_type') === 'flutter_simulator' ? 'selected' : '' }}
                            >
                                محاكي البصمة - Flutter
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location">
                            موقع الجهاز
                        </label>

                        <input
                            type="text"
                            id="location"
                            name="location"
                            value="{{ old('location') }}"
                            placeholder="مثال: بوابة المدرسة الرئيسية"
                            maxlength="255"
                        >
                    </div>

                    <div class="form-group">
                        <label for="manufacturer">
                            الشركة المصنعة
                        </label>

                        <input
                            type="text"
                            id="manufacturer"
                            name="manufacturer"
                            value="{{ old('manufacturer') }}"
                            placeholder="مثال: ZKTeco"
                            maxlength="255"
                        >
                    </div>

                    <div class="form-group">
                        <label for="model">
                            موديل الجهاز
                        </label>

                        <input
                            type="text"
                            id="model"
                            name="model"
                            value="{{ old('model') }}"
                            placeholder="مثال: K40"
                            maxlength="255"
                        >
                    </div>

                </div>

                <h3 class="section-title">
                    إعدادات الاتصال
                </h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="connection_type">
                            طريقة الاتصال
                            <span class="required">*</span>
                        </label>

                        <select
                            id="connection_type"
                            name="connection_type"
                            required
                        >
                            <option value="">
                                اختر طريقة الاتصال
                            </option>

                            <option
                                value="api"
                                {{ old('connection_type') === 'api' ? 'selected' : '' }}
                            >
                                API
                            </option>

                            <option
                                value="tcp_ip"
                                {{ old('connection_type') === 'tcp_ip' ? 'selected' : '' }}
                            >
                                TCP/IP
                            </option>

                            <option
                                value="usb"
                                {{ old('connection_type') === 'usb' ? 'selected' : '' }}
                            >
                                USB
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ip_address">
                            عنوان IP
                        </label>

                        <input
                            type="text"
                            id="ip_address"
                            name="ip_address"
                            value="{{ old('ip_address') }}"
                            placeholder="مثال: 192.168.1.100"
                            maxlength="45"
                            dir="ltr"
                        >

                        <span class="hint">
                            يُستخدم عند الحاجة إلى الاتصال بالجهاز عبر الشبكة.
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="port">
                            رقم المنفذ (Port)
                        </label>

                        <input
                            type="number"
                            id="port"
                            name="port"
                            value="{{ old('port') }}"
                            placeholder="مثال: 4370"
                            min="1"
                            max="65535"
                            dir="ltr"
                        >
                    </div>

                </div>

                <h3 class="section-title">
                    ملاحظات إضافية
                </h3>

                <div class="form-grid">

                    <div class="form-group full">
                        <label for="notes">
                            الملاحظات
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            maxlength="2000"
                            placeholder="أي معلومات إضافية عن الجهاز..."
                        >{{ old('notes') }}</textarea>
                    </div>

                </div>

                <div class="form-actions">

                    <button type="submit" class="save-btn">
                        حفظ جهاز البصمة
                    </button>

                    <a
                        href="{{ route('fingerprint-devices.index') }}"
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