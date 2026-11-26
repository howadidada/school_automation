<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    // تحديد جهات التواصل المسموح بها
    private function allowedRecipientIds(User $user)
    {
        if ($user->hasRole('teacher')) {

            $teacher = Teacher::where(
                'user_id',
                $user->id
            )->first();

            if (!$teacher) {
                return collect();
            }

            $sectionIds = $teacher->assignments()
                ->pluck('section_id');

            $students = Student::with('parents')
                ->whereIn('section_id', $sectionIds)
                ->get();

            $studentIds = $students->pluck('user_id');

            $parentIds = $students
                ->flatMap(function ($student) {
                    return $student->parents->pluck('user_id');
                });

            return $studentIds
                ->merge($parentIds)
                ->filter()
                ->unique()
                ->values();
        }

        if ($user->hasRole('student')) {

            $student = $user->student;

            if (!$student) {
                return collect();
            }

            return Teacher::whereHas(
                'assignments',
                function ($query) use ($student) {
                    $query->where(
                        'section_id',
                        $student->section_id
                    );
                }
            )->pluck('user_id');
        }

        if ($user->hasRole('parent')) {

            $parent = $user->parentProfile;

            if (!$parent) {
                return collect();
            }

            $sectionIds = Student::whereHas(
                'parents',
                function ($query) use ($parent) {
                    $query->where(
                        $parent->getTable() . '.id',
                        $parent->id
                    );
                }
            )->pluck('section_id');

            return Teacher::whereHas(
                'assignments',
                function ($query) use ($sectionIds) {
                    $query->whereIn(
                        'section_id',
                        $sectionIds
                    );
                }
            )->pluck('user_id');
        }

        return collect();
    }

    // جلب جهات التواصل
    public function recipients(Request $request)
    {
        $user = $request->user();

        $ids = $this->allowedRecipientIds($user);

        $recipients = User::with('role:id,name')
            ->whereIn('id', $ids)
            ->where('id', '!=', $user->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'role_id']);

        return response()->json([
            'success' => true,
            'data' => $recipients,
        ]);
    }

    // جلب الرسائل الواردة والمرسلة
    public function index(Request $request)
    {
        $user = $request->user();

        $receivedMessages = Message::with([
            'sender:id,name,username',
            'receiver:id,name,username',
        ])
            ->where('receiver_user_id', $user->id)
            ->latest()
            ->get();

        $sentMessages = Message::with([
            'sender:id,name,username',
            'receiver:id,name,username',
        ])
            ->where('sender_user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'received' => $receivedMessages,
                'sent' => $sentMessages,
            ],
        ]);
    }

    // إرسال رسالة جديدة
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'receiver_user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $receiverId = (int) $validated['receiver_user_id'];

        if ($receiverId === (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك إرسال رسالة إلى نفسك.',
            ], 422);
        }

        $allowedIds = $this->allowedRecipientIds($user);

        if (!$allowedIds->contains($receiverId)) {
            return response()->json([
                'success' => false,
                'code' => 'RECIPIENT_NOT_ALLOWED',
                'message' => 'لا يسمح لك بالتواصل مع هذا المستخدم.',
            ], 403);
        }

        $receiver = User::where('id', $receiverId)
            ->where('is_active', true)
            ->first();

        if (!$receiver) {
            return response()->json([
                'success' => false,
                'message' => 'حساب المستلم غير متاح.',
            ], 422);
        }

        $content = trim($validated['message']);

        if ($content === '') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إرسال رسالة فارغة.',
            ], 422);
        }

        return DB::transaction(function () use (
            $user,
            $receiver,
            $content
        ) {

            $message = Message::create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $receiver->id,
                'message' => $content,
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $receiver->id,
                'type' => 'message',
                'title' => 'رسالة جديدة',
                'body' => 'لديك رسالة جديدة من ' . $user->name,
                'is_read' => false,
                'reference_id' => $message->id,
                'reference_type' => 'message',
            ]);

            $message->load([
                'sender:id,name,username',
                'receiver:id,name,username',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الرسالة بنجاح.',
                'data' => $message,
            ], 201);
        });
    }

    // تعديل رسالة
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'الرسالة غير موجودة.',
            ], 404);
        }

        if (
            (int) $message->sender_user_id
            !== (int) $user->id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك تعديل هذه الرسالة.',
            ], 403);
        }

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $content = trim($validated['message']);

        if ($content === '') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حفظ رسالة فارغة.',
            ], 422);
        }

        $message->message = $content;

        $message->save();

        $message->load([
            'sender:id,name,username',
            'receiver:id,name,username',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل الرسالة بنجاح.',
            'data' => $message,
        ]);
    }

    // حذف رسالة
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'الرسالة غير موجودة.',
            ], 404);
        }

        if (
            (int) $message->sender_user_id
            !== (int) $user->id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك حذف هذه الرسالة.',
            ], 403);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الرسالة بنجاح.',
        ]);
    }
}