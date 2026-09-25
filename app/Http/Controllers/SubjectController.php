<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('teachers')
            ->latest()
            ->get();

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:subjects,name',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        Subject::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'تم إضافة المادة الدراسية بنجاح.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'name')
                    ->ignore($subject->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $subject->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'تم تعديل المادة الدراسية بنجاح.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->teachers()->exists()) {
            return redirect()
                ->route('subjects.index')
                ->with(
                    'error',
                    'لا يمكن حذف هذه المادة لأنها مرتبطة بمعلم أو أكثر. قم بتغيير مادة المعلمين أولًا.'
                );
        }

        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'تم حذف المادة الدراسية بنجاح.');
    }
}