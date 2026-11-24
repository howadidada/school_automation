<?php

namespace App\Http\Controllers;

use App\Models\ScheduleFile;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ScheduleFileController extends Controller
{
    private const PRIVATE_DISK = 'local';

    /**
     * عرض الجداول الدراسية.
     */
    public function index()
    {
        $scheduleFiles = ScheduleFile::with([
            'uploader',
            'section.schoolClass',
            'teacher.user',
        ])
            ->latest()
            ->get();

        return view('schedule_files.index', compact('scheduleFiles'));
    }

    /**
     * صفحة رفع جدول جديد.
     */
    public function create()
    {
        $sections = Section::with('schoolClass')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        // اسم المعلم موجود في جدول users.
        $teachers = Teacher::with('user')
            ->get()
            ->sortBy(fn ($teacher) => $teacher->user?->name ?? '')
            ->values();

        return view(
            'schedule_files.create',
            compact('sections', 'teachers')
        );
    }

    /**
     * حفظ جدول جديد.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'type' => [
                'required',
                Rule::in([
                    ScheduleFile::TEACHER_SCHEDULE,
                    ScheduleFile::STUDENT_SCHEDULE,
                    ScheduleFile::EXAM_SCHEDULE,
                ]),
            ],

            'teacher_id' => [
                'required_if:type,teacher_schedule',
                'nullable',
                'integer',
                'exists:teachers,id',
            ],

            'section_id' => [
                'required_if:type,student_schedule,exam_schedule',
                'nullable',
                'integer',
                'exists:sections,id',
            ],

            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
                'max:10240',
            ],
        ]);

        $type = $validated['type'];

        $teacherId = $type === ScheduleFile::TEACHER_SCHEDULE
            ? $validated['teacher_id']
            : null;

        $sectionId = $type === ScheduleFile::TEACHER_SCHEDULE
            ? null
            : $validated['section_id'];

        $path = $request->file('file')
            ->store('schedule_files', self::PRIVATE_DISK);

        if (!$path) {
            return back()
                ->withInput()
                ->with('error', 'تعذر رفع ملف الجدول الدراسي.');
        }

        try {
            ScheduleFile::create([
                'title' => $validated['title'],
                'type' => $type,
                'teacher_id' => $teacherId,
                'section_id' => $sectionId,
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
            ]);
        } catch (\Throwable $exception) {
            Storage::disk(self::PRIVATE_DISK)->delete($path);

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'تعذر حفظ بيانات الجدول الدراسي.');
        }

        return redirect()
            ->route('schedule-files.index')
            ->with('success', 'تم رفع الجدول الدراسي بنجاح.');
    }

    /**
     * عرض ملف الجدول.
     */
    public function show(ScheduleFile $scheduleFile)
    {
        $path = $scheduleFile->file_path;

        if (!$path) {
            abort(404, 'ملف الجدول غير موجود.');
        }

        if (Storage::disk(self::PRIVATE_DISK)->exists($path)) {
            return Storage::disk(self::PRIVATE_DISK)->response($path);
        }

        // دعم الملفات القديمة.
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        abort(404, 'ملف الجدول غير موجود.');
    }

    /**
     * صفحة تعديل الجدول.
     */
    public function edit(ScheduleFile $scheduleFile)
    {
        $sections = Section::with('schoolClass')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        $teachers = Teacher::with('user')
            ->get()
            ->sortBy(fn ($teacher) => $teacher->user?->name ?? '')
            ->values();

        return view(
            'schedule_files.edit',
            compact('scheduleFile', 'sections', 'teachers')
        );
    }

    /**
     * تحديث بيانات الجدول.
     */
    public function update(Request $request, ScheduleFile $scheduleFile)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'type' => [
                'required',
                Rule::in([
                    ScheduleFile::TEACHER_SCHEDULE,
                    ScheduleFile::STUDENT_SCHEDULE,
                    ScheduleFile::EXAM_SCHEDULE,
                ]),
            ],

            'teacher_id' => [
                'required_if:type,teacher_schedule',
                'nullable',
                'integer',
                'exists:teachers,id',
            ],

            'section_id' => [
                'required_if:type,student_schedule,exam_schedule',
                'nullable',
                'integer',
                'exists:sections,id',
            ],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
                'max:10240',
            ],
        ]);

        $type = $validated['type'];

        $teacherId = $type === ScheduleFile::TEACHER_SCHEDULE
            ? $validated['teacher_id']
            : null;

        $sectionId = $type === ScheduleFile::TEACHER_SCHEDULE
            ? null
            : $validated['section_id'];

        $oldPath = $scheduleFile->file_path;
        $newPath = null;

        if ($request->hasFile('file')) {
            $newPath = $request->file('file')
                ->store('schedule_files', self::PRIVATE_DISK);

            if (!$newPath) {
                return back()
                    ->withInput()
                    ->with('error', 'تعذر رفع الملف البديل.');
            }
        }

        try {
            $scheduleFile->title = $validated['title'];
            $scheduleFile->type = $type;
            $scheduleFile->teacher_id = $teacherId;
            $scheduleFile->section_id = $sectionId;

            if ($newPath) {
                $scheduleFile->file_path = $newPath;
            }

            $scheduleFile->save();
        } catch (\Throwable $exception) {
            if ($newPath) {
                Storage::disk(self::PRIVATE_DISK)->delete($newPath);
            }

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'تعذر تعديل الجدول الدراسي.');
        }

        if ($newPath && $oldPath && $oldPath !== $newPath) {
            $this->deleteStoredFile($oldPath);
        }

        return redirect()
            ->route('schedule-files.index')
            ->with('success', 'تم تعديل الجدول الدراسي بنجاح.');
    }

    /**
     * حذف الجدول.
     */
    public function destroy(ScheduleFile $scheduleFile)
    {
        $path = $scheduleFile->file_path;

        try {
            $scheduleFile->delete();
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->with('error', 'تعذر حذف الجدول الدراسي.');
        }

        if ($path) {
            $this->deleteStoredFile($path);
        }

        return redirect()
            ->route('schedule-files.index')
            ->with('success', 'تم حذف الجدول الدراسي بنجاح.');
    }

    /**
     * حذف الملف المخزن.
     */
    private function deleteStoredFile(string $path): void
    {
        foreach ([self::PRIVATE_DISK, 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }
        }
    }
}