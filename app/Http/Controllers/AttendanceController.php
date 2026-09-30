<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // ===============================================================
    // صفحة تسجيل الحضور
    // ===============================================================
    public function index(Request $request)
    {
        // جلب جميع الشعب مع اسم الفصل
        $sections = DB::table('sections as s')
            ->join(
                'school_classes as sc',
                'sc.id',
                '=',
                's.school_class_id'
            )
            ->select(
                's.id',
                's.name as section_name',
                'sc.name as class_name'
            )
            ->orderBy('sc.name')
            ->orderBy('s.name')
            ->get();

        $selectedSectionId = $request->get('section_id');

        $attendanceDate = $request->get(
            'attendance_date',
            now()->format('Y-m-d')
        );

        $students = collect();
        $existingAttendances = collect();

        // إذا تم اختيار شعبة
        if ($selectedSectionId) {

            // جلب طلاب الشعبة
            $students = DB::table('students as st')
                ->join(
                    'users as u',
                    'u.id',
                    '=',
                    'st.user_id'
                )
                ->where(
                    'st.section_id',
                    $selectedSectionId
                )
                ->select(
                    'st.id',
                    'st.student_number',
                    'u.name'
                )
                ->orderBy('u.name')
                ->get();

            // جلب حضورهم في نفس التاريخ لو موجود
            $studentIds = $students
                ->pluck('id')
                ->values();

            if ($studentIds->isNotEmpty()) {
                $existingAttendances = Attendance::whereIn(
                    'student_id',
                    $studentIds
                )
                    ->whereDate(
                        'attendance_date',
                        $attendanceDate
                    )
                    ->get()
                    ->keyBy('student_id');
            }
        }

        return view(
            'attendances.index',
            compact(
                'sections',
                'students',
                'selectedSectionId',
                'attendanceDate',
                'existingAttendances'
            )
        );
    }

    // ===============================================================
    // حفظ الحضور
    // ===============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' =>
                'required|in:present,absent,late,excused',
        ]);

        // الطلاب الموجودون فعليًا في الشعبة
        $studentIds = DB::table('students')
            ->where(
                'section_id',
                $validated['section_id']
            )
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        DB::transaction(function () use (
            $validated,
            $studentIds
        ) {
            foreach (
                $validated['attendance']
                as $studentId => $status
            ) {
                $studentId = (int) $studentId;

                // نتأكد أن الطالب من نفس الشعبة
                if (!in_array(
                    $studentId,
                    $studentIds,
                    true
                )) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'attendance_date' =>
                            $validated['attendance_date'],
                    ],
                    [
                        'status' => $status,
                    ]
                );
            }
        });

        return redirect()
            ->route(
                'attendances.index',
                [
                    'section_id' =>
                        $validated['section_id'],
                    'attendance_date' =>
                        $validated['attendance_date'],
                ]
            )
            ->with(
                'success',
                'تم حفظ حضور الطلاب بنجاح.'
            );
    }
}