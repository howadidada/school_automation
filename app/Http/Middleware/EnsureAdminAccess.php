<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'تم تعطيل حسابك. يرجى التواصل مع إدارة النظام.');
        }

        if (!$user->hasRole('admin')) {
            abort(403, 'هذه الصفحة مخصصة لمدير النظام فقط.');
        }

        return $next($request);
    }
}