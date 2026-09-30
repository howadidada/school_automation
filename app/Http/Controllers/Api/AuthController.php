<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ===============================================================
    // تسجيل الدخول
    // ===============================================================
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::with('teacher')
            ->where('username', $request->username)
            ->first();

        // التحقق من اسم المستخدم وكلمة المرور
        if (
            !$user ||
            !Hash::check($request->password, $user->password)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة.',
            ], 401);
        }

        // التحقق من أن الحساب مفعل
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الحساب غير مفعل.',
            ], 403);
        }

        // التحقق من أن الحساب مرتبط بمعلم
        if (!$user->teacher) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الحساب غير مرتبط بمعلم.',
            ], 403);
        }

        // حذف التوكنات القديمة
        $user->tokens()->delete();

        // إنشاء توكن جديد
        $token = $user
            ->createToken('teacher-mobile')
            ->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح.',
            'token' => $token,
            'token_type' => 'Bearer',

            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role_id' => $user->role_id,
                ],

                'teacher' => [
                    'id' => $user->teacher->id,
                    'user_id' => $user->teacher->user_id,
                    'subject_id' => $user->teacher->subject_id,
                    'specialization' => $user->teacher->specialization,
                ],
            ],
        ]);
    }

    // ===============================================================
    // المستخدم المسجل حالياً
    // ===============================================================
    public function me(Request $request)
    {
        $user = $request->user();

        $user->load('teacher');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'teacher' => $user->teacher,
            ],
        ]);
    }

    // ===============================================================
    // تسجيل الخروج
    // ===============================================================
    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ?->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح.',
        ]);
    }
}