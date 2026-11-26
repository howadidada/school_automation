<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use Illuminate\Http\Request;

class AcademicSettingController extends Controller
{
    public function current(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('teacher')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لتنفيذ هذه العملية.',
            ], 403);
        }

        $setting = AcademicSetting::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'لم يحدد مدير النظام الفصل الدراسي النشط بعد.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الإعدادات الأكاديمية بنجاح.',
            'data' => [
                'id' => $setting->id,

                'academic_year' => $setting->academic_year,

                'semester' => $setting->semester,

                'semester_name' =>
                    $setting->semester === 'first'
                        ? 'الفصل الدراسي الأول'
                        : 'الفصل الدراسي الثاني',

                'evaluation_periods' =>
                    $setting->evaluation_periods ?? [],
            ],
        ]);
    }
}