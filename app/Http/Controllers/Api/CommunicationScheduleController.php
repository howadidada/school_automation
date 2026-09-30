<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunicationSchedule;
use App\Models\Teacher;
use Illuminate\Http\Request;

class CommunicationScheduleController extends Controller
{
    // ===============================================================
    // جلب أوقات التواصل للمعلم الحالي
    // ===============================================================
    public function index(Request $request)
    {
        // المستخدم الحالي من الـ Token
        $user = $request->user();

        // البحث عن المعلم المرتبط بهذا المستخدم
        $teacher = Teacher::where(
            'user_id',
            $user->id
        )->first();

        // إذا لم يتم العثور على المعلم
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على بيانات المعلم'
            ], 404);
        }

        // جلب أوقات التواصل الخاصة بالمعلم الحالي
        $schedules = CommunicationSchedule::where(
            'teacher_id',
            $teacher->id
        )->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }


    // ===============================================================
    // حفظ أو تحديث أوقات التواصل
    // ===============================================================
    public function store(Request $request)
    {
        // المستخدم الحالي من الـ Token
        $user = $request->user();

        // البحث عن المعلم المرتبط بالمستخدم
        $teacher = Teacher::where(
            'user_id',
            $user->id
        )->first();

        // إذا لم يتم العثور على المعلم
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على بيانات المعلم'
            ], 404);
        }

        // التحقق من البيانات القادمة من Flutter
        $request->validate([
            'schedules' => 'required|array',

            'schedules.*.day_of_week' => 'required|string',

            'schedules.*.enabled' => 'required|boolean',

            'schedules.*.start_time' => 'nullable',

            'schedules.*.end_time' => 'nullable',
        ]);


        // ===========================================================
        // حذف البيانات القديمة للمعلم الحالي
        // ===========================================================
        CommunicationSchedule::where(
            'teacher_id',
            $teacher->id
        )->delete();


        // ===========================================================
        // حفظ البيانات الجديدة
        // ===========================================================
        foreach ($request->schedules as $schedule) {

            CommunicationSchedule::create([
                'teacher_id' => $teacher->id,

                'day_of_week' => $schedule['day_of_week'],

                'enabled' => $schedule['enabled'],

                // إذا اليوم مفعل نحفظ الوقت
                // وإذا غير مفعل نخزن null
                'start_time' => $schedule['enabled']
                    ? $schedule['start_time']
                    : null,

                'end_time' => $schedule['enabled']
                    ? $schedule['end_time']
                    : null,
            ]);
        }


        // ===========================================================
        // جلب البيانات بعد الحفظ
        // ===========================================================
        $schedules = CommunicationSchedule::where(
            'teacher_id',
            $teacher->id
        )->get();


        return response()->json([
            'success' => true,
            'message' => 'تم حفظ أوقات التواصل بنجاح',
            'data' => $schedules
        ]);
    }
}