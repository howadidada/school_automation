<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Teacher;
use App\Models\Notification;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * معرفة المعلم المسجل دخوله.
     */
    private function getTeacher(Request $request): Teacher
    {
        return Teacher::where(
            'user_id',
            $request->user()->id
        )->firstOrFail();
    }

    /**
     * جلب واجبات وأنشطة المعلم.
     */
    public function index(Request $request)
    {
        $teacher = $this->getTeacher($request);

        $query = Task::with([
            'subject',
            'section.schoolClass',
        ])
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where(
                'type',
                $request->type
            );
        }

        if ($request->filled('subject_id')) {
            $query->where(
                'subject_id',
                $request->subject_id
            );
        }

        if ($request->filled('section_id')) {
            $query->where(
                'section_id',
                $request->section_id
            );
        }

        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    }

    /**
     * إضافة واجب أو نشاط.
     */
    public function store(Request $request)
    {
        $teacher = $this->getTeacher($request);

        $validated = $request->validate([
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

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'details' => [
                'required',
                'string',
            ],

            'type' => [
                'required',
                'in:homework,activity',
            ],

            'due_date' => [
                'required',
                'date',
            ],
        ]);

        /*
         * نتأكد أن المادة والشعبة مسندتان
         * فعلًا إلى هذا المعلم.
         */
        $hasAssignment = $teacher
            ->assignments()
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'section_id',
                $validated['section_id']
            )
            ->exists();

        if (!$hasAssignment) {
            return response()->json([
                'success' => false,
                'message' =>
                    'هذه المادة والشعبة غير مسندتين لهذا المعلم.',
            ], 403);
        }

        // ===========================================================
        // إنشاء الواجب أو النشاط
        // ===========================================================
        $task = Task::create([
            'teacher_id' =>
                $teacher->id,

            'subject_id' =>
                $validated['subject_id'],

            'section_id' =>
                $validated['section_id'],

            'title' =>
                $validated['title'],

            'details' =>
                $validated['details'],

            'type' =>
                $validated['type'],

            'due_date' =>
                $validated['due_date'],
        ]);

        // ===========================================================
        // تحميل المادة والشعبة
        // ===========================================================
        $task->load([
            'subject',
            'section.schoolClass',
        ]);

        // ===========================================================
        // إنشاء إشعار تلقائي للمعلم
        // ===========================================================

        $notificationTitle =
            $task->type === 'homework'
                ? 'تم إضافة واجب جديد'
                : 'تم إضافة نشاط جديد';

        $subjectName =
            $task->subject?->name ?? 'المادة';

        $notificationBody =
            $task->type === 'homework'
                ? 'تمت إضافة واجب جديد لمادة ' .
                    $subjectName .
                    '.'
                : 'تمت إضافة نشاط جديد لمادة ' .
                    $subjectName .
                    '.';

        Notification::create([
            // user_id الخاص بالمعلم
            'user_id' =>
                $teacher->user_id,

            'type' =>
                'task',

            'title' =>
                $notificationTitle,

            'body' =>
                $notificationBody,

            'is_read' =>
                false,

            'reference_id' =>
                $task->id,

            'reference_type' =>
                'task',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ المهمة بنجاح.',
            'data' => $task,
        ], 201);
    }

    /**
     * عرض مهمة واحدة.
     */
    public function show(
        Request $request,
        Task $task
    ) {
        $teacher = $this->getTeacher($request);

        if ($task->teacher_id !== $teacher->id) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بعرض هذه المهمة.',
            ], 403);
        }

        $task->load([
            'subject',
            'section.schoolClass',
        ]);

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

    /**
     * تعديل واجب أو نشاط.
     */
    public function update(
        Request $request,
        Task $task
    ) {
        $teacher = $this->getTeacher($request);

        if ($task->teacher_id !== $teacher->id) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بتعديل هذه المهمة.',
            ], 403);
        }

        $validated = $request->validate([
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

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'details' => [
                'required',
                'string',
            ],

            'type' => [
                'required',
                'in:homework,activity',
            ],

            'due_date' => [
                'required',
                'date',
            ],
        ]);

        $hasAssignment = $teacher
            ->assignments()
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'section_id',
                $validated['section_id']
            )
            ->exists();

        if (!$hasAssignment) {
            return response()->json([
                'success' => false,
                'message' =>
                    'هذه المادة والشعبة غير مسندتين لهذا المعلم.',
            ], 403);
        }

        // ===========================================================
        // تحديث المهمة
        // ===========================================================
        $task->update($validated);

        $task->load([
            'subject',
            'section.schoolClass',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل المهمة بنجاح.',
            'data' => $task,
        ]);
    }

    /**
     * حذف واجب أو نشاط.
     */
    public function destroy(
        Request $request,
        Task $task
    ) {
        $teacher = $this->getTeacher($request);

        if ($task->teacher_id !== $teacher->id) {
            return response()->json([
                'success' => false,
                'message' =>
                    'غير مصرح لك بحذف هذه المهمة.',
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المهمة بنجاح.',
        ]);
    }
}