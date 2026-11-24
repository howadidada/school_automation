<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تفعيل الحساب | نظام صلة</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Tahoma, Arial, sans-serif;
            background: #f3f6fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1e293b;
        }

        .activation-card {
            background: #ffffff;
            width: 100%;
            max-width: 460px;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .description {
            text-align: center;
            color: #64748b;
            line-height: 1.9;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #334155;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background: #ffffff;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .email-field {
            background: #f8fafc;
            color: #475569;
            direction: ltr;
            text-align: left;
            cursor: not-allowed;
        }

        .password-hint {
            color: #64748b;
            font-size: 12px;
            line-height: 1.8;
            margin-top: 7px;
        }

        .submit-btn {
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .submit-btn:disabled {
            background: #93c5fd;
            cursor: not-allowed;
        }

        .error-box {
            background: #fef2f2;
            color: #b91c1c;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.8;
        }

        .error-box ul {
            margin: 0;
            padding-right: 20px;
        }

        .info-box {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 14px;
            border-radius: 9px;
            margin-top: 22px;
            font-size: 13px;
            line-height: 1.9;
            text-align: center;
        }

        .note {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            line-height: 1.9;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .activation-card {
                padding: 25px 20px;
            }

            h2 {
                font-size: 21px;
            }

            .logo {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

    <div class="activation-card">

        <!-- شعار النظام -->

        <div class="logo">
            صلة
        </div>

        <h2>
            تفعيل حسابك
        </h2>

        <p class="description">
            مرحبًا بك في نظام صلة لإدارة المدارس.
            <br>
            قم بإنشاء كلمة مرور آمنة لتفعيل حسابك
            والبدء باستخدام النظام.
        </p>

        <!-- عرض أخطاء التحقق -->

        @if ($errors->any())

            <div class="error-box" role="alert">

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <!-- نموذج التفعيل -->

        <form
            id="activationForm"
            action="{{ route('account.activation.submit') }}"
            method="POST"
        >

            @csrf

            <!-- رمز التفعيل -->

            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >

            <!-- البريد الإلكتروني -->

            <input
                type="hidden"
                name="email"
                value="{{ $email }}"
            >

            <div class="form-group">

                <label for="email_display">
                    البريد الإلكتروني
                </label>

                <input
                    type="email"
                    id="email_display"
                    class="email-field"
                    value="{{ $email }}"
                    readonly
                >

            </div>

            <!-- كلمة المرور -->

            <div class="form-group">

                <label for="password">
                    كلمة المرور الجديدة
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="أدخل كلمة مرور قوية"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

                <p class="password-hint">
                    يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل،
                    وتتضمن حرفًا كبيرًا وحرفًا صغيرًا ورقمًا.
                </p>

            </div>

            <!-- تأكيد كلمة المرور -->

            <div class="form-group">

                <label for="password_confirmation">
                    تأكيد كلمة المرور
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="أعد إدخال كلمة المرور"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

            </div>

            <!-- زر التفعيل -->

            <button
                type="submit"
                id="activationButton"
                class="submit-btn"
            >
                تفعيل الحساب
            </button>

        </form>

        <!-- معلومات مهمة -->

        <div class="info-box">

            بعد تفعيل حسابك بنجاح،
            يمكنك تسجيل الدخول من خلال
            الواجهة المخصصة لك في نظام صلة.

            <br>

            إذا كنت طالبًا أو ولي أمر،
            يمكنك استخدام حسابك في تطبيق صلة
            بعد تفعيله.

        </div>

        <!-- ملاحظة انتهاء الصلاحية -->

        <p class="note">

            إذا انتهت صلاحية رابط التفعيل
            أو لم تتمكن من استخدامه،
            يمكنك التواصل مع إدارة المدرسة
            لطلب إرسال رابط جديد.

        </p>

        <!-- التذييل -->

        <div class="footer">
            نظام صلة لإدارة المدارس
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('activationForm');

            const button = document.getElementById('activationButton');

            form.addEventListener('submit', function (event) {

                if (!form.checkValidity()) {
                    return;
                }

                if (button.disabled) {
                    event.preventDefault();
                    return;
                }

                button.disabled = true;

                button.textContent = 'جارٍ تفعيل الحساب...';

            });

        });
    </script>

</body>
</html>