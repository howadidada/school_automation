<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // ===============================================================
    // جلب الرسائل
    // ===============================================================
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
            ], 401);
        }

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

    // ===============================================================
    // إرسال رسالة
    // ===============================================================
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
            ], 401);
        }

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

        // ===========================================================
        // منع إرسال رسالة للنفس
        // ===========================================================
        if (
            (int) $validated['receiver_user_id']
            ===
            (int) $user->id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك إرسال رسالة إلى نفسك.',
            ], 422);
        }

        // ===========================================================
        // جلب المستقبل
        // ===========================================================
        $receiver = User::find(
            $validated['receiver_user_id']
        );

        if (!$receiver) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم المستقبل غير موجود.',
            ], 404);
        }

        // ===========================================================
        // إنشاء الرسالة
        // ===========================================================
        $message = Message::create([
            'sender_user_id' => $user->id,
            'receiver_user_id' => $receiver->id,
            'message' => trim(
                $validated['message']
            ),
            'is_read' => false,
        ]);

        // ===========================================================
        // إنشاء إشعار للمستقبل
        // ===========================================================
        Notification::create([
            'user_id' => $receiver->id,

            'type' => 'message',

            'title' => 'رسالة جديدة',

            'body' =>
                'لديك رسالة جديدة من ' . $user->name,

            'is_read' => false,

            'reference_id' => $message->id,

            'reference_type' => 'message',
        ]);

        // ===========================================================
        // تحميل بيانات المرسل والمستقبل
        // ===========================================================
        $message->load([
            'sender:id,name,username',
            'receiver:id,name,username',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرسالة بنجاح.',
            'data' => $message,
        ], 201);
    }

    // ===============================================================
    // تعديل رسالة
    // ===============================================================
    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
            ], 401);
        }

        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'الرسالة غير موجودة.',
            ], 404);
        }

        // ===========================================================
        // فقط المرسل يستطيع تعديل رسالته
        // ===========================================================
        if (
            (int) $message->sender_user_id
            !==
            (int) $user->id
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

        $message->message = trim(
            $validated['message']
        );

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

    // ===============================================================
    // حذف رسالة
    // ===============================================================
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
            ], 401);
        }

        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'الرسالة غير موجودة.',
            ], 404);
        }

        // ===========================================================
        // فقط المرسل يستطيع حذف رسالته
        // ===========================================================
        if (
            (int) $message->sender_user_id
            !==
            (int) $user->id
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