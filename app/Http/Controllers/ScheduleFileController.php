<?php

namespace App\Http\Controllers;

use App\Models\ScheduleFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScheduleFileController extends Controller
{
    public function index()
    {
        $scheduleFiles = ScheduleFile::with('uploader')
            ->latest()
            ->get();

        return view('schedule_files.index', compact('scheduleFiles'));
    }

    public function create()
    {
        return view('schedule_files.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
                'max:10240',
            ],
        ]);

        $path = $request->file('file')
            ->store('schedule_files', 'public');

        ScheduleFile::create([
            'title' => $validated['title'],
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()
            ->route('schedule-files.index')
            ->with('success', 'تم رفع ملف الجدول الدراسي بنجاح.');
    }

    public function show(ScheduleFile $scheduleFile)
    {
        if (
            !$scheduleFile->file_path ||
            !Storage::disk('public')->exists($scheduleFile->file_path)
        ) {
            abort(404, 'ملف الجدول غير موجود.');
        }

        return Storage::disk('public')->response(
            $scheduleFile->file_path
        );
    }

    public function edit(ScheduleFile $scheduleFile)
    {
        return view(
            'schedule_files.edit',
            compact('scheduleFile')
        );
    }

    public function update(Request $request, ScheduleFile $scheduleFile)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
                'max:10240',
            ],
        ]);

        $scheduleFile->title = $validated['title'];

        if ($request->hasFile('file')) {

            if (
                $scheduleFile->file_path &&
                Storage::disk('public')->exists($scheduleFile->file_path)
            ) {
                Storage::disk('public')->delete(
                    $scheduleFile->file_path
                );
            }

            $newPath = $request->file('file')
                ->store('schedule_files', 'public');

            $scheduleFile->file_path = $newPath;
        }

        $scheduleFile->save();

        return redirect()
            ->route('schedule-files.index')
            ->with('success', 'تم تعديل ملف الجدول الدراسي بنجاح.');
    }

    public function destroy(ScheduleFile $scheduleFile)
    {
        if (
            $scheduleFile->file_path &&
            Storage::disk('public')->exists($scheduleFile->file_path)
        ) {
            Storage::disk('public')->delete(
                $scheduleFile->file_path
            );
        }

        $scheduleFile->delete();

        return redirect()
            ->route('schedule-files.index')
            ->with('success', 'تم حذف ملف الجدول الدراسي بنجاح.');
    }
}