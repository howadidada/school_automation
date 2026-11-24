<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GradeBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeBatchController extends Controller
{
    // ==========================================================
    // Helpers
    // ==========================================================

    private function error(string $message, int $status = 403)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    private function getTeacher(Request $request)
    {
        return DB::table('teachers')
            ->where('user_id', $request->user()->id)
            ->first();
    }

    private function teacherHasAssignment(
        int $teacherId,
        int $subjectId,
        int $sectionId
    ): bool {
        return DB::table('teacher_assignments')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $subjectId)
            ->where('section_id', $sectionId)
            ->exists();
    }

    // ==========================================================
    // عرض كشوف الدرجات
    // ==========================================================

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->hasPermission('grades.view')) {
            return $this->error(
                'ليس لديك صلاحية عرض الدرجات.'
            );
        }

        $query = GradeBatch::query();

        if ($user->hasRole('teacher')) {
            $teacher = $this->getTeacher($request);

            if (!$teacher) {
                return $this->error(
                    'هذا الحساب غير مرتبط بمعلم.'
                );
            }

            $query->where('teacher_id', $teacher->id);
        }

        $validated = $request->validate([
            'status' => [
                'sometimes',
                'string',
                'in:draft,submitted,approved,rejected',
            ],
            'subject_id' => [
                'sometimes',
                'integer',
                'exists:subjects,id',
            ],
            'section_id' => [
                'sometimes',
                'integer',
                'exists:sections,id',
            ],
            'evaluation_name' => [
                'sometimes',
                'string',
                'max:255',
            ],
        ]);

        foreach (
            [
                'status',
                'subject_id',
                'section_id',
                'evaluation_name',
            ] as $field
        ) {
            if (isset($validated[$field])) {
                $query->where($field, $validated[$field]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $query->latest()->paginate(20),
        ]);
    }

    // ==========================================================
    // عرض كشف واحد
    // ==========================================================

    public function show(Request $request, GradeBatch $gradeBatch)
    {
        $user = $request->user();

        if (!$user->hasPermission('grades.view')) {
            return $this->error(
                'ليس لديك صلاحية عرض الدرجات.'
            );
        }

        if ($user->hasRole('teacher')) {
            $teacher = $this->getTeacher($request);

            if (
                !$teacher ||
                (int) $gradeBatch->teacher_id !== (int) $teacher->id
            ) {
                return $this->error(
                    'لا يمكنك عرض كشف درجات لا يخصك.'
                );
            }
        }

        return response()->json([
            'success' => true,
            'data' => $gradeBatch,
        ]);
    }

    // ==========================================================
    // إنشاء كشف درجات
    // ==========================================================

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->hasPermission('grades.create')) {
            return $this->error(
                'ليس لديك صلاحية إنشاء كشف درجات.'
            );
        }

        $teacher = $this->getTeacher($request);

        if (!$teacher) {
            return $this->error(
                'هذا الحساب غير مرتبط بمعلم.'
            );
        }

        $validated = $request->validate([
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],
            'section_id' => [
                'required',
                'integer',
                'exists:sections,id',
            ],
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],
            'semester' => [
                'required',
                'string',
                'max:30',
            ],
            'evaluation_name' => [
                'required',
                'string',
                'max:255',
            ],
            'evaluation_period' => [
                'required',
                'string',
                'max:50',
            ],
            'max_score' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        $assigned = $this->teacherHasAssignment(
            (int) $teacher->id,
            (int) $validated['subject_id'],
            (int) $validated['section_id']
        );

        if (!$assigned) {
            return $this->error(
                'هذه المادة والشعبة غير مسندتين للمعلم الحالي.'
            );
        }

        // منع إنشاء كشف مكرر لنفس التقييم
        return DB::transaction(function () use (
            $validated,
            $teacher
        ) {
            $existing = GradeBatch::query()
                ->where('teacher_id', $teacher->id)
                ->where('subject_id', $validated['subject_id'])
                ->where('section_id', $validated['section_id'])
                ->where('academic_year', $validated['academic_year'])
                ->where('semester', $validated['semester'])
                ->where('evaluation_name', $validated['evaluation_name'])
                ->where('evaluation_period', $validated['evaluation_period'])
                ->first();

            if ($existing) {
                if (
                    (float) $existing->max_score !==
                    (float) $validated['max_score']
                ) {
                    return $this->error(
                        'يوجد كشف سابق لهذا التقييم بدرجة كلية مختلفة.',
                        422
                    );
                }

                return response()->json([
                    'success' => true,
                    'message' => 'تم العثور على كشف الدرجات الموجود.',
                    'data' => $existing,
                ]);
            }

            $batch = GradeBatch::create([
                ...$validated,
                'teacher_id' => $teacher->id,
                'status' => 'draft',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء كشف الدرجات بنجاح.',
                'data' => $batch,
            ], 201);
        });
    }

    // ==========================================================
    // إرسال الكشف للاعتماد
    // ==========================================================

    public function submit(Request $request, GradeBatch $gradeBatch)
    {
        $user = $request->user();

        if (!$user->hasPermission('grades.submit')) {
            return $this->error(
                'ليس لديك صلاحية إرسال الدرجات للاعتماد.'
            );
        }

        $teacher = $this->getTeacher($request);

        if (
            !$teacher ||
            (int) $gradeBatch->teacher_id !== (int) $teacher->id
        ) {
            return $this->error(
                'لا يمكنك إرسال كشف درجات لا يخصك.'
            );
        }

        return DB::transaction(function () use ($gradeBatch) {
            $batch = GradeBatch::query()
                ->whereKey($gradeBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array(
                $batch->status,
                ['draft', 'rejected'],
                true
            )) {
                return $this->error(
                    'لا يمكن إرسال هذا الكشف في حالته الحالية.',
                    422
                );
            }

            $hasGrades = DB::table('grades')
                ->where('grade_batch_id', $batch->id)
                ->exists();

            if (!$hasGrades) {
                return $this->error(
                    'لا يمكن إرسال كشف درجات فارغ.',
                    422
                );
            }

            $batch->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'rejection_reason' => null,
                'returned_at' => null,
                'approved_by' => null,
                'approved_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال كشف الدرجات للاعتماد.',
                'data' => $batch->fresh(),
            ]);
        });
    }

    // ==========================================================
    // اعتماد الكشف
    // ==========================================================

    public function approve(Request $request, GradeBatch $gradeBatch)
    {
        $user = $request->user();

        if (!$user->hasPermission('grades.approve')) {
            return $this->error(
                'ليس لديك صلاحية اعتماد الدرجات.'
            );
        }

        return DB::transaction(function () use (
            $gradeBatch,
            $user
        ) {
            $batch = GradeBatch::query()
                ->whereKey($gradeBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($batch->status !== 'submitted') {
                return $this->error(
                    'لا يمكن اعتماد كشف لم يتم إرساله.',
                    422
                );
            }

            $batch->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => null,
                'returned_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم اعتماد كشف الدرجات بنجاح.',
                'data' => $batch->fresh(),
            ]);
        });
    }

    // ==========================================================
    // رفض الكشف وإعادته للمعلم
    // ==========================================================

    public function reject(Request $request, GradeBatch $gradeBatch)
    {
        $user = $request->user();

        if (!$user->hasPermission('grades.reject')) {
            return $this->error(
                'ليس لديك صلاحية رفض الدرجات.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        return DB::transaction(function () use (
            $gradeBatch,
            $validated
        ) {
            $batch = GradeBatch::query()
                ->whereKey($gradeBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($batch->status !== 'submitted') {
                return $this->error(
                    'لا يمكن رفض كشف لم يتم إرساله.',
                    422
                );
            }

            $batch->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'returned_at' => now(),
                'approved_by' => null,
                'approved_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تمت إعادة الكشف للمعلم للتعديل.',
                'data' => $batch->fresh(),
            ]);
        });
    }
}