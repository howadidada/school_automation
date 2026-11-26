<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunicationSchedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CommunicationScheduleController extends Controller
{
    private function getTeacher(Request $request)
    {
        return Teacher::where(
            'user_id',
            $request->user()->id
        )->first();
    }

    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على بيانات المعلم.',
            ], 404);
        }

        $schedules = CommunicationSchedule::where(
            'teacher_id',
            $teacher->id
        )->get();

        return response()->json([
            'success' => true,
            'data' => $schedules,
        ]);
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher($request);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على بيانات المعلم.',
            ], 404);
        }

        $days = [
            'السبت',
            'الأحد',
            'الاثنين',
            'الثلاثاء',
            'الأربعاء',
            'الخميس',
            'الجمعة',
        ];

        $validated = $request->validate([
            'schedules' => [
                'required',
                'array',
                'min:1',
                'max:7',
            ],

            'schedules.*.day_of_week' => [
                'required',
                'string',
                'distinct',
                Rule::in($days),
            ],

            'schedules.*.enabled' => [
                'required',
                'boolean',
            ],

            'schedules.*.start_time' => [
                'nullable',
                'string',
                'regex:/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/',
            ],

            'schedules.*.end_time' => [
                'nullable',
                'string',
                'regex:/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/',
            ],
        ]);

        foreach ($validated['schedules'] as $index => $schedule) {

            if (!$schedule['enabled']) {
                continue;
            }

            $start = $schedule['start_time'] ?? null;
            $end = $schedule['end_time'] ?? null;

            if (!$start || !$end) {
                throw ValidationException::withMessages([
                    "schedules.$index.start_time" =>
                        'يجب تحديد بداية ونهاية وقت التواصل.',
                ]);
            }

            if (strlen($start) === 5) {
                $start .= ':00';
            }

            if (strlen($end) === 5) {
                $end .= ':00';
            }

            if ($end <= $start) {
                throw ValidationException::withMessages([
                    "schedules.$index.end_time" =>
                        'وقت النهاية يجب أن يكون بعد وقت البداية.',
                ]);
            }
        }

        $schedules = DB::transaction(function () use (
            $teacher,
            $validated
        ) {
            DB::table('teachers')
                ->where('id', $teacher->id)
                ->lockForUpdate()
                ->first();

            CommunicationSchedule::where(
                'teacher_id',
                $teacher->id
            )->delete();

            foreach ($validated['schedules'] as $schedule) {

                CommunicationSchedule::create([
                    'teacher_id' => $teacher->id,

                    'day_of_week' =>
                        $schedule['day_of_week'],

                    'enabled' =>
                        $schedule['enabled'],

                    'start_time' => $schedule['enabled']
                        ? $schedule['start_time']
                        : null,

                    'end_time' => $schedule['enabled']
                        ? $schedule['end_time']
                        : null,
                ]);
            }

            return CommunicationSchedule::where(
                'teacher_id',
                $teacher->id
            )->get();
        });

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ أوقات التواصل بنجاح.',
            'data' => $schedules,
        ]);
    }
}