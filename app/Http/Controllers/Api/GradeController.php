<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\GradeBatch;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    private function error(string $message, int $status = 403)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    private function getAuthenticatedTeacher(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->hasRole('teacher')) {
            return null;
        }

        return DB::table('teachers')
            ->where('user_id', $user->id)
            ->first();
    }

    private function teacherHasSubject(
        int $teacherId,
        int $subjectId
    ): bool {
        return DB::table('teacher_assignments')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $subjectId)
            ->exists();
    }

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
            ->where('section_id', $student->section_id);

        if ($subjectId !== null) {
            $query->where('subject_id', $subjectId);
        }

        return $query->exists();
    }

    private function canEditBatch(GradeBatch $batch): bool
    {
        return in_array(
            $batch->status,
            ['draft', 'rejected'],
            true
        );
    }

    private function batchLockMessage(GradeBatch $batch): string
    {
        if ($batch->status === 'approved') {
            return 'لا يمكن تعديل أو حذف درجات كشف تم اعتماده من الوكيل.';
        }

        if ($batch->status === 'submitted') {
            return 'الكشف مرسل للمراجعة، ولا يمكن تعديل درجاته حتى تتم إعادته للتعديل.';
        }

        return 'لا يمكن تعديل درجات الكشف في حالته الحالية.';
    }

    private function notifyTeacher(
        int $userId,
        int $gradeId,
        string $message
    ): void {
        Notification::create([
            'user_id' => $userId,
            'type' => 'grade',
            'title' => 'تحديث الدرجات',
            'body' => $message,
            'is_read' => false,
            'reference_id' => $gradeId,
            'reference_type' => 'grade',
        ]);
    }

    // عرض الدرجات

    public function index(Request $request)
    {
        $teacher = $this->getAuthenticatedTeacher($request);

        if (!$teacher) {
            return $this->error(
                'هذا الحساب غير مرتبط بمعلم.'
            );
        }

        if (!$request->user()->hasPermission('grades.view')) {
            return $this->error(
                'ليس لديك صلاحية عرض الدرجات.'
            );
        }

        $query = Grade::with([
            'student',
            'teacher',
            'subject',
        ])->where('teacher_id', $teacher->id);

        $subjectId = null;

        if ($request->filled('subject_id')) {

            $subjectId = (int) $request->subject_id;

            if (!$this->teacherHasSubject(
                $teacher->id,
                $subjectId
            )) {
                return $this->error(
                    'هذه المادة غير مسندة للمعلم الحالي.'
                );
            }

            $query->where('subject_id', $subjectId);
        }

        if ($request->filled('student_id')) {

            $studentId = (int) $request->student_id;

            if (!$this->teacherCanAccessStudent(
                $teacher->id,
                $studentId,
                $subjectId
            )) {
                return $this->error(
                    'غير مصرح لك بالوصول إلى درجات هذا الطالب.'
                );
            }

            $query->where('student_id', $studentId);
        }

        if ($request->filled('grade_batch_id')) {

            $batch = GradeBatch::query()
                ->whereKey((int) $request->grade_batch_id)
                ->where('teacher_id', $teacher->id)
                ->first();

            if (!$batch) {
                return $this->error(
                    'لا يمكنك الوصول إلى هذا الكشف.'
                );
            }

            $query->where('grade_batch_id', $batch->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الدرجات بنجاح.',
            'data' => $query->latest()->get(),
        ]);
    }

    // إنشاء أو تحديث درجة

    public function store(Request $request)
    {
        $teacher = $this->getAuthenticatedTeacher($request);

        if (!$teacher) {
            return $this->error(
                'هذا الحساب غير مرتبط بمعلم.'
            );
        }

        if (!$request->user()->hasPermission('grades.create')) {
            return $this->error(
                'ليس لديك صلاحية إدخال الدرجات.'
            );
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
            'grade_batch_id' => [
                'required',
                'integer',
                'exists:grade_batches,id',
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

        $studentId = (int) $validated['student_id'];
        $subjectId = (int) $validated['subject_id'];
        $batchId = (int) $validated['grade_batch_id'];

        if (!$this->teacherCanAccessStudent(
            $teacher->id,
            $studentId,
            $subjectId
        )) {
            return $this->error(
                'غير مصرح لك بإدخال درجات لهذا الطالب في هذه المادة.'
            );
        }

        return DB::transaction(function () use (
            $teacher,
            $validated,
            $studentId,
            $subjectId,
            $batchId
        ) {

            $batch = GradeBatch::query()
                ->whereKey($batchId)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                (int) $batch->teacher_id !== (int) $teacher->id ||
                (int) $batch->subject_id !== $subjectId
            ) {
                return $this->error(
                    'هذا الكشف لا يخص المعلم أو المادة المحددة.'
                );
            }

            $student = DB::table('students')
                ->where('id', $studentId)
                ->first();

            if (
                !$student ||
                (int) $student->section_id !== (int) $batch->section_id
            ) {
                return $this->error(
                    'الطالب غير مسجل في شعبة هذا الكشف.'
                );
            }

            if (!$this->canEditBatch($batch)) {
                return $this->error(
                    $this->batchLockMessage($batch),
                    423
                );
            }

            if (
                $validated['evaluation_name'] !==
                $batch->evaluation_name
            ) {
                return $this->error(
                    'اسم التقييم لا يطابق كشف الدرجات.',
                    422
                );
            }

            if (
                (float) $validated['max_score'] !==
                (float) $batch->max_score
            ) {
                return $this->error(
                    'الدرجة الكلية لا تطابق كشف الدرجات.',
                    422
                );
            }

            if (
                (float) $validated['score'] >
                (float) $batch->max_score
            ) {
                return $this->error(
                    'الدرجة لا يمكن أن تكون أكبر من الدرجة الكلية.',
                    422
                );
            }

            // البحث باستخدام رقم الكشف لمنع تداخل الفترات

            $existing = Grade::query()
                ->where('student_id', $studentId)
                ->where('teacher_id', $teacher->id)
                ->where('subject_id', $subjectId)
                ->where('grade_batch_id', $batchId)
                ->lockForUpdate()
                ->first();

            if ($existing) {

                $existing->update([
                    'score' => $validated['score'],
                    'max_score' => $batch->max_score,
                    'evaluation_name' => $batch->evaluation_name,
                    'note' => $validated['note'] ?? null,
                ]);

                $grade = $existing;
                $created = false;

            } else {

                $grade = Grade::create([
                    'student_id' => $studentId,
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subjectId,
                    'grade_batch_id' => $batchId,
                    'evaluation_name' => $batch->evaluation_name,
                    'score' => $validated['score'],
                    'max_score' => $batch->max_score,
                    'note' => $validated['note'] ?? null,
                ]);

                $created = true;
            }

            $this->notifyTeacher(
                (int) $teacher->user_id,
                (int) $grade->id,
                'تم حفظ درجات الطلاب بنجاح.'
            );

            $grade->load([
                'student',
                'teacher',
                'subject',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الدرجة بنجاح.',
                'data' => $grade,
            ], $created ? 201 : 200);
        });
    }

    // عرض درجة واحدة

    public function show(Request $request, Grade $grade)
    {
        $teacher = $this->getAuthenticatedTeacher($request);

        if (!$teacher) {
            return $this->error(
                'هذا الحساب غير مرتبط بمعلم.'
            );
        }

        if (!$request->user()->hasPermission('grades.view')) {
            return $this->error(
                'ليس لديك صلاحية عرض الدرجات.'
            );
        }

        if ((int) $grade->teacher_id !== (int) $teacher->id) {
            return $this->error(
                'غير مصرح لك بعرض هذه الدرجة.'
            );
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

    // تعديل درجة

    public function update(Request $request, Grade $grade)
    {
        $teacher = $this->getAuthenticatedTeacher($request);

        if (!$teacher) {
            return $this->error(
                'هذا الحساب غير مرتبط بمعلم.'
            );
        }

        if (!$request->user()->hasPermission('grades.create')) {
            return $this->error(
                'ليس لديك صلاحية تعديل الدرجات.'
            );
        }

        if ((int) $grade->teacher_id !== (int) $teacher->id) {
            return $this->error(
                'غير مصرح لك بتعديل هذه الدرجة.'
            );
        }

        if (!$this->teacherCanAccessStudent(
            $teacher->id,
            (int) $grade->student_id,
            (int) $grade->subject_id
        )) {
            return $this->error(
                'لم تعد هذه المادة والشعبة مسندة للمعلم الحالي.'
            );
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

        return DB::transaction(function () use (
            $grade,
            $teacher,
            $validated
        ) {

            if ($grade->grade_batch_id === null) {
                return $this->error(
                    'هذه الدرجة غير مرتبطة بكشف درجات.',
                    422
                );
            }

            $batch = GradeBatch::query()
                ->whereKey($grade->grade_batch_id)
                ->lockForUpdate()
                ->first();

            if (!$batch) {
                return $this->error(
                    'لم يتم العثور على كشف الدرجات المرتبط.',
                    422
                );
            }

            $current = Grade::query()
                ->whereKey($grade->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                (int) $batch->teacher_id !== (int) $teacher->id ||
                (int) $current->teacher_id !== (int) $teacher->id ||
                (int) $current->grade_batch_id !== (int) $batch->id
            ) {
                return $this->error(
                    'غير مصرح لك بتعديل هذه الدرجة.'
                );
            }

            if (!$this->canEditBatch($batch)) {
                return $this->error(
                    $this->batchLockMessage($batch),
                    423
                );
            }

            $newScore =
                $validated['score'] ?? $current->score;

            if ((float) $newScore > (float) $batch->max_score) {
                return $this->error(
                    'الدرجة لا يمكن أن تكون أكبر من الدرجة الكلية.',
                    422
                );
            }

            if (
                isset($validated['max_score']) &&
                (float) $validated['max_score'] !==
                (float) $batch->max_score
            ) {
                return $this->error(
                    'لا يمكن تغيير الدرجة الكلية للكشف من درجة طالب.',
                    422
                );
            }

            if (
                isset($validated['evaluation_name']) &&
                $validated['evaluation_name'] !==
                $batch->evaluation_name
            ) {
                return $this->error(
                    'لا يمكن تغيير اسم تقييم الكشف من درجة طالب.',
                    422
                );
            }

            $current->update([
                ...$validated,
                'max_score' => $batch->max_score,
                'evaluation_name' => $batch->evaluation_name,
            ]);

            $this->notifyTeacher(
                (int) $teacher->user_id,
                (int) $current->id,
                'تم تعديل الدرجة بنجاح.'
            );

            $current->load([
                'student',
                'teacher',
                'subject',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تعديل الدرجة بنجاح.',
                'data' => $current,
            ]);
        });
    }

    // حذف درجة

    public function destroy(Request $request, Grade $grade)
    {
        $teacher = $this->getAuthenticatedTeacher($request);

        if (!$teacher) {
            return $this->error(
                'هذا الحساب غير مرتبط بمعلم.'
            );
        }

        if (!$request->user()->hasPermission('grades.create')) {
            return $this->error(
                'ليس لديك صلاحية حذف الدرجات.'
            );
        }

        if ((int) $grade->teacher_id !== (int) $teacher->id) {
            return $this->error(
                'غير مصرح لك بحذف هذه الدرجة.'
            );
        }

        if (!$this->teacherCanAccessStudent(
            $teacher->id,
            (int) $grade->student_id,
            (int) $grade->subject_id
        )) {
            return $this->error(
                'لم تعد هذه المادة والشعبة مسندة للمعلم الحالي.'
            );
        }

        return DB::transaction(function () use (
            $grade,
            $teacher
        ) {

            if ($grade->grade_batch_id === null) {
                return $this->error(
                    'هذه الدرجة غير مرتبطة بكشف درجات.',
                    422
                );
            }

            $batch = GradeBatch::query()
                ->whereKey($grade->grade_batch_id)
                ->lockForUpdate()
                ->first();

            if (!$batch) {
                return $this->error(
                    'لم يتم العثور على كشف الدرجات المرتبط.',
                    422
                );
            }

            $current = Grade::query()
                ->whereKey($grade->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                (int) $batch->teacher_id !== (int) $teacher->id ||
                (int) $current->teacher_id !== (int) $teacher->id ||
                (int) $current->grade_batch_id !== (int) $batch->id
            ) {
                return $this->error(
                    'غير مصرح لك بحذف هذه الدرجة.'
                );
            }

            if (!$this->canEditBatch($batch)) {
                return $this->error(
                    $this->batchLockMessage($batch),
                    423
                );
            }

            $current->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الدرجة بنجاح.',
            ]);
        });
    }
}