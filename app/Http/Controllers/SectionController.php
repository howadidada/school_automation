<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SectionController extends Controller
{
    // ===============================================================
    // عرض الشعب
    // ===============================================================
    public function index()
    {
        $sections = Section::with('schoolClass')
            ->withCount('students')
            ->latest()
            ->get();

        return view('sections.index', compact('sections'));
    }

    // ===============================================================
    // صفحة إضافة شعبة
    // ===============================================================
    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('sections.create', compact('classes'));
    }

    // ===============================================================
    // حفظ شعبة جديدة
    // ===============================================================
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
                Rule::unique('sections', 'name')
                    ->where('school_class_id', $request->input('school_class_id'))
                    ->where('gender_section', $request->input('gender_section')),
            ],

            'gender_section' => [
                'required',
                Rule::in(['boys', 'girls']),
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
            ],
        ], [
            'gender_section.required' => 'يجب تحديد القسم: بنين أو بنات.',
            'gender_section.in' => 'القسم المحدد غير صحيح.',
            'capacity.required' => 'يجب تحديد سعة الشعبة.',
            'capacity.integer' => 'يجب أن تكون السعة رقمًا صحيحًا.',
            'capacity.min' => 'يجب أن تكون السعة طالبًا واحدًا على الأقل.',
            'name.unique' => 'اسم الشعبة موجود مسبقًا داخل هذا الصف والقسم.',
        ]);

        Section::create([
            'school_class_id' => $validated['school_class_id'],
            'name' => $validated['name'],
            'gender_section' => $validated['gender_section'],
            'capacity' => $validated['capacity'],
        ]);

        return redirect()
            ->route('sections.index')
            ->with('success', 'تم إضافة الشعبة بنجاح.');
    }

    // ===============================================================
    // صفحة تعديل الشعبة
    // ===============================================================
    public function edit(Section $section)
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('sections.edit', compact('section', 'classes'));
    }

    // ===============================================================
    // تحديث بيانات الشعبة
    // ===============================================================
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
                Rule::unique('sections', 'name')
                    ->where('school_class_id', $request->input('school_class_id'))
                    ->where('gender_section', $request->input('gender_section'))
                    ->ignore($section->id),
            ],

            'gender_section' => [
                'required',
                Rule::in(['boys', 'girls']),
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
            ],
        ], [
            'gender_section.required' => 'يجب تحديد القسم: بنين أو بنات.',
            'gender_section.in' => 'القسم المحدد غير صحيح.',
            'capacity.required' => 'يجب تحديد سعة الشعبة.',
            'capacity.integer' => 'يجب أن تكون السعة رقمًا صحيحًا.',
            'capacity.min' => 'يجب أن تكون السعة طالبًا واحدًا على الأقل.',
            'name.unique' => 'اسم الشعبة موجود مسبقًا داخل هذا الصف والقسم.',
        ]);

        DB::transaction(function () use ($validated, $section) {

            // قفل الشعبة أثناء تعديل بياناتها
            $lockedSection = Section::whereKey($section->id)
                ->lockForUpdate()
                ->firstOrFail();

            // حساب الطلاب المسجلين
            $studentsCount = $lockedSection->students()->count();

            // منع تخفيض السعة عن عدد الطلاب الحالي
            if ($validated['capacity'] < $studentsCount) {
                throw ValidationException::withMessages([
                    'capacity' =>
                        'لا يمكن تحديد السعة بـ '
                        . $validated['capacity']
                        . ' لأن الشعبة تحتوي حاليًا على '
                        . $studentsCount
                        . ' طالبًا.',
                ]);
            }

            // منع تغيير الصف أو القسم لشعبة مرتبطة بطلاب أو معلمين
            $scopeChanged =
                (int) $lockedSection->school_class_id !== (int) $validated['school_class_id']
                || $lockedSection->gender_section !== $validated['gender_section'];

            if ($scopeChanged &&
                (
                    $lockedSection->students()->exists()
                    || $lockedSection->teacherAssignments()->exists()
                )
            ) {
                throw ValidationException::withMessages([
                    'gender_section' =>
                        'لا يمكن تغيير الصف أو القسم لشعبة مرتبطة بطلاب أو إسنادات معلمين. انقل الطلاب وأزل الإسنادات أولًا.',
                ]);
            }

            $lockedSection->update([
                'school_class_id' => $validated['school_class_id'],
                'name' => $validated['name'],
                'gender_section' => $validated['gender_section'],
                'capacity' => $validated['capacity'],
            ]);
        }, 3);

        return redirect()
            ->route('sections.index')
            ->with('success', 'تم تعديل الشعبة بنجاح.');
    }

    // ===============================================================
    // حذف الشعبة
    // ===============================================================
    public function destroy(Section $section)
    {
        return DB::transaction(function () use ($section) {

            $lockedSection = Section::whereKey($section->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedSection->students()->exists()) {
                return redirect()
                    ->route('sections.index')
                    ->with(
                        'error',
                        'لا يمكن حذف هذه الشعبة لأنها تحتوي على طلاب. انقل الطلاب أولًا.'
                    );
            }

            if ($lockedSection->teacherAssignments()->exists()) {
                return redirect()
                    ->route('sections.index')
                    ->with(
                        'error',
                        'لا يمكن حذف هذه الشعبة لأنها مرتبطة بإسنادات معلمين. احذف الإسنادات أولًا.'
                    );
            }

            $lockedSection->delete();

            return redirect()
                ->route('sections.index')
                ->with('success', 'تم حذف الشعبة بنجاح.');

        }, 3);
    }
}