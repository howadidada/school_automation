<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AccountActivationController extends Controller
{
    /**
     * عرض صفحة تفعيل الحساب.
     */
    public function show(Request $request, string $token)
    {
        $email = $request->query('email');

        if (!is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            abort(403, 'رابط التفعيل غير صالح.');
        }

        $user = User::with('role')
            ->where('email', $email)
            ->first();

        if (!$user || $user->is_active || $user->password !== null) {
            abort(403, 'رابط التفعيل غير صالح أو تم تفعيل الحساب مسبقًا.');
        }

        if (!Password::broker()->tokenExists($user, $token)) {
            abort(403, 'رابط التفعيل غير صالح أو انتهت صلاحيته.');
        }

        return view('auth.activate-account', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * حفظ كلمة المرور وتفعيل الحساب.
     */
    public function activate(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | التحقق من الرابط وتفعيل الحساب داخل معاملة آمنة
        |--------------------------------------------------------------------------
        */

        $result = DB::transaction(function () use ($validated) {

            $user = User::with('role')
                ->where('email', $validated['email'])
                ->lockForUpdate()
                ->first();

            if (!$user || $user->is_active || $user->password !== null) {
                return null;
            }

            if (!Password::broker()->tokenExists($user, $validated['token'])) {
                return null;
            }

            $user->forceFill([
                'password' => $validated['password'],
                'is_active' => true,
                'remember_token' => Str::random(60),
            ])->save();

            Password::broker()->deleteToken($user);

            return [
                'role' => $user->role?->name,
            ];

        }, 3);

        /*
        |--------------------------------------------------------------------------
        | فشل التفعيل
        |--------------------------------------------------------------------------
        */

        if ($result === null) {
            return back()->withErrors([
                'email' => 'رابط التفعيل غير صالح أو انتهت صلاحيته أو تم استخدامه مسبقًا.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | مدير النظام: تسجيل الدخول إلى لوحة الإدارة
        |--------------------------------------------------------------------------
        */

        if ($result['role'] === 'admin') {
            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'تم تفعيل حسابك بنجاح. يمكنك الآن تسجيل الدخول إلى لوحة الإدارة.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | بقية المستخدمين: صفحة نجاح التفعيل
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('account.activation.success');
    }

    /**
     * عرض صفحة نجاح التفعيل.
     */
    public function success()
    {
        return view('auth.activation-success');
    }
}