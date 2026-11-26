<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ChangePasswordController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
                'max:128',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                'different:current_password',
            ],
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($user, $validated) {

            $account = $user->newQuery()
                ->whereKey($user->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (!Hash::check(
                $validated['current_password'],
                $account->password
            )) {
                return response()->json([
                    'success' => false,
                    'code' => 'INVALID_CURRENT_PASSWORD',
                    'message' => 'كلمة المرور الحالية غير صحيحة.',
                ], 422);
            }

            $account->password = Hash::make(
                $validated['password']
            );

            $account->save();

            // إنهاء جميع جلسات الحساب السابقة
            $account->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير كلمة المرور بنجاح.',
            ]);
        });
    }
}