<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount('sections')
            ->latest()
            ->get();

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['nullable', 'string', 'max:255'],
            'education_stage' => [
                'required',
                Rule::in(['primary', 'middle', 'secondary']),
            ],
        ]);

        SchoolClass::create($validated);

        return redirect()
            ->route('classes.index')
            ->with('success', 'تم إضافة الفصل بنجاح.');
    }

    public function edit(SchoolClass $schoolClass)
    {
        return view('classes.edit', compact('schoolClass'));
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['nullable', 'string', 'max:255'],
            'education_stage' => [
                'required',
                Rule::in(['primary', 'middle', 'secondary']),
            ],
        ]);

        $schoolClass->update($validated);

        return redirect()
            ->route('classes.index')
            ->with('success', 'تم تعديل الفصل بنجاح.');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        if ($schoolClass->sections()->exists()) {
            return redirect()
                ->route('classes.index')
                ->with(
                    'error',
                    'لا يمكن حذف هذا الفصل لأنه يحتوي على شعبة أو أكثر. احذف الشعب أولًا.'
                );
        }

        $schoolClass->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', 'تم حذف الفصل بنجاح.');
    }
}