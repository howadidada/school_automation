<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::middleware('permission:إدارة المستخدمين')->group(function () {

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::patch(
            '/users/{user}/toggle-status',
            [UserController::class, 'toggleStatus']
        )->name('users.toggle-status');
    });


    Route::middleware('permission:إدارة الأدوار والصلاحيات')->group(function () {

        Route::get('/roles', [RoleController::class, 'index'])
            ->name('roles.index');

        Route::get('/roles/create', [RoleController::class, 'create'])
            ->name('roles.create');

        Route::post('/roles', [RoleController::class, 'store'])
            ->name('roles.store');

        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
            ->name('roles.edit');

        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->name('roles.update');

        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->name('roles.destroy');
    });


    Route::middleware('permission:إدارة الطلاب')->group(function () {

        Route::get('/students', [StudentController::class, 'index'])
            ->name('students.index');

        Route::get('/students/create', [StudentController::class, 'create'])
            ->name('students.create');

        Route::post('/students', [StudentController::class, 'store'])
            ->name('students.store');

        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
            ->name('students.edit');

        Route::put('/students/{student}', [StudentController::class, 'update'])
            ->name('students.update');

        Route::delete('/students/{student}', [StudentController::class, 'destroy'])
            ->name('students.destroy');
    });


    Route::middleware('permission:إدارة المعلمين')->group(function () {

        Route::get('/teachers', [TeacherController::class, 'index'])
            ->name('teachers.index');

        Route::get('/teachers/create', [TeacherController::class, 'create'])
            ->name('teachers.create');

        Route::post('/teachers', [TeacherController::class, 'store'])
            ->name('teachers.store');

        Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])
            ->name('teachers.edit');

        Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])
            ->name('teachers.update');

        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])
            ->name('teachers.destroy');
    });


    Route::middleware('permission:إدارة أولياء الأمور')->group(function () {

        Route::get('/parents', [ParentController::class, 'index'])
            ->name('parents.index');

        Route::get('/parents/create', [ParentController::class, 'create'])
            ->name('parents.create');

        Route::post('/parents', [ParentController::class, 'store'])
            ->name('parents.store');

        Route::get('/parents/{parent}/edit', [ParentController::class, 'edit'])
            ->name('parents.edit');

        Route::put('/parents/{parent}', [ParentController::class, 'update'])
            ->name('parents.update');

        Route::delete('/parents/{parent}', [ParentController::class, 'destroy'])
            ->name('parents.destroy');
    });


    Route::middleware('permission:إدارة الفصول والشعب')->group(function () {

        Route::get('/classes', [SchoolClassController::class, 'index'])
            ->name('classes.index');

        Route::get('/classes/create', [SchoolClassController::class, 'create'])
            ->name('classes.create');

        Route::post('/classes', [SchoolClassController::class, 'store'])
            ->name('classes.store');

        Route::get('/classes/{schoolClass}/edit', [SchoolClassController::class, 'edit'])
            ->name('classes.edit');

        Route::put('/classes/{schoolClass}', [SchoolClassController::class, 'update'])
            ->name('classes.update');

        Route::delete('/classes/{schoolClass}', [SchoolClassController::class, 'destroy'])
            ->name('classes.destroy');


        Route::get('/sections', [SectionController::class, 'index'])
            ->name('sections.index');

        Route::get('/sections/create', [SectionController::class, 'create'])
            ->name('sections.create');

        Route::post('/sections', [SectionController::class, 'store'])
            ->name('sections.store');

        Route::get('/sections/{section}/edit', [SectionController::class, 'edit'])
            ->name('sections.edit');

        Route::put('/sections/{section}', [SectionController::class, 'update'])
            ->name('sections.update');

        Route::delete('/sections/{section}', [SectionController::class, 'destroy'])
            ->name('sections.destroy');
    });


    Route::middleware('permission:إدارة المواد الدراسية')->group(function () {

        Route::get('/subjects', [SubjectController::class, 'index'])
            ->name('subjects.index');

        Route::get('/subjects/create', [SubjectController::class, 'create'])
            ->name('subjects.create');

        Route::post('/subjects', [SubjectController::class, 'store'])
            ->name('subjects.store');

        Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])
            ->name('subjects.edit');

        Route::put('/subjects/{subject}', [SubjectController::class, 'update'])
            ->name('subjects.update');

        Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])
            ->name('subjects.destroy');
    });


    Route::middleware('permission:إسناد المعلمين')->group(function () {

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


    Route::middleware('permission:رفع الجدول الدراسي')->group(function () {

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

});