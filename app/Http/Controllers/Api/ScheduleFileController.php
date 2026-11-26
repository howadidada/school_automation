<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ScheduleFileController extends Controller
{
    /**
     * جلب الجداول الدراسية الخاصة بالمعلم.
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

        // جلب المعلم المرتبط بالحساب الحالي
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

        // جلب الشعب المسندة للمعلم
        $sectionIds = DB::table('teacher_assignments')
            ->where('teacher_id', $teacher->id)
            ->pluck('section_id')
            ->filter()
            ->unique()
            ->values();

        // جلب الجداول المسموح للمعلم بالاطلاع عليها
        $scheduleFiles = DB::table('schedule_files as sf')
            ->leftJoin(
                'sections as s',
                's.id',
                '=',
                'sf.section_id'
            )
            ->leftJoin(
                'school_classes as sc',
                'sc.id',
                '=',
                's.school_class_id'
            )
            ->where(function ($query) use ($teacher, $sectionIds) {

                // الجدول الدراسي الخاص بالمعلم
                $query->where(function ($own) use ($teacher) {
                    $own->where('sf.type', 'teacher_schedule')
                        ->where('sf.teacher_id', $teacher->id);
                })

                // جداول الطلاب والاختبارات للشعب المسندة
                ->orWhere(function ($shared) use ($sectionIds) {
                    $shared->whereIn('sf.type', [
                        'student_schedule',
                        'exam_schedule',
                    ])
                    ->whereIn('sf.section_id', $sectionIds);
                });
            })
            ->select(
                'sf.id',
                'sf.title',
                'sf.type',
                'sf.file_path',
                'sf.section_id',
                'sf.created_at',
                's.name as section_name',
                'sc.id as class_id',
                'sc.name as class_name'
            )
            ->orderByDesc('sf.created_at')
            ->get();

        // تجهيز البيانات لتطبيق Flutter
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

                'type' => $file->type,

                'class' => [
                    'id' => $file->class_id,
                    'name' => $file->class_name,
                ],

                'section' => [
                    'id' => $file->section_id,
                    'name' => $file->section_name,
                ],

                'file_type' => $extension,

              'file_url' => url(
             '/api/teacher/schedule-files/'
             . $file->id
             . '/file'
              ),
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
     * عرض ملف الجدول الدراسي.
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

        // التحقق من حساب المعلم
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

        // جلب ملف الجدول
        $scheduleFile = DB::table('schedule_files')
            ->where('id', $id)
            ->first();

        if (!$scheduleFile) {
            return response()->json([
                'success' => false,
                'message' => 'ملف الجدول غير موجود.',
            ], 404);
        }

        // التحقق من صلاحية المعلم لعرض الجدول
        $hasAccess = false;

        if ($scheduleFile->type === 'teacher_schedule') {

            $hasAccess =
                (int) $scheduleFile->teacher_id
                === (int) $teacher->id;

        } elseif (in_array($scheduleFile->type, [
            'student_schedule',
            'exam_schedule',
        ], true)) {

            $hasAccess = DB::table('teacher_assignments')
                ->where('teacher_id', $teacher->id)
                ->where('section_id', $scheduleFile->section_id)
                ->exists();
        }

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض هذا الجدول.',
            ], 403);
        }

        // التحقق من مسار الملف
        if (empty($scheduleFile->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'مسار ملف الجدول غير موجود.',
            ], 404);
        }

        try {

            // تحديد مكان تخزين ملف الجدول الدراسي
           $disk = Storage::disk('local');

         if (!$disk->exists($scheduleFile->file_path)) {
         $disk = Storage::disk('public');
}

            // التحقق من وجود الملف
            if (!$disk->exists($scheduleFile->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ملف الجدول غير موجود على الخادم.',
                ], 404);
            }

            // معرفة امتداد الملف
            $extension = strtolower(
                pathinfo(
                    $scheduleFile->file_path,
                    PATHINFO_EXTENSION
                )
            );

            // تحديد نوع الملف
            $mimeType = match ($extension) {

                'pdf' => 'application/pdf',

                'png' => 'image/png',

                'jpg', 'jpeg' => 'image/jpeg',

                default => null,
            };

            if (!$mimeType) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع الملف غير مدعوم.',
                ], 422);
            }

            // قراءة محتوى الملف
            $contents = $disk->get(
                $scheduleFile->file_path
            );

            if ($contents === null || $contents === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'ملف الجدول فارغ.',
                ], 422);
            }

            // حساب الحجم الحقيقي للملف
            $fileSize = strlen($contents);

            if ($fileSize <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'ملف الجدول غير صالح.',
                ], 422);
            }

            // تحويل الملف إلى Base64
            $base64File = base64_encode($contents);

            return response()->json([
                'success' => true,

                'message' => 'تم جلب ملف الجدول بنجاح.',

                'data' => [
                    'mime_type' => $mimeType,

                    'extension' => $extension,

                    'file_size' => $fileSize,

                    'file_data' => $base64File,
                ],
            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء قراءة ملف الجدول.',
            ], 500);
        }
    }
}