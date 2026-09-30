<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\StudentNoteController;
use App\Http\Controllers\Api\TeacherAssignmentController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ScheduleFileController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommunicationScheduleController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;

use Illuminate\Support\Facades\Route;


// ===================================================================
// Authentication API
// ===================================================================

// تسجيل الدخول فقط مسموح بدون Token
Route::post(
    '/login',
    [AuthController::class, 'login']
);


// ===================================================================
// جميع الوظائف المحمية بـ Sanctum
// ===================================================================
Route::middleware('auth:sanctum')->group(function () {

    // ===============================================================
    // بيانات المستخدم الحالي
    // ===============================================================
    Route::get(
        '/user',
        [AuthController::class, 'me']
    );


    // ===============================================================
    // تسجيل الخروج
    // ===============================================================
    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );


    // ===============================================================
    // مواد + شعب + طلاب المعلم الحالي
    // ===============================================================
    Route::get(
        '/teacher/assignments',
        [TeacherAssignmentController::class, 'show']
    );


    // ===============================================================
    // ملفات الجدول الدراسي
    // ===============================================================
    Route::get(
        '/teacher/schedule-files',
        [ScheduleFileController::class, 'index']
    );

    Route::get(
        '/teacher/schedule-files/{id}/file',
        [ScheduleFileController::class, 'show']
    );


    // ===============================================================
    // الدرجات والتقييمات
    // ===============================================================
    Route::apiResource(
        'grades',
        GradeController::class
    );


    // ===============================================================
    // ملاحظات الطلاب
    // ===============================================================
    Route::apiResource(
        'student-notes',
        StudentNoteController::class
    );


    // ===============================================================
    // الواجبات والأنشطة
    // ===============================================================
    Route::apiResource(
        'tasks',
        TaskController::class
    );


    // ===============================================================
    // الحضور
    // ===============================================================
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


    // ===============================================================
    // أوقات التواصل مع أولياء الأمور
    // ===============================================================

    // جلب أوقات التواصل للمعلم الحالي
    Route::get(
        '/teacher/communication-schedules',
        [CommunicationScheduleController::class, 'index']
    );

    // حفظ وتحديث أوقات التواصل
    Route::post(
        '/teacher/communication-schedules',
        [CommunicationScheduleController::class, 'store']
    );


    // ===============================================================
    // الرسائل
    // ===============================================================

    // جلب الرسائل الواردة والمرسلة
    Route::get(
        '/messages',
        [MessageController::class, 'index']
    );

    // إرسال رسالة جديدة
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


    // ===============================================================
    // الإشعارات
    // ===============================================================

    // جلب إشعارات المستخدم الحالي
    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    );

    // تحديد إشعار واحد كمقروء
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