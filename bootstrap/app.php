<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\EnsureAdminAccess;
use App\Http\Middleware\EnsureAccountIsActive;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | تسجيل أسماء Middleware
        |--------------------------------------------------------------------------
        |
        | الحفاظ على صلاحيات النظام الحالية.
        | إضافة فحص حالة الحساب.
        |
        */

        $middleware->alias([
            'permission' => PermissionMiddleware::class,
            'admin' => EnsureAdminAccess::class,
            'active.account' => EnsureAccountIsActive::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | حماية المسارات التي تتطلب تسجيل الدخول
        |--------------------------------------------------------------------------
        |
        | يتم تنفيذ فحص حالة الحساب بعد المصادقة.
        | إذا كان الحساب معطلًا، يتم إنهاء الجلسة.
        |
        */

        $middleware->appendToGroup(
            'web',
            EnsureAccountIsActive::class
        );

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();