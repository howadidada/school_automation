<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\EnsureApiAccountIsActive;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\GradeBatchController;
use App\Http\Controllers\Api\StudentNoteController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ScheduleFileController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommunicationScheduleController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TeacherAssignmentController;

// ================================================================
// Sila School System
// API Routes
// ================================================================

// ================================================================
// Authentication API
// ================================================================

// تسجيل الدخول
Route::post(
    '/login',
    [AuthController::class, 'login']
);

// ================================================================
// استعادة كلمة المرور عبر البريد الإلكتروني
// ================================================================

// إرسال رابط استعادة كلمة المرور إلى بريد المعلم
Route::post(
    '/forgot-password',
    [ForgotPasswordController::class, 'sendResetLink']
)->middleware('throttle:5,1');

// ================================================================
// Protected API Routes
// ================================================================
//
// التحقق من رمز تسجيل الدخول باستخدام Sanctum.
// التحقق من أن حساب المستخدم نشط عند كل طلب.
//
// ================================================================

Route::middleware([
    'auth:sanctum',
    EnsureApiAccountIsActive::class,
])->group(function () {

    // ============================================================
    // Authentication
    // ============================================================

    // بيانات المستخدم الحالي
    Route::get(
        '/user',
        [AuthController::class, 'me']
    );

    // تسجيل الخروج
    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );

    // ============================================================
    // Teacher Assignments
    // ============================================================

    Route::get(
        '/teacher/assignments',
        [TeacherAssignmentController::class, 'show']
    );

    // ============================================================
    // Teacher Schedule Files
    // ============================================================

    Route::get(
        '/teacher/schedule-files',
        [ScheduleFileController::class, 'index']
    );

    Route::get(
        '/teacher/schedule-files/{id}/file',
        [ScheduleFileController::class, 'show']
    );

    // ============================================================
    // Grades
    // ============================================================

    Route::apiResource(
        'grades',
        GradeController::class
    );

    // ============================================================
    // Grade Batches
    // إرسال واعتماد كشوف الدرجات
    // ============================================================

    // عرض كشوف الدرجات
    Route::get(
        '/grade-batches',
        [GradeBatchController::class, 'index']
    );

    // إنشاء كشف درجات
    Route::post(
        '/grade-batches',
        [GradeBatchController::class, 'store']
    );

    // عرض تفاصيل كشف درجات
    Route::get(
        '/grade-batches/{gradeBatch}',
        [GradeBatchController::class, 'show']
    );

    // إرسال الكشف للاعتماد
    Route::post(
        '/grade-batches/{gradeBatch}/submit',
        [GradeBatchController::class, 'submit']
    );

    // اعتماد الكشف من الوكيل
    Route::post(
        '/grade-batches/{gradeBatch}/approve',
        [GradeBatchController::class, 'approve']
    );

    // رفض الكشف وإعادته للمعلم
    Route::post(
        '/grade-batches/{gradeBatch}/reject',
        [GradeBatchController::class, 'reject']
    );

    // ============================================================
    // Student Notes
    // ============================================================

    Route::apiResource(
        'student-notes',
        StudentNoteController::class
    );

    // ============================================================
    // Tasks
    // ============================================================

    Route::apiResource(
        'tasks',
        TaskController::class
    );

    // ============================================================
    // Attendance
    // ============================================================

    // عرض حضور طالب
    Route::get(
        '/attendance/student/{studentId}',
        [AttendanceController::class, 'studentAttendance']
    );

    // تسجيل الحضور
    Route::post(
        '/attendance',
        [AttendanceController::class, 'store']
    );

    // تعديل الحضور
    Route::put(
        '/attendance/{id}',
        [AttendanceController::class, 'update']
    );

    // حذف سجل الحضور
    Route::delete(
        '/attendance/{id}',
        [AttendanceController::class, 'destroy']
    );

    // ============================================================
    // Communication Schedules
    // ============================================================

    Route::get(
        '/teacher/communication-schedules',
        [CommunicationScheduleController::class, 'index']
    );

    Route::post(
        '/teacher/communication-schedules',
        [CommunicationScheduleController::class, 'store']
    );

    // ============================================================
    // Messages
    // ============================================================

    // عرض الرسائل
    Route::get(
        '/messages',
        [MessageController::class, 'index']
    );

    // إرسال رسالة
    Route::post(
        '/messages',
        [MessageController::class, 'store']
    );

    // تعديل رسالة
    Route::put(
        '/messages/{id}',
        [MessageController::class, 'update']
    );

    // حذف رسالة
    Route::delete(
        '/messages/{id}',
        [MessageController::class, 'destroy']
    );

    // ============================================================
    // Notifications
    // ============================================================

    // عرض الإشعارات
    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    );

    // تحديد إشعار كمقروء
    Route::put(
        '/notifications/{id}/read',
        [NotificationController::class, 'markAsRead']
    );

    // تحديد جميع الإشعارات كمقروءة
    Route::put(
        '/notifications/read-all',
        [NotificationController::class, 'markAllAsRead']
    );

});