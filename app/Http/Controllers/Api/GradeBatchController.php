<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\GradeBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        } else {
            return $this->error(
                'عرض كشوف الدرجات لهذا الدور غير متاح حاليًا.'
            );
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
            'evaluation_period' => [
                'sometimes',
                'string',
                'max:50',
            ],
            'academic_year' => [
                'sometimes',
                'string',
                'max:20',
            ],
            'semester' => [
                'sometimes',
                'string',
                'max:30',
            ],
        ]);

        foreach ($validated as $field => $value) {
            $query->where($field, $value);
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

        if (!$user->hasRole('teacher')) {
            return $this->error(
                'عرض كشف الدرجات لهذا الدور غير متاح حاليًا.'
            );
        }

        $teacher = $this->getTeacher($request);

        if (
            !$teacher ||
            (int) $gradeBatch->teacher_id !== (int) $teacher->id
        ) {
            return $this->error(
                'لا يمكنك عرض كشف درجات لا يخصك.'
            );
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

        if (
            !$user->hasRole('teacher') ||
            !$user->hasPermission('grades.create')
        ) {
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

        $setting = AcademicSetting::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();

        if (!$setting) {
            return $this->error(
                'لم يحدد مدير النظام الفصل الدراسي النشط.',
                422
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

            'evaluation_name' => [
                'required',
                'string',
                'max:255',
            ],

            'evaluation_period' => [
                'required',
                'string',
                'max:50',
                Rule::in(
                    $setting->evaluation_periods ?? []
                ),
            ],

            'max_score' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        // العام والفصل الدراسي من إعدادات مدير النظام

        $validated['academic_year'] =
            $setting->academic_year;

        $validated['semester'] =
            $setting->semester;

        // التحقق من إسناد المادة والشعبة

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

        return DB::transaction(function () use (
            $validated,
            $teacher
        ) {
            // منع إنشاء كشوف مكررة بالتزامن

            DB::table('teachers')
                ->where('id', $teacher->id)
                ->lockForUpdate()
                ->first();

            // البحث عن كشف سابق لنفس التقييم

            $existing = GradeBatch::query()
                ->where('teacher_id', $teacher->id)
                ->where('subject_id', $validated['subject_id'])
                ->where('section_id', $validated['section_id'])
                ->where('academic_year', $validated['academic_year'])
                ->where('semester', $validated['semester'])
                ->where('evaluation_period', $validated['evaluation_period'])
                ->where('evaluation_name', $validated['evaluation_name'])
                ->lockForUpdate()
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

                if (!in_array(
                    $existing->status,
                    ['draft', 'rejected'],
                    true
                )) {
                    return $this->error(
                        'تم إرسال هذا الكشف للمراجعة أو اعتماده، ولا يمكن تعديله.',
                        422
                    );
                }

                return response()->json([
                    'success' => true,
                    'message' => 'تم العثور على كشف الدرجات الموجود.',
                    'data' => $existing,
                ]);
            }

            // إنشاء كشف جديد كمسودة

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
    // إرسال كشف الدرجات للمشرف للمراجعة
    // ==========================================================

    public function submit(
        Request $request,
        GradeBatch $gradeBatch
    ) {
        $user = $request->user();

        if (
            !$user ||
            !$user->hasRole('teacher') ||
            !$user->hasPermission('grades.submit')
        ) {
            return $this->error(
                'ليس لديك صلاحية إرسال الدرجات للمراجعة.'
            );
        }

        $teacher = $this->getTeacher($request);

        if (!$teacher) {
            return $this->error(
                'هذا الحساب غير مرتبط بمعلم.'
            );
        }

        return DB::transaction(function () use (
            $gradeBatch,
            $teacher
        ) {
            $batch = GradeBatch::query()
                ->whereKey($gradeBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            // التحقق من ملكية الكشف

            if (
                (int) $batch->teacher_id !==
                (int) $teacher->id
            ) {
                return $this->error(
                    'لا يمكنك إرسال كشف درجات لا يخصك.'
                );
            }

            // التحقق من إسناد المادة والشعبة

            if (!$this->teacherHasAssignment(
                (int) $teacher->id,
                (int) $batch->subject_id,
                (int) $batch->section_id
            )) {
                return $this->error(
                    'هذه المادة والشعبة غير مسندتين للمعلم.'
                );
            }

            // التحقق من حالة الكشف

            if (!in_array(
                $batch->status,
                ['draft', 'rejected'],
                true
            )) {
                return $this->error(
                    'لا يمكن إرسال الكشف في حالته الحالية.',
                    422
                );
            }

            // جلب طلاب الشعبة

            $studentIds = DB::table('students')
                ->where('section_id', $batch->section_id)
                ->pluck('id');

            if ($studentIds->isEmpty()) {
                return $this->error(
                    'لا يوجد طلاب في هذه الشعبة.',
                    422
                );
            }

            // جلب الدرجات المحفوظة

            $grades = DB::table('grades')
                ->where('grade_batch_id', $batch->id)
                ->get();

            if ($grades->isEmpty()) {
                return $this->error(
                    'يجب حفظ الدرجات قبل إرسال الكشف.',
                    422
                );
            }

            $gradedStudentIds = $grades
                ->pluck('student_id');

            // التأكد من استكمال درجات جميع الطلاب

            if (
                $studentIds
                    ->diff($gradedStudentIds)
                    ->isNotEmpty()
            ) {
                return $this->error(
                    'يجب استكمال درجات جميع الطلاب قبل الإرسال.',
                    422
                );
            }

            // منع إرسال درجات مكررة

            if (
                $gradedStudentIds->count() !==
                $gradedStudentIds->unique()->count()
            ) {
                return $this->error(
                    'توجد درجات مكررة داخل الكشف.',
                    422
                );
            }

            // التحقق من صحة الدرجات

            foreach ($grades as $grade) {

                if (
                    !$studentIds->contains($grade->student_id) ||

                    (int) $grade->teacher_id !==
                    (int) $batch->teacher_id ||

                    (int) $grade->subject_id !==
                    (int) $batch->subject_id ||

                    $grade->evaluation_name !==
                    $batch->evaluation_name ||

                    $grade->score === null ||

                    (float) $grade->score < 0 ||

                    (float) $grade->score >
                    (float) $batch->max_score ||

                    (float) $grade->max_score !==
                    (float) $batch->max_score
                ) {
                    return $this->error(
                        'توجد درجات غير صحيحة داخل الكشف.',
                        422
                    );
                }
            }

            // إرسال الكشف وإغلاق إمكانية التعديل

            $batch->update([
                'status' => 'submitted',
                'submitted_at' => now(),

                'supervisor_reviewed_by' => null,
                'supervisor_reviewed_at' => null,

                'approved_by' => null,
                'approved_at' => null,

                'rejection_reason' => null,
                'returned_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال كشف الدرجات للمشرف للمراجعة بنجاح.',
                'data' => $batch->fresh(),
            ]);
        });
    }

    // ==========================================================
    // الاعتماد النهائي - غير مفعل حتى استكمال صلاحيات الوكيل
    // ==========================================================

    public function approve(
        Request $request,
        GradeBatch $gradeBatch
    ) {
        return $this->error(
            'لم يتم تفعيل وظيفة الاعتماد النهائي بعد.',
            501
        );
    }

    // ==========================================================
    // إعادة الكشف - غير مفعلة حتى استكمال صلاحيات المراجعة
    // ==========================================================

    public function reject(
        Request $request,
        GradeBatch $gradeBatch
    ) {
        return $this->error(
            'لم يتم تفعيل وظيفة إعادة الكشف بعد.',
            501
        );
    }
}