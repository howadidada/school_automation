<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementTarget;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AnnouncementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | الفئات المستهدفة
    |--------------------------------------------------------------------------
    |
    | هذه رموز داخلية للإعلانات.
    | سنربطها بالأدوار الحقيقية في النظام عند بناء API.
    |
    */

    private const TARGET_ROLES = [
        'all',
        'parents',
        'teachers',
        'students',
        'deputies',
        'supervisors',
        'academic_council',
        'quality',
        'administrative_staff',
    ];

    /*
    |--------------------------------------------------------------------------
    | عرض جميع الإعلانات
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $announcements = Announcement::with([
            'creator',
            'targets.schoolClass',
            'targets.section',
        ])
            ->latest()
            ->get();

        return view(
            'announcements.index',
            compact('announcements')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | صفحة إضافة إعلان
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();

        $sections = Section::with('schoolClass')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        return view(
            'announcements.create',
            compact('classes', 'sections')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | حفظ إعلان جديد
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $this->validateAnnouncement($request);

        DB::transaction(function () use ($validated) {

            $announcement = Announcement::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'priority' => $validated['priority'],
                'status' => $validated['status'],
                'published_at' => $this->publicationDate($validated),
                'expires_at' => $validated['expires_at'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $this->saveTargets(
                $announcement,
                $validated['targets']
            );
        });

        return redirect()
            ->route('announcements.index')
            ->with('success', 'تم حفظ الإعلان بنجاح.');
    }

    /*
    |--------------------------------------------------------------------------
    | عرض تفاصيل إعلان
    |--------------------------------------------------------------------------
    */

    public function show(Announcement $announcement)
    {
        $announcement->load([
            'creator',
            'targets.schoolClass',
            'targets.section',
        ]);

        return view(
            'announcements.show',
            compact('announcement')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | صفحة تعديل إعلان
    |--------------------------------------------------------------------------
    */

    public function edit(Announcement $announcement)
    {
        $announcement->load('targets');

        $classes = SchoolClass::orderBy('name')->get();

        $sections = Section::with('schoolClass')
            ->orderBy('school_class_id')
            ->orderBy('name')
            ->get();

        return view(
            'announcements.edit',
            compact('announcement', 'classes', 'sections')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | تحديث الإعلان
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Announcement $announcement
    ) {
        $validated = $this->validateAnnouncement($request);

        DB::transaction(function () use ($announcement, $validated) {

            $announcement->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'priority' => $validated['priority'],
                'status' => $validated['status'],
                'published_at' => $this->publicationDate($validated),
                'expires_at' => $validated['expires_at'] ?? null,
            ]);

            $announcement->targets()->delete();

            $this->saveTargets(
                $announcement,
                $validated['targets']
            );
        });

        return redirect()
            ->route('announcements.index')
            ->with('success', 'تم تعديل الإعلان بنجاح.');
    }

    /*
    |--------------------------------------------------------------------------
    | حذف الإعلان
    |--------------------------------------------------------------------------
    */

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('announcements.index')
            ->with('success', 'تم حذف الإعلان بنجاح.');
    }

    /*
    |--------------------------------------------------------------------------
    | التحقق من بيانات الإعلان
    |--------------------------------------------------------------------------
    */

    private function validateAnnouncement(Request $request): array
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'content' => [
                'required',
                'string',
            ],

            'priority' => [
                'required',
                Rule::in([
                    'normal',
                    'important',
                    'urgent',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'scheduled',
                ]),
            ],

            'published_at' => [
                'nullable',
                'date',
                'required_if:status,scheduled',
            ],

            'expires_at' => [
                'nullable',
                'date',
            ],

            'targets' => [
                'required',
                'array',
                'min:1',
            ],

            'targets.*.role' => [
                'required',
                Rule::in(self::TARGET_ROLES),
            ],

            'targets.*.section_gender' => [
                'nullable',
                Rule::in([
                    'boys',
                    'girls',
                ]),
            ],

            'targets.*.education_stage' => [
                'nullable',
                'string',
                'max:100',
            ],

            'targets.*.school_class_id' => [
                'nullable',
                'integer',
                'exists:school_classes,id',
            ],

            'targets.*.section_id' => [
                'nullable',
                'integer',
                'exists:sections,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | التحقق من موعد النشر
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] === 'scheduled') {

            if (
                !isset($validated['published_at']) ||
                strtotime($validated['published_at']) <= time()
            ) {
                throw ValidationException::withMessages([
                    'published_at' =>
                        'يجب أن يكون موعد نشر الإعلان المجدول في المستقبل.',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | التحقق من تاريخ الانتهاء
        |--------------------------------------------------------------------------
        */

        $publicationDate = $this->publicationDate($validated);

        if (
            !empty($validated['expires_at']) &&
            $publicationDate &&
            strtotime($validated['expires_at']) <= strtotime($publicationDate)
        ) {
            throw ValidationException::withMessages([
                'expires_at' =>
                    'يجب أن يكون تاريخ انتهاء الإعلان بعد تاريخ نشره.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | التحقق من توافق الصف والشعبة
        |--------------------------------------------------------------------------
        */

        foreach ($validated['targets'] as $index => $target) {

            if (
                !empty($target['school_class_id']) &&
                !empty($target['section_id'])
            ) {
                $section = Section::find($target['section_id']);

                if (
                    !$section ||
                    (int) $section->school_class_id !==
                    (int) $target['school_class_id']
                ) {
                    throw ValidationException::withMessages([
                        "targets.$index.section_id" =>
                            'الشعبة المختارة لا تتبع الصف الدراسي المحدد.',
                    ]);
                }
            }
        }

        return $validated;
    }

    /*
    |--------------------------------------------------------------------------
    | تحديد تاريخ النشر
    |--------------------------------------------------------------------------
    */

    private function publicationDate(array $validated): ?string
    {
        return match ($validated['status']) {

            'draft' => null,

            'published' => now()->toDateTimeString(),

            'scheduled' => $validated['published_at'],

            default => null,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | حفظ الفئات المستهدفة
    |--------------------------------------------------------------------------
    */

    private function saveTargets(
        Announcement $announcement,
        array $targets
    ): void {

        foreach ($targets as $target) {

            AnnouncementTarget::create([
                'announcement_id' => $announcement->id,

                'role' => $target['role'],

                'section_gender' =>
                    $target['section_gender'] ?? null,

                'education_stage' =>
                    $target['education_stage'] ?? null,

                'school_class_id' =>
                    $target['school_class_id'] ?? null,

                'section_id' =>
                    $target['section_id'] ?? null,
            ]);
        }
    }
}