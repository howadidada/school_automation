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

            $extension = strtolower(
                pathinfo(
                    $file->file_path,
                    PATHINFO_EXTENSION
                )
            );

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

                'file_type' => $extension,

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
     * جلب ملف الجدول كـ Base64 داخل JSON.
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
        // جلب سجل ملف الجدول
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
            // معرفة امتداد الملف
            // ======================================================
            $extension = strtolower(
                pathinfo(
                    $scheduleFile->file_path,
                    PATHINFO_EXTENSION
                )
            );

            // ======================================================
            // تحديد نوع الملف
            // ======================================================
            $mimeType = match ($extension) {
                'pdf' => 'application/pdf',

                'png' => 'image/png',

                'jpg',
                'jpeg' => 'image/jpeg',

                default => null,
            };

            if (!$mimeType) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع الملف غير مدعوم.',
                ], 422);
            }

            // ======================================================
            // قراءة الملف
            // ======================================================
            $contents = Storage::disk('public')->get(
                $scheduleFile->file_path
            );

            if (
                $contents === null ||
                $contents === ''
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'ملف الجدول فارغ.',
                ], 422);
            }

            // ======================================================
            // الحجم الحقيقي للملف قبل Base64
            // ======================================================
            $fileSize = strlen($contents);

            if ($fileSize <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'ملف الجدول فارغ أو غير صالح.',
                ], 422);
            }

            // ======================================================
            // تحويل الملف إلى Base64
            // ======================================================
            $base64File = base64_encode(
                $contents
            );

            if (empty($base64File)) {
                return response()->json([
                    'success' => false,
                    'message' => 'تعذر تجهيز ملف الجدول.',
                ], 500);
            }

            // ======================================================
            // إرجاع الملف داخل JSON
            // ======================================================
            return response()->json([
                'success' => true,

                'message' =>
                    'تم جلب ملف الجدول بنجاح.',

                'data' => [
                    'mime_type' =>
                        $mimeType,

                    'extension' =>
                        $extension,

                    // الحجم الحقيقي للملف
                    'file_size' =>
                        $fileSize,

                    // الملف Base64
                    'file_data' =>
                        $base64File,
                ],
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