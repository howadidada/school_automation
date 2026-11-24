<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تم تغيير كلمة المرور | نظام صلة</title>

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

        .success-card {
            background: #ffffff;
            width: 100%;
            max-width: 460px;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 25px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #dcfce7;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            font-weight: bold;
        }

        h2 {
            color: #1e293b;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .description {
            color: #64748b;
            font-size: 14px;
            line-height: 2;
            margin-bottom: 25px;
        }

        .info-box {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 16px;
            border-radius: 9px;
            font-size: 13px;
            line-height: 2;
            margin-bottom: 20px;
        }

        .note {
            color: #64748b;
            font-size: 12px;
            line-height: 1.9;
            margin-top: 20px;
        }

        .footer {
            margin-top: 25px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .success-card {
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

    <div class="success-card">

        <!-- شعار نظام صلة -->

        <div class="logo">
            صلة
        </div>

        <!-- أيقونة النجاح -->

        <div class="success-icon">
            &#10003;
        </div>

        <!-- رسالة النجاح -->

        <h2>
            تم تغيير كلمة المرور بنجاح
        </h2>

        <p class="description">
            تم حفظ كلمة المرور الجديدة لحسابك
            في نظام صلة بنجاح.

            <br>

            يمكنك الآن تسجيل الدخول باستخدام
            اسم المستخدم وكلمة المرور الجديدة.
        </p>

        <!-- تعليمات العودة للتطبيق -->

        <div class="info-box">

            افتح تطبيق صلة على هاتفك.

            <br>

            انتقل إلى شاشة تسجيل الدخول.

            <br>

            أدخل اسم المستخدم وكلمة المرور الجديدة.

        </div>

        <!-- ملاحظة أمنية -->

        <p class="note">
            حفاظًا على أمان حسابك،
            تم إلغاء رموز تسجيل الدخول السابقة.
            وستحتاج إلى تسجيل الدخول مجددًا.
        </p>

        <!-- التذييل -->

        <div class="footer">
            نظام صلة لإدارة المدارس
        </div>

    </div>

</body>
</html>