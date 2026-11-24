<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تفعيل حسابك في نظام صلة</title>
</head>

<body style="
    margin: 0;
    padding: 30px 15px;
    background-color: #f3f6fb;
    font-family: Tahoma, Arial, sans-serif;
    direction: rtl;
">

    <table
        role="presentation"
        width="100%"
        cellpadding="0"
        cellspacing="0"
        style="max-width: 560px; margin: auto;"
    >
        <tr>
            <td style="
                background-color: #ffffff;
                padding: 35px 25px;
                border-radius: 14px;
                text-align: center;
            ">

                <!-- اسم النظام -->
                <h1 style="
                    color: #2563eb;
                    font-size: 36px;
                    margin: 0 0 10px;
                ">
                    صلة
                </h1>

                <p style="
                    color: #64748b;
                    font-size: 14px;
                    margin-bottom: 30px;
                ">
                    نظام الإدارة المدرسية الذكي
                </p>

                <h2 style="
                    color: #1e293b;
                    font-size: 22px;
                ">
                    مرحبًا بك في نظام صلة
                </h2>

                <p style="
                    color: #334155;
                    font-size: 15px;
                    line-height: 2;
                ">
                    أهلًا بك،
                    <strong>{{ $user->name }}</strong>
                    <br><br>

                    تم إنشاء حساب خاص بك في نظام صلة
                    لإدارة المدارس.

                    <br>

                    لإكمال تفعيل حسابك، يرجى الضغط
                    على الزر التالي وإنشاء كلمة مرور
                    خاصة بك.
                </p>
<!-- زر تفعيل الحساب -->
<table
    role="presentation"
    align="center"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="margin: 30px auto;"
>
    <tr>
        <td
            align="center"
            bgcolor="#2563eb"
            style="border-radius: 8px;"
        >
            <a
                href="{{ $activationUrl }}"
                target="_blank"
                style="
                    background-color: #2563eb;
                    border-radius: 8px;
                    color: #ffffff;
                    display: inline-block;
                    font-family: Arial, sans-serif;
                    font-size: 16px;
                    font-weight: bold;
                    line-height: 24px;
                    padding: 15px 35px;
                    text-align: center;
                    text-decoration: none;
                "
            >
                تفعيل حسابي
            </a>
        </td>
    </tr>
</table>

<!-- رابط احتياطي -->
<p style="
    color: #64748b;
    font-size: 13px;
    line-height: 2;
    text-align: center;
">
    إذا لم يعمل زر التفعيل، يمكنك استخدام الرابط التالي:
</p>

<p style="
    text-align: center;
    direction: ltr;
    overflow-wrap: anywhere;
    word-break: break-all;
    font-size: 12px;
    line-height: 1.8;
">
    <a
        href="{{ $activationUrl }}"
        target="_blank"
        style="
            color: #2563eb;
            text-decoration: underline;
        "
    >{{ $activationUrl }}</a>
</p>

                <p style="
                    color: #64748b;
                    font-size: 13px;
                    line-height: 2;
                ">
                    🔐 رابط التفعيل صالح لمدة
                    <strong>60 دقيقة</strong>
                    فقط.

                    <br>

                    بعد إنشاء كلمة المرور بنجاح،
                    لن تتمكن من استخدام رابط التفعيل
                    مرة أخرى.
                </p>

                <hr style="
                    border: none;
                    border-top: 1px solid #e2e8f0;
                    margin: 25px 0;
                ">

                <p style="
                    color: #64748b;
                    font-size: 12px;
                    line-height: 1.9;
                ">
                    إذا لم تكن تتوقع إنشاء هذا الحساب،
                    يمكنك تجاهل الرسالة والتواصل
                    مع إدارة المدرسة.
                </p>

                <p style="
                    color: #94a3b8;
                    font-size: 12px;
                    margin-top: 25px;
                ">
                    © نظام صلة لإدارة المدارس
                </p>

            </td>
        </tr>
    </table>

</body>
</html>