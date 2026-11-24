<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {

            $user = Auth::user();

            if (!$user->is_active) {

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'تم تعطيل حسابك. يرجى التواصل مع إدارة النظام.',
                    ], 403);
                }

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'تم تعطيل حسابك. يرجى التواصل مع إدارة النظام.'
                    );
            }
        }

        return $next($request);
    }
}