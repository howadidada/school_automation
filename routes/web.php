<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountActivationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\ScheduleFileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FingerprintDeviceController;
use App\Http\Controllers\AnnouncementController;

/*
|--------------------------------------------------------------------------
| الصفحة الرئيسية
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| تسجيل الدخول
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'login']
)
    ->middleware('throttle:5,1')
    ->name('login.submit');

/*
|--------------------------------------------------------------------------
| تسجيل الخروج
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| تفعيل الحساب وإنشاء كلمة المرور
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get(
        '/activate-account/{token}',
        [AccountActivationController::class, 'show']
    )->name('account.activation.show');

    Route::post(
        '/activate-account',
        [AccountActivationController::class, 'activate']
    )
        ->middleware('throttle:5,1')
        ->name('account.activation.submit');

    Route::get(
        '/activation-success',
        [AccountActivationController::class, 'success']
    )->name('account.activation.success');

});

/*
|--------------------------------------------------------------------------
| استعادة كلمة المرور للمعلم
|--------------------------------------------------------------------------
|
| هذه المسارات خاصة بالمعلم الذي فعّل حسابه سابقًا
| ونسي كلمة المرور.
|
| المعلم يطلب رابط الاستعادة من تطبيق Flutter.
| ثم يفتح الرابط من بريده الإلكتروني.
| ويحدد كلمة المرور الجديدة من صفحة Laravel.
|
| هذه العملية منفصلة عن تفعيل الحساب لأول مرة.
|
*/

Route::get(
    '/reset-password/{token}',
    [PasswordResetController::class, 'show']
)->name('password.reset.form');

Route::post(
    '/reset-password',
    [PasswordResetController::class, 'reset']
)
    ->middleware('throttle:5,1')
    ->name('password.reset.submit');

Route::get(
    '/password-reset-success',
    [PasswordResetController::class, 'success']
)->name('password.reset.success');

