<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Section;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    public function index()
    {
        $assignments = TeacherAssignment::with([
            'teacher.user',
            'teacher.subject',
            'section.schoolClass',
        ])->latest()->get();

        return view(
            'teacher_assignments.index',
            compact('assignments')
        );
    }

    public function create()
    {
        $teachers = Teacher::with([
            'user',
            'subject',
        ])->orderBy('id')->get();

        $sections = Section::with('schoolClass')
            ->orderBy('name')
            ->get();

        return view(
            'teacher_assignments.create',
            compact('teachers', 'sections')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],

            'section_id' => [
                'required',
                'exists:sections,id',
            ],
        ]);

        $exists = TeacherAssignment::where(
            'teacher_id',
            $validated['teacher_id']
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
                    'هذا المعلم مسند بالفعل إلى هذه الشعبة.'
                );
        }

        TeacherAssignment::create([
            'teacher_id' => $validated['teacher_id'],
            'section_id' => $validated['section_id'],
        ]);

        return redirect()
            ->route('teacher-assignments.index')
            ->with(
                'success',
                'تم إسناد المعلم إلى الشعبة بنجاح.'
            );
    }

    public function edit(TeacherAssignment $teacherAssignment)
    {
        $teachers = Teacher::with([
            'user',
            'subject',
        ])->orderBy('id')->get();

        $sections = Section::with('schoolClass')
            ->orderBy('name')
            ->get();

        return view(
            'teacher_assignments.edit',
            compact(
                'teacherAssignment',
                'teachers',
                'sections'
            )
        );
    }

    public function update(
        Request $request,
        TeacherAssignment $teacherAssignment
    ) {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],

            'section_id' => [
                'required',
                'exists:sections,id',
            ],
        ]);

        $exists = TeacherAssignment::where(
            'teacher_id',
            $validated['teacher_id']
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
                    'هذا المعلم مسند بالفعل إلى هذه الشعبة.'
                );
        }

        $teacherAssignment->update([
            'teacher_id' => $validated['teacher_id'],
            'section_id' => $validated['section_id'],
        ]);

        return redirect()
            ->route('teacher-assignments.index')
            ->with(
                'success',
                'تم تعديل إسناد المعلم بنجاح.'
            );
    }

    public function destroy(
        TeacherAssignment $teacherAssignment
    ) {
        $teacherAssignment->delete();

        return redirect()
            ->route('teacher-assignments.index')
            ->with(
                'success',
                'تم حذف إسناد المعلم بنجاح.'
            );
    }
}