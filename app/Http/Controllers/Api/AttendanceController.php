<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // ============================================================
    // جلب حضور طالب + حساب النسبة
    // ============================================================
    public function studentAttendance($studentId)
    {
        $attendances = Attendance::where('student_id', $studentId)
            ->orderBy('attendance_date', 'desc')
            ->get();

        $total = $attendances->count();

        $present = $attendances
            ->where('status', 'present')
            ->count();

        $late = $attendances
            ->where('status', 'late')
            ->count();

        $absent = $attendances
            ->where('status', 'absent')
            ->count();

        $excused = $attendances
            ->where('status', 'excused')
            ->count();

        $attendancePercentage = $total > 0
            ? round((($present + $late) / $total) * 100, 2)
            : null;

        return response()->json([
            'success' => true,
            'data' => [
                'student_id' => (int) $studentId,
                'total_days' => $total,
                'present_days' => $present,
                'late_days' => $late,
                'absent_days' => $absent,
                'excused_days' => $excused,
                'attendance_percentage' => $attendancePercentage,
                'records' => $attendances->values(),
            ],
        ]);
    }

    // ============================================================
    // إضافة حضور
    // ============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            [
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ الحضور بنجاح',
            'data' => $attendance,
        ]);
    }

    // ============================================================
    // تعديل الحضور
    // ============================================================
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل الحضور بنجاح',
            'data' => $attendance,
        ]);
    }

    // ============================================================
    // حذف الحضور
    // ============================================================
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف سجل الحضور بنجاح',
        ]);
    }
}