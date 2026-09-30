<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    // ===============================================================
    // تحديد مرحلة الشعبة من الفصل المرتبط بها
    // ===============================================================
    private function getSectionStage(Section $section): ?string
    {
        $section->loadMissing('schoolClass');

        if (!$section->schoolClass) {
            return null;
        }

        return $section->schoolClass->education_stage;
    }

    // ===============================================================
    // التأكد أن المعلم مسموح له بتدريس هذه الشعبة
    // حسب مرحلته التعليمية
    // ===============================================================
    private function teacherCanTeachSection(
        Teacher $teacher,
        Section $section
    ): bool {
        if (!in_array(
            $teacher->education_stage,
            [
                'primary',
                'middle',
                'secondary',
            ],
            true
        )) {
            return false;
        }

        $sectionStage =
            $this->getSectionStage($section);

        if (!$sectionStage) {
            return false;
        }

        return $teacher->education_stage ===
            $sectionStage;
    }

    // ===============================================================
    // اسم المرحلة بالعربي للرسائل
    // ===============================================================
    private function stageName(?string $stage): string
    {
        return match ($stage) {
            'primary' =>
                'المرحلة الابتدائية',

            'middle' =>
                'المرحلة المتوسطة / الإعدادية',

            'secondary' =>
                'المرحلة الثانوية',

            default =>
                'مرحلة غير محددة',
        };
    }

    // ===============================================================
    // عرض جميع إسنادات المعلمين
    // ===============================================================
    public function index()
    {
        $assignments =
            TeacherAssignment::with([
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
    // صفحة إضافة إسناد جديد
    // ===============================================================
    public function create()
    {
        $teachers =
            Teacher::with('user')
                ->orderBy('id')
                ->get();

        $subjects =
            Subject::orderBy('name')
                ->get();

        $sections =
            Section::with('schoolClass')
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
    // حفظ إسناد جديد
    // المعلم + المادة + الشعبة
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

        // -----------------------------------------------------------
        // جلب المعلم
        // -----------------------------------------------------------
        $teacher =
            Teacher::with('user')
                ->findOrFail(
                    $validated['teacher_id']
                );

        // -----------------------------------------------------------
        // جلب الشعبة والفصل
        // -----------------------------------------------------------
        $section =
            Section::with('schoolClass')
                ->findOrFail(
                    $validated['section_id']
                );

        // -----------------------------------------------------------
        // التأكد من تحديد مرحلة المعلم
        // -----------------------------------------------------------
        if (!$teacher->education_stage) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'يجب تحديد المرحلة التعليمية للمعلم أولاً.'
                );
        }

        // -----------------------------------------------------------
        // التأكد من صحة قيمة مرحلة المعلم
        // -----------------------------------------------------------
        if (!in_array(
            $teacher->education_stage,
            [
                'primary',
                'middle',
                'secondary',
            ],
            true
        )) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'المرحلة التعليمية الخاصة بالمعلم غير صحيحة.'
                );
        }

        // -----------------------------------------------------------
        // التأكد من وجود فصل مرتبط بالشعبة
        // -----------------------------------------------------------
        if (!$section->schoolClass) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'هذه الشعبة غير مرتبطة بفصل دراسي.'
                );
        }

        // -----------------------------------------------------------
        // التأكد من تحديد مرحلة الفصل
        // -----------------------------------------------------------
        if (!$section->schoolClass->education_stage) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'يجب تحديد المرحلة التعليمية للفصل أولاً.'
                );
        }

        // -----------------------------------------------------------
        // منع إسناد المعلم إلى مرحلة أخرى
        // -----------------------------------------------------------
        if (!$this->teacherCanTeachSection(
            $teacher,
            $section
        )) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'لا يمكن إسناد هذا المعلم إلى هذه الشعبة. ' .
                    'مرحلة المعلم هي ' .
                    $this->stageName(
                        $teacher->education_stage
                    ) .
                    '، بينما مرحلة الفصل هي ' .
                    $this->stageName(
                        $section->schoolClass
                            ->education_stage
                    ) .
                    '.'
                );
        }

        // -----------------------------------------------------------
        // منع تكرار:
        // المعلم + المادة + الشعبة
        // -----------------------------------------------------------
        $exists =
            TeacherAssignment::where(
                'teacher_id',
                $validated['teacher_id']
            )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'section_id',
                $validated['section_id']
            )
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'هذا المعلم مسند بالفعل إلى هذه المادة وهذه الشعبة.'
                );
        }

        // -----------------------------------------------------------
        // إنشاء الإسناد
        // -----------------------------------------------------------
        TeacherAssignment::create([
            'teacher_id' =>
                $validated['teacher_id'],

            'subject_id' =>
                $validated['subject_id'],

            'section_id' =>
                $validated['section_id'],
        ]);

        return redirect()
            ->route(
                'teacher-assignments.index'
            )
            ->with(
                'success',
                'تم إسناد المعلم إلى المادة والشعبة بنجاح.'
            );
    }

    // ===============================================================
    // صفحة تعديل الإسناد
    // ===============================================================
    public function edit(
        TeacherAssignment $teacherAssignment
    ) {
        $teachers =
            Teacher::with('user')
                ->orderBy('id')
                ->get();

        $subjects =
            Subject::orderBy('name')
                ->get();

        $sections =
            Section::with('schoolClass')
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
    // تحديث الإسناد
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

        // -----------------------------------------------------------
        // جلب المعلم
        // -----------------------------------------------------------
        $teacher =
            Teacher::with('user')
                ->findOrFail(
                    $validated['teacher_id']
                );

        // -----------------------------------------------------------
        // جلب الشعبة والفصل
        // -----------------------------------------------------------
        $section =
            Section::with('schoolClass')
                ->findOrFail(
                    $validated['section_id']
                );

        // -----------------------------------------------------------
        // التأكد من تحديد مرحلة المعلم
        // -----------------------------------------------------------
        if (!$teacher->education_stage) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'يجب تحديد المرحلة التعليمية للمعلم أولاً.'
                );
        }

        // -----------------------------------------------------------
        // التأكد من صحة قيمة مرحلة المعلم
        // -----------------------------------------------------------
        if (!in_array(
            $teacher->education_stage,
            [
                'primary',
                'middle',
                'secondary',
            ],
            true
        )) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'المرحلة التعليمية الخاصة بالمعلم غير صحيحة.'
                );
        }

        // -----------------------------------------------------------
        // التأكد من وجود الفصل
        // -----------------------------------------------------------
        if (!$section->schoolClass) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'هذه الشعبة غير مرتبطة بفصل دراسي.'
                );
        }

        // -----------------------------------------------------------
        // التأكد من تحديد مرحلة الفصل
        // -----------------------------------------------------------
        if (!$section->schoolClass->education_stage) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'يجب تحديد المرحلة التعليمية للفصل أولاً.'
                );
        }

        // -----------------------------------------------------------
        // منع نقل الإسناد إلى مرحلة مختلفة
        // -----------------------------------------------------------
        if (!$this->teacherCanTeachSection(
            $teacher,
            $section
        )) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'لا يمكن إسناد هذا المعلم إلى هذه الشعبة. ' .
                    'مرحلة المعلم هي ' .
                    $this->stageName(
                        $teacher->education_stage
                    ) .
                    '، بينما مرحلة الفصل هي ' .
                    $this->stageName(
                        $section->schoolClass
                            ->education_stage
                    ) .
                    '.'
                );
        }

        // -----------------------------------------------------------
        // منع التكرار مع تجاهل السجل الحالي
        // -----------------------------------------------------------
        $exists =
            TeacherAssignment::where(
                'teacher_id',
                $validated['teacher_id']
            )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'section_id',
                $validated['section_id']
            )
            ->where(
                'id',
                '!=',
                $teacherAssignment->id
            )
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'هذا المعلم مسند بالفعل إلى هذه المادة وهذه الشعبة.'
                );
        }

        // -----------------------------------------------------------
        // تحديث الإسناد
        // -----------------------------------------------------------
        $teacherAssignment->update([
            'teacher_id' =>
                $validated['teacher_id'],

            'subject_id' =>
                $validated['subject_id'],

            'section_id' =>
                $validated['section_id'],
        ]);

        return redirect()
            ->route(
                'teacher-assignments.index'
            )
            ->with(
                'success',
                'تم تعديل إسناد المعلم بنجاح.'
            );
    }

    // ===============================================================
    // حذف الإسناد
    // ===============================================================
    public function destroy(
        TeacherAssignment $teacherAssignment
    ) {
        $teacherAssignment->delete();

        return redirect()
            ->route(
                'teacher-assignments.index'
            )
            ->with(
                'success',
                'تم حذف إسناد المعلم بنجاح.'
            );
    }
}