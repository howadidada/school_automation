<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ScheduleFileController extends Controller
{
    /**
     * جلب ملفات الجدول الخاصة بشعب المعلم.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
                'data' => null,
            ], 401);
        }

        // ==========================================================
        // جلب المعلم المرتبط بالمستخدم
        // ==========================================================
        $teacher = DB::table('teachers')
            ->where('user_id', $user->id)
            ->select('id')
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الحساب غير مرتبط بمعلم.',
                'data' => null,
            ], 404);
        }

        // ==========================================================
        // جلب الشعب المسندة للمعلم
        // ==========================================================
        $sectionIds = DB::table('teacher_assignments')
            ->where('teacher_id', $teacher->id)
            ->pluck('section_id')
            ->filter()
            ->unique()
            ->values();

        if ($sectionIds->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'لا توجد شعب مسندة لهذا المعلم.',
                'data' => [],
            ]);
        }

        // ==========================================================
        // جلب ملفات الجدول الخاصة بشعب المعلم
        // ==========================================================
        $scheduleFiles = DB::table('schedule_files as sf')
            ->join(
                'sections as s',
                's.id',
                '=',
                'sf.section_id'
            )
            ->join(
                'school_classes as sc',
                'sc.id',
                '=',
                's.school_class_id'
            )
            ->whereIn(
                'sf.section_id',
                $sectionIds
            )
            ->select(
                'sf.id',
                'sf.title',
                'sf.file_path',
                'sf.section_id',
                'sf.created_at',
                's.name as section_name',
                'sc.id as class_id',
                'sc.name as class_name'
            )
            ->orderByDesc('sf.created_at')
            ->get();

        // ==========================================================
        // تجهيز البيانات لتطبيق Flutter
        // ==========================================================
        $data = $scheduleFiles->map(function ($file) {
            return [
                'id' => $file->id,

                'title' => $file->title,

                'class' => [
                    'id' => $file->class_id,
                    'name' => $file->class_name,
                ],

                'section' => [
                    'id' => $file->section_id,
                    'name' => $file->section_name,
                ],

                'file_url' =>
                    'http://10.0.2.2:8000/api/teacher/schedule-files/'
                    . $file->id
                    . '/file',

                'created_at' => $file->created_at,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'تم جلب ملفات الجدول الدراسي بنجاح.',
            'data' => $data,
        ]);
    }

    /**
     * فتح ملف الجدول داخل تطبيق Flutter.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
            ], 401);
        }

        // ==========================================================
        // جلب المعلم المرتبط بالمستخدم
        // ==========================================================
        $teacher = DB::table('teachers')
            ->where('user_id', $user->id)
            ->select('id')
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الحساب غير مرتبط بمعلم.',
            ], 404);
        }

        // ==========================================================
        // جلب ملف الجدول
        // ==========================================================
        $scheduleFile = DB::table('schedule_files')
            ->where('id', $id)
            ->first();

        if (!$scheduleFile) {
            return response()->json([
                'success' => false,
                'message' => 'ملف الجدول غير موجود.',
            ], 404);
        }

        // ==========================================================
        // التأكد أن المعلم مرتبط بالشعبة الخاصة بهذا الجدول
        // ==========================================================
        $hasAccess = DB::table('teacher_assignments')
            ->where('teacher_id', $teacher->id)
            ->where(
                'section_id',
                $scheduleFile->section_id
            )
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض هذا الجدول.',
            ], 403);
        }

        // ==========================================================
        // التأكد من وجود مسار الملف
        // ==========================================================
        if (empty($scheduleFile->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'مسار ملف الجدول غير موجود.',
            ], 404);
        }

        // ==========================================================
        // التأكد من وجود الملف فعلياً
        // ==========================================================
        if (
            !Storage::disk('public')->exists(
                $scheduleFile->file_path
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'ملف الجدول غير موجود على الخادم.',
            ], 404);
        }

        try {
            // ======================================================
            // قراءة ملف PDF كاملاً
            // ======================================================
            $fileContents = Storage::disk('public')->get(
                $scheduleFile->file_path
            );

            // ======================================================
            // التأكد أن الملف ليس فارغاً
            // ======================================================
            if (
                $fileContents === null ||
                $fileContents === ''
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'ملف الجدول فارغ.',
                ], 404);
            }

            // ======================================================
            // التأكد أن الملف PDF
            // ======================================================
            if (
                substr($fileContents, 0, 4)
                !== '%PDF'
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'ملف الجدول ليس ملف PDF صالحاً.',
                ], 422);
            }

            // ======================================================
            // إرسال PDF
            //
            // مهم:
            // لا نحدد Content-Length يدوياً.
            // نترك Laravel / PHP يدير طول وإنهاء الاستجابة.
            // ======================================================
            return response(
                $fileContents,
                200
            )->withHeaders([
                'Content-Type' => 'application/pdf',
                'Content-Disposition' =>
                    'inline; filename="schedule.pdf"',
                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' =>
                    'حدث خطأ أثناء قراءة ملف الجدول.',
            ], 500);
        }
    }
}