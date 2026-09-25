<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with('schoolClass')
            ->latest()
            ->get();

        return view('sections.index', compact('sections'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('sections.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        Section::create([
            'school_class_id' => $validated['school_class_id'],
            'name' => $validated['name'],
            'capacity' => $validated['capacity'] ?? null,
        ]);

        return redirect()
            ->route('sections.index')
            ->with('success', 'تم إضافة الشعبة بنجاح.');
    }

    public function edit(Section $section)
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view(
            'sections.edit',
            compact('section', 'classes')
        );
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'school_class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        $section->update([
            'school_class_id' => $validated['school_class_id'],
            'name' => $validated['name'],
            'capacity' => $validated['capacity'] ?? null,
        ]);

        return redirect()
            ->route('sections.index')
            ->with('success', 'تم تعديل الشعبة بنجاح.');
    }

    public function destroy(Section $section)
    {
        if ($section->students()->exists()) {
            return redirect()
                ->route('sections.index')
                ->with(
                    'error',
                    'لا يمكن حذف هذه الشعبة لأنها تحتوي على طلاب. انقل الطلاب أو احذفهم أولًا.'
                );
        }

        if ($section->teacherAssignments()->exists()) {
            return redirect()
                ->route('sections.index')
                ->with(
                    'error',
                    'لا يمكن حذف هذه الشعبة لأنها مرتبطة بإسناد معلم أو أكثر. احذف الإسنادات أولًا.'
                );
        }

        $section->delete();

        return redirect()
            ->route('sections.index')
            ->with('success', 'تم حذف الشعبة بنجاح.');
    }
}