/*
|--------------------------------------------------------------------------
| لوحة التحكم
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)
    ->middleware(['auth', 'admin'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| مسارات إدارة النظام
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | إدارة المستخدمين
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إدارة المستخدمين'
    )->group(function () {

        Route::get(
            '/users',
            [UserController::class, 'index']
        )->name('users.index');

        Route::get(
            '/users/create',
            [UserController::class, 'create']
        )->name('users.create');

        Route::post(
            '/users',
            [UserController::class, 'store']
        )->name('users.store');

        Route::get(
            '/users/{user}/edit',
            [UserController::class, 'edit']
        )->name('users.edit');

        Route::put(
            '/users/{user}',
            [UserController::class, 'update']
        )->name('users.update');

        Route::patch(
            '/users/{user}/toggle-status',
            [UserController::class, 'toggleStatus']
        )->name('users.toggle-status');

        Route::post(
            '/users/{user}/resend-activation',
            [UserController::class, 'resendActivation']
        )
            ->middleware('throttle:3,1')
            ->name('users.resend-activation');

    });

    /*
    |--------------------------------------------------------------------------
    | إدارة الأدوار والصلاحيات
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إدارة الأدوار والصلاحيات'
    )->group(function () {

        Route::get(
            '/roles',
            [RoleController::class, 'index']
        )->name('roles.index');

        Route::get(
            '/roles/create',
            [RoleController::class, 'create']
        )->name('roles.create');

        Route::post(
            '/roles',
            [RoleController::class, 'store']
        )->name('roles.store');

        Route::get(
            '/roles/{role}/edit',
            [RoleController::class, 'edit']
        )->name('roles.edit');

        Route::put(
            '/roles/{role}',
            [RoleController::class, 'update']
        )->name('roles.update');

        Route::delete(
            '/roles/{role}',
            [RoleController::class, 'destroy']
        )->name('roles.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | إدارة الطلاب
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إدارة الطلاب'
    )->group(function () {

        Route::get(
            '/students',
            [StudentController::class, 'index']
        )->name('students.index');

        Route::get(
            '/students/create',
            [StudentController::class, 'create']
        )->name('students.create');

        Route::post(
            '/students',
            [StudentController::class, 'store']
        )->name('students.store');

        Route::get(
            '/students/{student}/edit',
            [StudentController::class, 'edit']
        )->name('students.edit');

        Route::put(
            '/students/{student}',
            [StudentController::class, 'update']
        )->name('students.update');

        Route::delete(
            '/students/{student}',
            [StudentController::class, 'destroy']
        )->name('students.destroy');

        Route::post(
            '/students/{student}/resend-activation',
            [StudentController::class, 'resendActivation']
        )
            ->middleware('throttle:3,1')
            ->name('students.resend-activation');

    });

    /*
    |--------------------------------------------------------------------------
    | إدارة المعلمين
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إدارة المعلمين'
    )->group(function () {

        Route::get(
            '/teachers',
            [TeacherController::class, 'index']
        )->name('teachers.index');

        Route::get(
            '/teachers/create',
            [TeacherController::class, 'create']
        )->name('teachers.create');

        Route::post(
            '/teachers',
            [TeacherController::class, 'store']
        )->name('teachers.store');

        Route::get(
            '/teachers/{teacher}/edit',
            [TeacherController::class, 'edit']
        )->name('teachers.edit');

        Route::put(
            '/teachers/{teacher}',
            [TeacherController::class, 'update']
        )->name('teachers.update');

        Route::post(
            '/teachers/{teacher}/resend-activation',
            [TeacherController::class, 'resendActivation']
        )
            ->middleware('throttle:3,1')
            ->name('teachers.resend-activation');

        Route::patch(
            '/teachers/{teacher}/toggle-status',
            [TeacherController::class, 'toggleStatus']
        )->name('teachers.toggle-status');

        Route::delete(
            '/teachers/{teacher}',
            [TeacherController::class, 'destroy']
        )->name('teachers.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | إدارة أولياء الأمور
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إدارة أولياء الأمور'
    )->group(function () {

        Route::get(
            '/parents',
            [ParentController::class, 'index']
        )->name('parents.index');

        Route::get(
            '/parents/create',
            [ParentController::class, 'create']
        )->name('parents.create');

        Route::post(
            '/parents',
            [ParentController::class, 'store']
        )->name('parents.store');

        Route::get(
            '/parents/{parent}/edit',
            [ParentController::class, 'edit']
        )->name('parents.edit');

        Route::put(
            '/parents/{parent}',
            [ParentController::class, 'update']
        )->name('parents.update');

        Route::delete(
            '/parents/{parent}',
            [ParentController::class, 'destroy']
        )->name('parents.destroy');

        Route::post(
            '/parents/{parent}/resend-activation',
            [ParentController::class, 'resendActivation']
        )
            ->middleware('throttle:3,1')
            ->name('parents.resend-activation');

    });

    /*
    |--------------------------------------------------------------------------
    | إدارة الفصول والشعب
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إدارة الفصول والشعب'
    )->group(function () {

        Route::get(
            '/classes',
            [SchoolClassController::class, 'index']
        )->name('classes.index');

        Route::get(
            '/classes/create',
            [SchoolClassController::class, 'create']
        )->name('classes.create');

        Route::post(
            '/classes',
            [SchoolClassController::class, 'store']
        )->name('classes.store');

        Route::get(
            '/classes/{schoolClass}/edit',
            [SchoolClassController::class, 'edit']
        )->name('classes.edit');

        Route::put(
            '/classes/{schoolClass}',
            [SchoolClassController::class, 'update']
        )->name('classes.update');

        Route::delete(
            '/classes/{schoolClass}',
            [SchoolClassController::class, 'destroy']
        )->name('classes.destroy');

        Route::get(
            '/sections',
            [SectionController::class, 'index']
        )->name('sections.index');

        Route::get(
            '/sections/create',
            [SectionController::class, 'create']
        )->name('sections.create');

        Route::post(
            '/sections',
            [SectionController::class, 'store']
        )->name('sections.store');

        Route::get(
            '/sections/{section}/edit',
            [SectionController::class, 'edit']
        )->name('sections.edit');

        Route::put(
            '/sections/{section}',
            [SectionController::class, 'update']
        )->name('sections.update');

        Route::delete(
            '/sections/{section}',
            [SectionController::class, 'destroy']
        )->name('sections.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | إدارة المواد الدراسية
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إدارة المواد الدراسية'
    )->group(function () {

        Route::get(
            '/subjects',
            [SubjectController::class, 'index']
        )->name('subjects.index');

        Route::get(
            '/subjects/create',
            [SubjectController::class, 'create']
        )->name('subjects.create');

        Route::post(
            '/subjects',
            [SubjectController::class, 'store']
        )->name('subjects.store');

        Route::get(
            '/subjects/{subject}/edit',
            [SubjectController::class, 'edit']
        )->name('subjects.edit');

        Route::delete(
            '/subjects/{subject}',
            [SubjectController::class, 'destroy']
        )->name('subjects.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | توزيع المعلمين
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إسناد المعلمين'
    )->group(function () {

        Route::get(
            '/teacher-assignments',
            [TeacherAssignmentController::class, 'index']
        )->name('teacher-assignments.index');

        Route::get(
            '/teacher-assignments/create',
            [TeacherAssignmentController::class, 'create']
        )->name('teacher-assignments.create');

        Route::post(
            '/teacher-assignments',
            [TeacherAssignmentController::class, 'store']
        )->name('teacher-assignments.store');

        Route::get(
            '/teacher-assignments/{teacherAssignment}/edit',
            [TeacherAssignmentController::class, 'edit']
        )->name('teacher-assignments.edit');

        Route::put(
            '/teacher-assignments/{teacherAssignment}',
            [TeacherAssignmentController::class, 'update']
        )->name('teacher-assignments.update');

        Route::delete(
            '/teacher-assignments/{teacherAssignment}',
            [TeacherAssignmentController::class, 'destroy']
        )->name('teacher-assignments.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | إدارة الجداول الدراسية
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:رفع الجدول الدراسي'
    )->group(function () {

        Route::get(
            '/schedule-files',
            [ScheduleFileController::class, 'index']
        )->name('schedule-files.index');

        Route::get(
            '/schedule-files/create',
            [ScheduleFileController::class, 'create']
        )->name('schedule-files.create');

        Route::post(
            '/schedule-files',
            [ScheduleFileController::class, 'store']
        )->name('schedule-files.store');

        Route::get(
            '/schedule-files/{scheduleFile}/show',
            [ScheduleFileController::class, 'show']
        )->name('schedule-files.show');

        Route::get(
            '/schedule-files/{scheduleFile}/edit',
            [ScheduleFileController::class, 'edit']
        )->name('schedule-files.edit');

        Route::put(
            '/schedule-files/{scheduleFile}',
            [ScheduleFileController::class, 'update']
        )->name('schedule-files.update');

        Route::delete(
            '/schedule-files/{scheduleFile}',
            [ScheduleFileController::class, 'destroy']
        )->name('schedule-files.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | إدارة الإعلانات المدرسية - جديد
    |--------------------------------------------------------------------------
    |
    | جميع هذه المسارات محمية بواسطة:
    | auth + admin
    |
    | لا توجد مسارات عامة لعرض الإعلانات حاليًا.
    | سنضيف لاحقًا API يتحقق من الفئات المستهدفة.
    |
    */

    Route::prefix('announcements')
        ->name('announcements.')
        ->group(function () {

            Route::get(
                '/',
                [AnnouncementController::class, 'index']
            )->name('index');

            Route::get(
                '/create',
                [AnnouncementController::class, 'create']
            )->name('create');

            Route::post(
                '/',
                [AnnouncementController::class, 'store']
            )->name('store');

            Route::get(
                '/{announcement}',
                [AnnouncementController::class, 'show']
            )->name('show');

            Route::get(
                '/{announcement}/edit',
                [AnnouncementController::class, 'edit']
            )->name('edit');

            Route::put(
                '/{announcement}',
                [AnnouncementController::class, 'update']
            )->name('update');

            Route::delete(
                '/{announcement}',
                [AnnouncementController::class, 'destroy']
            )->name('destroy');

        });

    /*
    |--------------------------------------------------------------------------
    | تسجيل الحضور والغياب
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/attendances',
        [AttendanceController::class, 'index']
    )->name('attendances.index');

    Route::post(
        '/attendances',
        [AttendanceController::class, 'store']
    )->name('attendances.store');

    /*
    |--------------------------------------------------------------------------
    | إدارة أجهزة البصمة
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'permission:إدارة أجهزة البصمة'
    )->group(function () {

        Route::get(
            '/fingerprint-devices',
            [FingerprintDeviceController::class, 'index']
        )->name('fingerprint-devices.index');

        Route::get(
            '/fingerprint-devices/create',
            [FingerprintDeviceController::class, 'create']
        )->name('fingerprint-devices.create');

        Route::post(
            '/fingerprint-devices',
            [FingerprintDeviceController::class, 'store']
        )->name('fingerprint-devices.store');

        Route::get(
            '/fingerprint-devices/{fingerprintDevice}/edit',
            [FingerprintDeviceController::class, 'edit']
        )->name('fingerprint-devices.edit');

        Route::put(
            '/fingerprint-devices/{fingerprintDevice}',
            [FingerprintDeviceController::class, 'update']
        )->name('fingerprint-devices.update');

        Route::patch(
            '/fingerprint-devices/{fingerprintDevice}/toggle-status',
            [FingerprintDeviceController::class, 'toggleStatus']
        )->name('fingerprint-devices.toggle-status');

    });

});
// ==========================================================
// الإعدادات الأكاديمية - مدير النظام
// ==========================================================

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get(
        '/academic-settings',
        [\App\Http\Controllers\AcademicSettingController::class, 'index']
    )->name('academic-settings.index');

    Route::post(
        '/academic-settings',
        [\App\Http\Controllers\AcademicSettingController::class, 'store']
    )->name('academic-settings.store');

    Route::post(
        '/academic-settings/{academicSetting}/activate',
        [\App\Http\Controllers\AcademicSettingController::class, 'activate']
    )->name('academic-settings.activate');

});