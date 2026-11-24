<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiAccountIsActive
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = $request->user();

        // التحقق من تسجيل الدخول
        if (!$user) {
            return response()->json([
                'success' => false,
                'code' => 'UNAUTHENTICATED',
                'message' => 'يرجى تسجيل الدخول مجددًا.',
            ], 401);
        }

        // التحقق من حالة الحساب
        if (!$user->is_active) {

            // إلغاء جميع رموز تسجيل الدخول
            $user->tokens()->delete();

            return response()->json([
                'success' => false,
                'code' => 'ACCOUNT_DISABLED',
                'message' => 'تم تعطيل حسابك. يرجى التواصل مع إدارة المدرسة.',
            ], 403);
        }

        // السماح للحساب النشط بالمتابعة
        return $next($request);
    }
}