<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
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
    // التحقق أن المادة مسندة للمعلم
    // ===============================================================
    private function teacherHasSubject(
        int $teacherId,
        int $subjectId
    ): bool {
        return DB::table('teacher_assignments')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $subjectId)
            ->exists();
    }

    // ===============================================================
    // التحقق أن الطالب ضمن شعبة مسندة للمعلم
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
    // جلب الدرجات
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

        $query = Grade::with([
            'student',
            'teacher',
            'subject',
        ])
            ->where(
                'teacher_id',
                $teacher->id
            );

        $subjectId = null;

        if ($request->filled('subject_id')) {
            $subjectId =
                (int) $request->subject_id;

            if (
                !$this->teacherHasSubject(
                    $teacher->id,
                    $subjectId
                )
            ) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'هذه المادة غير مسندة للمعلم الحالي.',
                ], 403);
            }

            $query->where(
                'subject_id',
                $subjectId
            );
        }

        if ($request->filled('student_id')) {
            $studentId =
                (int) $request->student_id;

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
                        'غير مصرح لك بالوصول إلى درجات هذا الطالب.',
                ], 403);
            }

            $query->where(
                'student_id',
                $studentId
            );
        }

        $grades = $query
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' =>
                'تم جلب الدرجات بنجاح.',
            'data' => $grades,
        ]);
    }

    // ===============================================================
    // حفظ درجة جديدة أو تحديث الموجودة
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

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],

            'evaluation_name' => [
                'required',
                'string',
                'max:255',
            ],

            'score' => [
                'required',
                'numeric',
                'min:0',
            ],

            'max_score' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ]);

        $studentId =
            (int) $validated['student_id'];

        $subjectId =
            (int) $validated['subject_id'];

        if (
            !$this->teacherHasSubject(
                $teacher->id,
                $subjectId
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'هذه المادة غير مسندة للمعلم الحالي.',
            ], 403);
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
                    'غير مصرح لك بإدخال درجات لهذا الطالب في هذه المادة.',
            ], 403);
        }

        if (
            (float) $validated['score'] >
            (float) $validated['max_score']
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الدرجة لا يمكن أن تكون أكبر من الدرجة الكلية.',
            ], 422);
        }

        // ===========================================================
        // إنشاء الدرجة أو تحديث نفس الدرجة
        // ===========================================================
        $grade = Grade::updateOrCreate(
            [
                'student_id' =>
                    $studentId,

                'teacher_id' =>
                    $teacher->id,

                'subject_id' =>
                    $subjectId,

                'evaluation_name' =>
                    $validated['evaluation_name'],
            ],
            [
                'score' =>
                    $validated['score'],

                'max_score' =>
                    $validated['max_score'],

                'note' =>
                    $validated['note'] ?? null,
            ]
        );

        $grade->load([
            'student',
            'teacher',
            'subject',
        ]);

        // ===========================================================
        // إنشاء إشعار تلقائي للمعلم
        // ===========================================================
        Notification::create([
            'user_id' =>
                $teacher->user_id,

            'type' =>
                'grade',

            'title' =>
                'تحديث الدرجات',

            'body' =>
                'تم حفظ درجات الطلاب بنجاح.',

            'is_read' =>
                false,

            'reference_id' =>
                $grade->id,

            'reference_type' =>
                'grade',
        ]);

        return response()->json([
            'success' => true,

            'message' =>
                'تم حفظ الدرجة بنجاح.',

            'data' => $grade,
        ], $grade->wasRecentlyCreated ? 201 : 200);
    }

    // ===============================================================
    // عرض درجة واحدة
    // ===============================================================
    public function show(
        Request $request,
        Grade $grade
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

        if (
            (int) $grade->teacher_id !==
            (int) $teacher->id
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بعرض هذه الدرجة.',
            ], 403);
        }

        $grade->load([
            'student',
            'teacher',
            'subject',
        ]);

        return response()->json([
            'success' => true,
            'data' => $grade,
        ]);
    }

    // ===============================================================
    // تعديل درجة
    // ===============================================================
    public function update(
        Request $request,
        Grade $grade
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

        if (
            (int) $grade->teacher_id !==
            (int) $teacher->id
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بتعديل هذه الدرجة.',
            ], 403);
        }

        if (
            !$this->teacherCanAccessStudent(
                $teacher->id,
                (int) $grade->student_id,
                (int) $grade->subject_id
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لم تعد هذه المادة والشعبة مسندة للمعلم الحالي.',
            ], 403);
        }

        $validated = $request->validate([
            'evaluation_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'score' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
            ],

            'max_score' => [
                'sometimes',
                'required',
                'numeric',
                'gt:0',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ]);

        $newScore =
            $validated['score'] ??
            $grade->score;

        $newMaxScore =
            $validated['max_score'] ??
            $grade->max_score;

        if (
            (float) $newScore >
            (float) $newMaxScore
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'الدرجة لا يمكن أن تكون أكبر من الدرجة الكلية.',
            ], 422);
        }

        // ===========================================================
        // تحديث الدرجة
        // ===========================================================
        $grade->update(
            $validated
        );

        $grade->load([
            'student',
            'teacher',
            'subject',
        ]);

        // ===========================================================
        // إشعار تلقائي بعد تعديل الدرجة
        // ===========================================================
        Notification::create([
            'user_id' =>
                $teacher->user_id,

            'type' =>
                'grade',

            'title' =>
                'تحديث الدرجات',

            'body' =>
                'تم تعديل الدرجة بنجاح.',

            'is_read' =>
                false,

            'reference_id' =>
                $grade->id,

            'reference_type' =>
                'grade',
        ]);

        return response()->json([
            'success' => true,

            'message' =>
                'تم تعديل الدرجة بنجاح.',

            'data' => $grade,
        ]);
    }

    // ===============================================================
    // حذف درجة
    // ===============================================================
    public function destroy(
        Request $request,
        Grade $grade
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

        if (
            (int) $grade->teacher_id !==
            (int) $teacher->id
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بحذف هذه الدرجة.',
            ], 403);
        }

        if (
            !$this->teacherCanAccessStudent(
                $teacher->id,
                (int) $grade->student_id,
                (int) $grade->subject_id
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'لم تعد هذه المادة والشعبة مسندة للمعلم الحالي.',
            ], 403);
        }

        $grade->delete();

        return response()->json([
            'success' => true,

            'message' =>
                'تم حذف الدرجة بنجاح.',
        ]);
    }
}