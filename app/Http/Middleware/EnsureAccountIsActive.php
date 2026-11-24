<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (Auth::check() && !Auth::user()->is_active) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'تم تعطيل حسابك. يرجى التواصل مع إدارة المدرسة.',
                ], 403);
            }

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'تم تعطيل حسابك. يرجى التواصل مع إدارة المدرسة.'
                );
        }

        return $next($request);
    }
}