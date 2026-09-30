<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentNoteController extends Controller
{
    // ===============================================================
    // الحصول على المعلم المسجل دخوله
    // ===============================================================
    private function getAuthenticatedTeacher(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return null;
        }

        return DB::table('teachers')
            ->where('user_id', $user->id)
            ->first();
    }

    // ===============================================================
    // التحقق أن الطالب ضمن شعبة مسندة للمعلم
    //
    // إذا تم إرسال subjectId:
    // يجب أن تكون نفس المادة مسندة للمعلم في شعبة الطالب.
    // ===============================================================
    private function teacherCanAccessStudent(
        int $teacherId,
        int $studentId,
        ?int $subjectId = null
    ): bool {
        $student = DB::table('students')
            ->where('id', $studentId)
            ->first();

        if (!$student) {
            return false;
        }

        $query = DB::table('teacher_assignments')
            ->where('teacher_id', $teacherId)
            ->where(
                'section_id',
                $student->section_id
            );

        if ($subjectId !== null) {
            $query->where(
                'subject_id',
                $subjectId
            );
        }

        return $query->exists();
    }

    // ===============================================================
    // جلب الملاحظات
    // ===============================================================
    public function index(Request $request)
    {
        $teacher =
            $this->getAuthenticatedTeacher(
                $request
            );

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' =>
                    'هذا الحساب غير مرتبط بمعلم.',
            ], 403);
        }

        $query = StudentNote::with([
            'student',
            'teacher',
            'subject',
        ])
            ->where(
                'teacher_id',
                $teacher->id
            );

        // -----------------------------------------------------------
        // فلترة حسب الطالب
        // -----------------------------------------------------------
        if ($request->filled('student_id')) {
            $studentId =
                (int) $request->student_id;

            $subjectId = null;

            if ($request->filled('subject_id')) {
                $subjectId =
                    (int) $request->subject_id;
            }

            if (
                !$this->teacherCanAccessStudent(
                    $teacher->id,
                    $studentId,
                    $subjectId
                )
            ) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'غير مصرح لك بالوصول إلى ملاحظات هذا الطالب.',
                ], 403);
            }

            $query->where(
                'student_id',
                $studentId
            );
        }

        // -----------------------------------------------------------
        // فلترة حسب المادة - اختيارية
        // -----------------------------------------------------------
        if ($request->filled('subject_id')) {
            $subjectId =
                (int) $request->subject_id;

            $query->where(
                'subject_id',
                $subjectId
            );
        }

        // -----------------------------------------------------------
        // فلترة حسب نوع الملاحظة - اختيارية
        // -----------------------------------------------------------
        if ($request->filled('type')) {
            $type =
                $request->type;

            $allowedTypes = [
                'behavior',
                'positive',
                'academic_warning',
            ];

            if (!in_array(
                $type,
                $allowedTypes,
                true
            )) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'نوع الملاحظة غير صحيح.',
                ], 422);
            }

            $query->where(
                'type',
                $type
            );
        }

        // -----------------------------------------------------------
        // جلب الملاحظات
        // -----------------------------------------------------------
        $notes = $query
            ->orderByDesc('note_date')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success' => true,
            'message' =>
                'تم جلب الملاحظات بنجاح.',
            'data' => $notes,
        ]);
    }

    // ===============================================================
    // إضافة ملاحظة جديدة
    // ===============================================================
    public function store(Request $request)
    {
        $teacher =
            $this->getAuthenticatedTeacher(
                $request
            );

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' =>
                    'هذا الحساب غير مرتبط بمعلم.',
            ], 403);
        }

        // -----------------------------------------------------------
        // Validation
        // -----------------------------------------------------------
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'subject_id' => [
                'nullable',
                'integer',
                'exists:subjects,id',
            ],

            'type' => [
                'required',
                'string',
                'in:behavior,positive,academic_warning',
            ],

            'note' => [
                'required',
                'string',
                'max:1000',
            ],

            'note_date' => [
                'required',
                'date',
            ],
        ]);

        $studentId =
            (int) $validated['student_id'];

        $subjectId =
            isset($validated['subject_id'])
                ? (int) $validated['subject_id']
                : null;

        // -----------------------------------------------------------
        // التحقق أن الطالب ضمن شعبة المعلم
        // وإذا وجدت مادة نتأكد من المادة + الشعبة
        // -----------------------------------------------------------
        if (
            !$this->teacherCanAccessStudent(
                $teacher->id,
                $studentId,
                $subjectId
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بإضافة ملاحظة لهذا الطالب.',
            ], 403);
        }

        // -----------------------------------------------------------
        // إنشاء الملاحظة
        //
        // teacher_id يؤخذ من Token وليس من Flutter
        // -----------------------------------------------------------
        $note = StudentNote::create([
            'student_id' =>
                $studentId,

            'teacher_id' =>
                $teacher->id,

            'subject_id' =>
                $subjectId,

            'type' =>
                $validated['type'],

            'note' =>
                trim($validated['note']),

            'note_date' =>
                $validated['note_date'],
        ]);

        $note->load([
            'student',
            'teacher',
            'subject',
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'تم حفظ الملاحظة بنجاح.',
            'data' => $note,
        ], 201);
    }

    // ===============================================================
    // عرض ملاحظة واحدة
    // ===============================================================
    public function show(
        Request $request,
        StudentNote $studentNote
    ) {
        $teacher =
            $this->getAuthenticatedTeacher(
                $request
            );

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' =>
                    'هذا الحساب غير مرتبط بمعلم.',
            ], 403);
        }

        // -----------------------------------------------------------
        // المعلم لا يستطيع رؤية ملاحظة تخص معلمًا آخر
        // -----------------------------------------------------------
        if (
            (int) $studentNote->teacher_id !==
            (int) $teacher->id
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بعرض هذه الملاحظة.',
            ], 403);
        }

        $studentNote->load([
            'student',
            'teacher',
            'subject',
        ]);

        return response()->json([
            'success' => true,
            'data' => $studentNote,
        ]);
    }

    // ===============================================================
    // تعديل ملاحظة
    // ===============================================================
    public function update(
        Request $request,
        StudentNote $studentNote
    ) {
        $teacher =
            $this->getAuthenticatedTeacher(
                $request
            );

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' =>
                    'هذا الحساب غير مرتبط بمعلم.',
            ], 403);
        }

        // -----------------------------------------------------------
        // لا يسمح بتعديل ملاحظة معلم آخر
        // -----------------------------------------------------------
        if (
            (int) $studentNote->teacher_id !==
            (int) $teacher->id
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بتعديل هذه الملاحظة.',
            ], 403);
        }

        $validated = $request->validate([
            'subject_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:subjects,id',
            ],

            'type' => [
                'sometimes',
                'required',
                'string',
                'in:behavior,positive,academic_warning',
            ],

            'note' => [
                'sometimes',
                'required',
                'string',
                'max:1000',
            ],

            'note_date' => [
                'sometimes',
                'required',
                'date',
            ],
        ]);

        // -----------------------------------------------------------
        // تحديد المادة الجديدة أو الحالية
        // -----------------------------------------------------------
        $subjectId =
            array_key_exists(
                'subject_id',
                $validated
            )
                ? (
                    $validated['subject_id'] !== null
                        ? (int) $validated['subject_id']
                        : null
                )
                : (
                    $studentNote->subject_id !== null
                        ? (int) $studentNote->subject_id
                        : null
                );

        // -----------------------------------------------------------
        // التأكد أن الطالب ما زال ضمن إسنادات المعلم
        // -----------------------------------------------------------
        if (
            !$this->teacherCanAccessStudent(
                $teacher->id,
                (int) $studentNote->student_id,
                $subjectId
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لم يعد هذا الطالب أو هذه المادة ضمن إسنادات المعلم الحالي.',
            ], 403);
        }

        if (isset($validated['note'])) {
            $validated['note'] =
                trim($validated['note']);
        }

        $studentNote->update(
            $validated
        );

        $studentNote->load([
            'student',
            'teacher',
            'subject',
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'تم تعديل الملاحظة بنجاح.',
            'data' => $studentNote,
        ]);
    }

    // ===============================================================
    // حذف ملاحظة
    // ===============================================================
    public function destroy(
        Request $request,
        StudentNote $studentNote
    ) {
        $teacher =
            $this->getAuthenticatedTeacher(
                $request
            );

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' =>
                    'هذا الحساب غير مرتبط بمعلم.',
            ], 403);
        }

        // -----------------------------------------------------------
        // لا يسمح بحذف ملاحظة معلم آخر
        // -----------------------------------------------------------
        if (
            (int) $studentNote->teacher_id !==
            (int) $teacher->id
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بحذف هذه الملاحظة.',
            ], 403);
        }

        $studentNote->delete();

        return response()->json([
            'success' => true,
            'message' =>
                'تم حذف الملاحظة بنجاح.',
        ]);
    }
}