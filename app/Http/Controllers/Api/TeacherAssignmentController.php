<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAssignmentController extends Controller
{
    /**
     * جلب بيانات المعلم الحالي
     * مع الإسنادات والفصول والشعب والطلاب.
     */
    public function show(Request $request)
    {
        // ============================================================
        // المستخدم المسجل دخوله حاليًا
        // ============================================================
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
            ], 401);
        }

        // ============================================================
        // جلب المعلم المرتبط بالمستخدم الحالي
        // ============================================================
        $teacher = Teacher::with([
            'user',
            'subject',
        ])
            ->where(
                'user_id',
                $user->id
            )
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على بيانات المعلم.',
            ], 404);
        }

        // ============================================================
        // جلب إسنادات المعلم
        //
        // نستخدم العلاقات الموجودة في المشروع:
        // subject
        // section.schoolClass
        //
        // بدل الربط الخاطئ sec.class_id
        // ============================================================
        $teacherAssignments =
            TeacherAssignment::with([
                'subject',
                'section.schoolClass',
            ])
                ->where(
                    'teacher_id',
                    $teacher->id
                )
                ->get();

        // ============================================================
        // تجهيز الإسنادات بالشكل المطلوب في Flutter
        // ============================================================
        $assignments =
            $teacherAssignments->map(
                function ($assignment) {

                    $section =
                        $assignment->section;

                    $schoolClass =
                        $section?->schoolClass;

                    // =================================================
                    // جلب طلاب الشعبة
                    // =================================================
                    $students = collect();

                    if ($section) {
                        $students =
                            DB::table('students as st')
                                ->join(
                                    'users as u',
                                    'u.id',
                                    '=',
                                    'st.user_id'
                                )
                                ->where(
                                    'st.section_id',
                                    $section->id
                                )
                                ->select(
                                    'st.id',
                                    'st.user_id',
                                    'u.name',
                                    'u.email',
                                    'st.student_number',
                                    'st.gender'
                                )
                                ->get();
                    }

                    // =================================================
                    // البيانات التي ستصل إلى Flutter
                    // =================================================
                    return [
                        'assignment_id' =>
                            $assignment->id,

                        'subject' => [
                            'id' =>
                                $assignment->subject?->id,

                            'name' =>
                                $assignment->subject?->name
                                ?? '',
                        ],

                        'class' => [
                            'id' =>
                                $schoolClass?->id,

                            'name' =>
                                $schoolClass?->name
                                ?? '',

                            'grade_level' =>
                                $schoolClass?->grade_level
                                ?? $schoolClass?->education_stage
                                ?? '',
                        ],

                        'section' => [
                            'id' =>
                                $section?->id,

                            'name' =>
                                $section?->name
                                ?? '',

                            'capacity' =>
                                $section?->capacity,
                        ],

                        'students' =>
                            $students,
                    ];
                }
            )
            ->values();

        // ============================================================
        // الاستجابة النهائية
        // ============================================================
        return response()->json([
            'success' => true,

            'data' => [

                // ====================================================
                // بيانات المعلم
                // ====================================================
                'teacher' => [
                    'id' =>
                        $teacher->id,

                    'user_id' =>
                        $teacher->user_id,

                    'employee_number' =>
                        $teacher->employee_number,

                    'name' =>
                        $teacher->user?->name
                        ?? '',

                    'username' =>
                        $teacher->user?->username
                        ?? '',

                    'email' =>
                        $teacher->user?->email
                        ?? '',

                    'subject_id' =>
                        $teacher->subject_id,

                    'subject_name' =>
                        $teacher->subject?->name
                        ?? '',

                    'specialization' =>
                        $teacher->specialization
                        ?? '',
                ],

                // ====================================================
                // الإسنادات
                // ====================================================
                'assignments' =>
                    $assignments,
            ],
        ]);
    }
}