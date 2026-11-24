<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use RuntimeException;

class AccountActivationService
{
    /**
     * التحقق من أن الحساب ينتظر التفعيل.
     */
    public function isPendingActivation(User $user): bool
    {
        return !$user->is_active
            && $user->password === null;
    }

    /**
     * إرسال رابط تفعيل الحساب.
     *
     * يُستخدم عند إنشاء الحساب لأول مرة،
     * وكذلك عند إعادة إرسال الرابط.
     */
    public function send(User $user): void
    {
        $user->refresh();

        if (!$this->isPendingActivation($user)) {
            throw new RuntimeException(
                'هذا الحساب لا ينتظر التفعيل.'
            );
        }

        if (
            empty($user->email) ||
            !filter_var($user->email, FILTER_VALIDATE_EMAIL)
        ) {
            throw new RuntimeException(
                'البريد الإلكتروني للمستخدم غير صالح.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | إنشاء رمز التفعيل
        |--------------------------------------------------------------------------
        |
        | نستخدم Password Broker الموجود في Laravel،
        | والمتوافق مع AccountActivationController.
        |
        | لا نحذف الرمز السابق قبل محاولة الإرسال؛
        | حتى لا نُبطل رابطًا صالحًا إذا تعذر إرسال البريد.
        |
        */

        $token = Password::broker()->createToken($user);

        /*
        |--------------------------------------------------------------------------
        | إنشاء رابط التفعيل
        |--------------------------------------------------------------------------
        |
        | نستخدم عنوانًا ثابتًا لروابط البريد الإلكتروني،
        | حتى لا يظهر عنوان محاكي Android داخل الرسائل.
        |
        */

        $baseUrl = rtrim(
            (string) config(
                'sila.mail_link_base_url',
                config('app.url')
            ),
            '/'
        );

        $activationPath = route(
            'account.activation.show',
            [
                'token' => $token,
                'email' => $user->email,
            ],
            false
        );

        $activationUrl = $baseUrl . $activationPath;

        /*
        |--------------------------------------------------------------------------
        | إرسال البريد الإلكتروني
        |--------------------------------------------------------------------------
        */

        Mail::send(
            'emails.account-activation',
            [
                'user' => $user,
                'activationUrl' => $activationUrl,
            ],
            function ($message) use ($user) {
                $message
                    ->to($user->email)
                    ->subject('تفعيل حسابك في نظام صلة');
            }
        );
    }
}