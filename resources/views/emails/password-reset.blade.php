<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>استعادة كلمة المرور | نظام صلة</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f3f6fb;
    font-family: Tahoma, Arial, sans-serif;
    direction: rtl;
">

    <table
        role="presentation"
        width="100%"
        cellpadding="0"
        cellspacing="0"
        style="background-color: #f3f6fb; padding: 35px 15px;"
    >
        <tr>
            <td align="center">

                <table
                    role="presentation"
                    width="100%"
                    cellpadding="0"
                    cellspacing="0"
                    style="
                        max-width: 500px;
                        background-color: #ffffff;
                        border-radius: 16px;
                    "
                >

                    <!-- شعار النظام -->

                    <tr>
                        <td
                            align="center"
                            style="padding: 35px 25px 10px;"
                        >

                            <div style="
                                font-size: 38px;
                                font-weight: bold;
                                color: #2563eb;
                            ">
                                صلة
                            </div>

                        </td>
                    </tr>

                    <!-- عنوان الرسالة -->

                    <tr>
                        <td
                            align="center"
                            style="padding: 10px 25px;"
                        >

                            <h2 style="
                                margin: 0;
                                color: #1e293b;
                                font-size: 23px;
                            ">
                                استعادة كلمة المرور
                            </h2>

                        </td>
                    </tr>

                    <!-- محتوى الرسالة -->

                    <tr>
                        <td style="
                            padding: 20px 30px;
                            color: #475569;
                            font-size: 14px;
                            line-height: 2;
                            text-align: right;
                        ">

                            <p>
                                مرحبًا {{ $user->name }}،
                            </p>

                            <p>
                                تلقينا طلبًا لإعادة تعيين كلمة المرور
                                الخاصة بحسابك في نظام صلة لإدارة المدارس.
                            </p>

                            <p>
                                لإعادة تعيين كلمة المرور،
                                اضغط على الزر التالي:
                            </p>

                        </td>
                    </tr>

                    <!-- زر استعادة كلمة المرور -->

                    <tr>
                        <td
                            align="center"
                            style="padding: 10px 25px 25px;"
                        >

                            <a
                                href="{{ $resetUrl }}"
                                style="
                                    display: inline-block;
                                    background-color: #2563eb;
                                    color: #ffffff;
                                    text-decoration: none;
                                    padding: 14px 28px;
                                    border-radius: 9px;
                                    font-size: 15px;
                                    font-weight: bold;
                                "
                            >
                                إعادة تعيين كلمة المرور
                            </a>

                        </td>
                    </tr>

                    <!-- مدة صلاحية الرابط -->

                    <tr>
                        <td style="padding: 0 30px 20px;">

                            <div style="
                                background-color: #eff6ff;
                                color: #1d4ed8;
                                padding: 16px;
                                border-radius: 9px;
                                font-size: 13px;
                                line-height: 1.9;
                                text-align: center;
                            ">

                                رابط استعادة كلمة المرور صالح لمدة
                                <strong>60 دقيقة</strong>.

                                <br>

                                بعد تغيير كلمة المرور،
                                يمكنك العودة إلى تطبيق صلة
                                وتسجيل الدخول باستخدام
                                كلمة المرور الجديدة.

                            </div>

                        </td>
                    </tr>

                    <!-- تنبيه أمني -->

                    <tr>
                        <td style="
                            padding: 5px 30px 20px;
                            color: #64748b;
                            font-size: 13px;
                            line-height: 1.9;
                            text-align: right;
                        ">

                            <p>
                                إذا لم تطلب استعادة كلمة المرور،
                                يمكنك تجاهل هذه الرسالة.
                            </p>

                            <p>
                                حفاظًا على أمان حسابك،
                                لا تشارك رابط الاستعادة
                                مع أي شخص آخر.
                            </p>

                        </td>
                    </tr>

                    <!-- رابط بديل -->

                    <tr>
                        <td style="
                            padding: 0 30px 25px;
                            color: #64748b;
                            font-size: 12px;
                            line-height: 1.8;
                            overflow-wrap: anywhere;
                        ">

                            إذا لم يعمل الزر،
                            انسخ الرابط التالي وافتحه في المتصفح:

                            <br><br>

                            <a
                                href="{{ $resetUrl }}"
                                style="color: #2563eb; word-break: break-all;"
                                dir="ltr"
                            >
                                {{ $resetUrl }}
                            </a>

                        </td>
                    </tr>

                    <!-- التذييل -->

                    <tr>
                        <td
                            align="center"
                            style="
                                padding: 22px;
                                border-top: 1px solid #e2e8f0;
                                color: #94a3b8;
                                font-size: 12px;
                            "
                        >

                            نظام صلة لإدارة المدارس

                            <br>

                            هذه رسالة آلية، يرجى عدم الرد عليها.

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>