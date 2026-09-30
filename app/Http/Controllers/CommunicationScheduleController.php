<?php

namespace App\Http\Controllers;

use App\Models\CommunicationSchedule;
use Illuminate\Http\Request;

class CommunicationScheduleController extends Controller
{
    // جلب أوقات التواصل للمعلم
    public function index($teacher_id)
    {
        $schedules = CommunicationSchedule::where('teacher_id', $teacher_id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }


    // حفظ أوقات التواصل
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required',
            'schedules' => 'required|array',
        ]);


        // حذف الإعدادات القديمة
        CommunicationSchedule::where(
            'teacher_id',
            $request->teacher_id
        )->delete();


        // حفظ الجديدة
        foreach ($request->schedules as $schedule) {

            CommunicationSchedule::create([
                'teacher_id' => $request->teacher_id,
                'day_of_week' => $schedule['day_of_week'],
                'enabled' => $schedule['enabled'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
            ]);
        }


        return response()->json([
            'success' => true,
            'message' => 'تم حفظ أوقات التواصل بنجاح'
        ]);
    }
}