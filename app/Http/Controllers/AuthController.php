<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * عرض صفحة تسجيل دخول مدير النظام.
     */
    public function showLogin()
    {
        if (Auth::check()) {

            if (
                Auth::user()->is_active &&
                Auth::user()->hasRole('admin')
            ) {
                return redirect()->route('dashboard');
            }

            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return view('auth.login');
    }

    /**
     * تسجيل دخول مدير النظام فقط.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | السماح للحسابات النشطة فقط
        |--------------------------------------------------------------------------
        */

        $credentials['is_active'] = true;

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'بيانات تسجيل الدخول غير صحيحة أو الحساب غير مفعل.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | السماح لمدير النظام فقط
        |--------------------------------------------------------------------------
        */

        if (!Auth::user()->hasRole('admin')) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'هذا الحساب غير مخوّل للدخول إلى لوحة مدير النظام.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | تجديد الجلسة بعد نجاح تسجيل الدخول
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        return redirect()->intended(
            route('dashboard')
        );
    }

    /**
     * تسجيل الخروج.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}