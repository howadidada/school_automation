
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\EnsureApiAccountIsActive;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ChangePasswordController;
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
use App\Http\Controllers\Api\AcademicSettingController;

// ================================================================
// Sila School System
// API Routes
// ================================================================

// ================================================================
// Authentication API
// تسجيل الدخول
// ================================================================

Route::post(
    '/login',
    [AuthController::class, 'login']
);

// ================================================================
// Forgot Password
// استعادة كلمة المرور
// ================================================================

Route::post(
    '/forgot-password',
    [ForgotPasswordController::class, 'sendResetLink']
)->middleware('throttle:5,1');

// ================================================================
// Protected API Routes
// المسارات المحمية
// ================================================================

Route::middleware([
    'auth:sanctum',
    EnsureApiAccountIsActive::class,
])->group(function () {

    // ============================================================
    // Authentication
    // ============================================================

    Route::get(
        '/user',
        [AuthController::class, 'me']
    );

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );

    // ============================================================
    // Change Password
    // تغيير كلمة المرور
    // ============================================================

    Route::post(
        '/change-password',
        [ChangePasswordController::class, 'update']
    )->middleware('throttle:5,1');

    // ============================================================
    // Academic Settings
    // الإعدادات الأكاديمية المعتمدة من مدير النظام
    // ============================================================

    Route::get(
        '/academic-settings/current',
        [AcademicSettingController::class, 'current']
    );

    // ============================================================
    // Teacher Assignments
    // تكليفات المعلم
    // ============================================================

    Route::get(
        '/teacher/assignments',
        [TeacherAssignmentController::class, 'show']
    );

    // ============================================================
    // Teacher Schedule Files
    // ملفات الجداول
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
    // درجات الطلاب
    // ============================================================

    Route::apiResource(
        'grades',
        GradeController::class
    );

    // ============================================================
    // Grade Batches
    // كشوف الدرجات
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

    // إرسال الكشف للمراجعة
    Route::post(
        '/grade-batches/{gradeBatch}/submit',
        [GradeBatchController::class, 'submit']
    );

    // اعتماد كشف الدرجات
    Route::post(
        '/grade-batches/{gradeBatch}/approve',
        [GradeBatchController::class, 'approve']
    );

    // رفض الكشف وإعادته للتعديل
    Route::post(
        '/grade-batches/{gradeBatch}/reject',
        [GradeBatchController::class, 'reject']
    );

    // ============================================================
    // Student Notes
    // الملاحظات السلوكية
    // ============================================================

    Route::apiResource(
        'student-notes',
        StudentNoteController::class
    );

    // ============================================================
    // Tasks
    // الواجبات والأنشطة
    // ============================================================

    Route::apiResource(
        'tasks',
        TaskController::class
    );

    // ============================================================
    // Attendance
    // الحضور والغياب
    // ============================================================

    Route::get(
        '/attendance/student/{studentId}',
        [AttendanceController::class, 'studentAttendance']
    );

    Route::post(
        '/attendance',
        [AttendanceController::class, 'store']
    );

    Route::put(
        '/attendance/{id}',
        [AttendanceController::class, 'update']
    );

    Route::delete(
        '/attendance/{id}',
        [AttendanceController::class, 'destroy']
    );

    // ============================================================
    // Communication Schedules
    // أوقات التواصل
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
    // الرسائل
    // ============================================================
     Route::get(
    '/messages/recipients',
    [MessageController::class, 'recipients']
    );
    Route::get(
        '/messages',
        [MessageController::class, 'index']
    );

    Route::post(
        '/messages',
        [MessageController::class, 'store']
    );

    Route::put(
        '/messages/{id}',
        [MessageController::class, 'update']
    );

    Route::delete(
        '/messages/{id}',
        [MessageController::class, 'destroy']
    );

    // ============================================================
    // Notifications
    // الإشعارات
    // ============================================================

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    );

    Route::put(
        '/notifications/{id}/read',
        [NotificationController::class, 'markAsRead']
    );

    Route::put(
        '/notifications/read-all',
        [NotificationController::class, 'markAllAsRead']
    );

});
