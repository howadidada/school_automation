<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeacherAssignmentController extends Controller
{
    // ===============================================================
    // تحديد مرحلة الشعبة من الصف المرتبط بها
    // ===============================================================
    private function getSectionStage(Section $section): ?string
    {
        $section->loadMissing('schoolClass');

        return $section->schoolClass?->education_stage;
    }

    // ===============================================================
    // التأكد من توافق مرحلة المعلم مع مرحلة الشعبة
    // ===============================================================
    private function teacherCanTeachSection(
        Teacher $teacher,
        Section $section
    ): bool {
        $allowedStages = [
            'primary',
            'middle',
            'secondary',
        ];

        if (!in_array(
            $teacher->education_stage,
            $allowedStages,
            true
        )) {
            return false;
        }

        $sectionStage = $this->getSectionStage($section);

        return $sectionStage !== null
            && $teacher->education_stage === $sectionStage;
    }

    // ===============================================================
    // اسم المرحلة بالعربي
    // ===============================================================
    private function stageName(?string $stage): string
    {
        return match ($stage) {
            'primary' => 'المرحلة الابتدائية',
            'middle' => 'المرحلة المتوسطة / الإعدادية',
            'secondary' => 'المرحلة الثانوية',
            default => 'مرحلة غير محددة',
        };
    }

    // ===============================================================
    // التحقق من صحة بيانات التوزيع
    // ===============================================================
    private function validateAssignment(
        Teacher $teacher,
        Section $section,
        int $subjectId,
        ?int $ignoreAssignmentId = null
    ): void {

        // -----------------------------------------------------------
        // يجب أن تكون للمعلم مادة أساسية محددة
        // -----------------------------------------------------------
        if (!$teacher->subject_id) {
            throw ValidationException::withMessages([
                'subject_id' =>
                    'يجب تحديد مادة المعلم الأساسية من صفحة إدارة المعلمين أولًا.',
            ]);
        }

        // -----------------------------------------------------------
        // منع اختيار مادة مختلفة عن مادة المعلم
        // -----------------------------------------------------------
        if ((int) $teacher->subject_id !== $subjectId) {
            throw ValidationException::withMessages([
                'subject_id' =>
                    'لا يمكن توزيع المعلم على مادة مختلفة عن مادته الأساسية. '
                    . 'يرجى اختيار المادة المحددة له في إدارة المعلمين.',
            ]);
        }

        // -----------------------------------------------------------
        // التأكد من وجود مرحلة تعليمية للمعلم
        // -----------------------------------------------------------
        if (!in_array(
            $teacher->education_stage,
            ['primary', 'middle', 'secondary'],
            true
        )) {
            throw ValidationException::withMessages([
                'teacher_id' =>
                    'يجب تحديد مرحلة تعليمية صحيحة للمعلم أولًا.',
            ]);
        }

        // -----------------------------------------------------------
        // التأكد من ارتباط الشعبة بصف دراسي
        // -----------------------------------------------------------
        if (!$section->schoolClass) {
            throw ValidationException::withMessages([
                'section_id' =>
                    'هذه الشعبة غير مرتبطة بصف دراسي.',
            ]);
        }

        // -----------------------------------------------------------
        // التأكد من تحديد مرحلة الصف
        // -----------------------------------------------------------
        if (!$section->schoolClass->education_stage) {
            throw ValidationException::withMessages([
                'section_id' =>
                    'يجب تحديد المرحلة التعليمية للصف أولًا.',
            ]);
        }

        // -----------------------------------------------------------
        // منع توزيع المعلم على مرحلة تعليمية مختلفة
        // -----------------------------------------------------------
        if (!$this->teacherCanTeachSection(
            $teacher,
            $section
        )) {
            throw ValidationException::withMessages([
                'section_id' =>
                    'لا يمكن توزيع هذا المعلم على هذه الشعبة. '
                    . 'مرحلة المعلم هي '
                    . $this->stageName($teacher->education_stage)
                    . '، بينما مرحلة الصف هي '
                    . $this->stageName(
                        $section->schoolClass->education_stage
                    )
                    . '.',
            ]);
        }

        // -----------------------------------------------------------
        // منع تكرار المعلم في الشعبة نفسها
        // حتى لو اختلفت المادة
        // -----------------------------------------------------------
        $query = TeacherAssignment::query()
            ->where('teacher_id', $teacher->id)
            ->where('section_id', $section->id);

        if ($ignoreAssignmentId !== null) {
            $query->where('id', '!=', $ignoreAssignmentId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'section_id' =>
                    'هذا المعلم موزع بالفعل على هذه الشعبة. '
                    . 'لا يمكن تكرار التوزيع.',
            ]);
        }
    }

    // ===============================================================
    // عرض جميع توزيعات المعلمين
    // ===============================================================
    public function index()
    {
        $assignments = TeacherAssignment::with([
            'teacher.user',
            'subject',
            'section.schoolClass',
        ])
            ->latest()
            ->get();

        return view(
            'teacher_assignments.index',
            compact('assignments')
        );
    }

    // ===============================================================
    // صفحة إضافة توزيع جديد
    // ===============================================================
    public function create()
    {
        $teachers = Teacher::with([
            'user',
            'subject',
        ])
            ->orderBy('id')
            ->get();

        $subjects = Subject::orderBy('name')->get();

        $sections = Section::with('schoolClass')
            ->orderBy('name')
            ->get();

        return view(
            'teacher_assignments.create',
            compact(
                'teachers',
                'subjects',
                'sections'
            )
        );
    }

    // ===============================================================
    // حفظ توزيع جديد
    // ===============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'integer',
                'exists:teachers,id',
            ],

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
        ]);

        DB::transaction(function () use ($validated) {

            // قفل سجل المعلم أثناء إنشاء التوزيع
            $teacher = Teacher::query()
                ->lockForUpdate()
                ->findOrFail($validated['teacher_id']);

            $section = Section::with('schoolClass')
                ->findOrFail($validated['section_id']);

            $this->validateAssignment(
                $teacher,
                $section,
                (int) $validated['subject_id']
            );

            TeacherAssignment::create([
                'teacher_id' => $teacher->id,
                'subject_id' => $teacher->subject_id,
                'section_id' => $section->id,
            ]);
        });

        return redirect()
            ->route('teacher-assignments.index')
            ->with(
                'success',
                'تم توزيع المعلم على الشعبة بنجاح.'
            );
    }

    // ===============================================================
    // صفحة تعديل التوزيع
    // ===============================================================
    public function edit(
        TeacherAssignment $teacherAssignment
    ) {
        $teacherAssignment->load([
            'teacher.user',
            'teacher.subject',
            'subject',
            'section.schoolClass',
        ]);

        $teachers = Teacher::with([
            'user',
            'subject',
        ])
            ->orderBy('id')
            ->get();

        $subjects = Subject::orderBy('name')->get();

        $sections = Section::with('schoolClass')
            ->orderBy('name')
            ->get();

        return view(
            'teacher_assignments.edit',
            compact(
                'teacherAssignment',
                'teachers',
                'subjects',
                'sections'
            )
        );
    }

    // ===============================================================
    // تحديث التوزيع
    // ===============================================================
    public function update(
        Request $request,
        TeacherAssignment $teacherAssignment
    ) {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'integer',
                'exists:teachers,id',
            ],

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
        ]);

        DB::transaction(function () use (
            $validated,
            $teacherAssignment
        ) {
            $teacher = Teacher::query()
                ->lockForUpdate()
                ->findOrFail($validated['teacher_id']);

            $section = Section::with('schoolClass')
                ->findOrFail($validated['section_id']);

            $this->validateAssignment(
                $teacher,
                $section,
                (int) $validated['subject_id'],
                $teacherAssignment->id
            );

            $teacherAssignment->update([
                'teacher_id' => $teacher->id,
                'subject_id' => $teacher->subject_id,
                'section_id' => $section->id,
            ]);
        });

        return redirect()
            ->route('teacher-assignments.index')
            ->with(
                'success',
                'تم تعديل توزيع المعلم بنجاح.'
            );
    }

    // ===============================================================
    // حذف التوزيع
    // ===============================================================
    public function destroy(
        TeacherAssignment $teacherAssignment
    ) {
        $teacherAssignment->delete();

        return redirect()
            ->route('teacher-assignments.index')
            ->with(
                'success',
                'تم حذف توزيع المعلم بنجاح.'
            );
    }
}