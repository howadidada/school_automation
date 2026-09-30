<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // ===============================================================
    // جلب إشعارات المستخدم الحالي
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

        $notifications = Notification::where(
            'user_id',
            $user->id
        )
            ->latest()
            ->get();

        $unreadCount = Notification::where(
            'user_id',
            $user->id
        )
            ->where(
                'is_read',
                false
            )
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ],
        ]);
    }

    // ===============================================================
    // تحديد إشعار واحد كمقروء
    // ===============================================================
    public function markAsRead(
        Request $request,
        $id
    ) {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
            ], 401);
        }

        $notification = Notification::where(
            'id',
            $id
        )
            ->where(
                'user_id',
                $user->id
            )
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'الإشعار غير موجود.',
            ], 404);
        }

        $notification->is_read = true;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد الإشعار كمقروء.',
            'data' => $notification,
        ]);
    }

    // ===============================================================
    // تحديد جميع الإشعارات كمقروءة
    // ===============================================================
    public function markAllAsRead(
        Request $request
    ) {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير مسجل الدخول.',
            ], 401);
        }

        Notification::where(
            'user_id',
            $user->id
        )
            ->where(
                'is_read',
                false
            )
            ->update([
                'is_read' => true,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد جميع الإشعارات كمقروءة.',
        ]);
    }
}