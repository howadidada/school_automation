<?php

namespace App\Http\Controllers;

use App\Models\AcademicSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicSettingController extends Controller
{
    /**
     * عرض الإعدادات الأكاديمية.
     */
    public function index()
    {
        $settings = AcademicSetting::query()
            ->orderByDesc('is_active')
            ->orderByDesc('academic_year')
            ->orderByDesc('id')
            ->get();

        $activeSetting = AcademicSetting::active()
            ->first();

        return view(
            'academic_settings.index',
            compact('settings', 'activeSetting')
        );
    }

    /**
     * حفظ الإعدادات الأكاديمية.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'academic_year' => [
                'required',
                'string',
                'regex:/^\d{4}-\d{4}$/',
            ],

            'semester' => [
                'required',
                'in:first,second',
            ],

            'evaluation_periods' => [
                'required',
                'array',
                'min:1',
                'max:20',
            ],

            'evaluation_periods.*' => [
                'required',
                'string',
                'max:50',
                'distinct',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ], [
            'academic_year.required' =>
                'يرجى إدخال العام الدراسي.',

            'academic_year.regex' =>
                'اكتب العام الدراسي مثل 2026-2027.',

            'semester.required' =>
                'يرجى اختيار الفصل الدراسي.',

            'evaluation_periods.required' =>
                'يرجى إضافة فترة تقييم واحدة على الأقل.',

            'evaluation_periods.*.distinct' =>
                'لا يمكن تكرار فترة التقييم.',
        ]);

        [$startYear, $endYear] = array_map(
            'intval',
            explode('-', $validated['academic_year'])
        );

        if ($endYear !== $startYear + 1) {
            return back()
                ->withInput()
                ->withErrors([
                    'academic_year' =>
                        'يجب أن تكون سنوات العام الدراسي متتالية.',
                ]);
        }

        $periods = array_values(
            array_unique(
                array_map(
                    'trim',
                    $validated['evaluation_periods']
                )
            )
        );

        $activate = $request->boolean('is_active');

        DB::transaction(function () use (
            $validated,
            $periods,
            $activate,
            $request
        ) {
            $setting = AcademicSetting::firstOrNew([
                'academic_year' =>
                    $validated['academic_year'],

                'semester' =>
                    $validated['semester'],
            ]);

            if ($activate) {
                AcademicSetting::query()
                    ->where('is_active', true)
                    ->update([
                        'is_active' => false,
                    ]);
            }

            $setting->evaluation_periods = $periods;

            $setting->is_active = $activate;

            if (!$setting->exists) {
                $setting->created_by =
                    $request->user()->id;
            }

            $setting->save();
        });

        return redirect()
            ->route('academic-settings.index')
            ->with(
                'success',
                'تم حفظ الإعدادات الأكاديمية بنجاح.'
            );
    }

    /**
     * تفعيل عام وفصل دراسي محدد.
     */
    public function activate(AcademicSetting $academicSetting)
    {
        DB::transaction(function () use ($academicSetting) {

            AcademicSetting::query()
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                ]);

            $academicSetting->update([
                'is_active' => true,
            ]);
        });

        return redirect()
            ->route('academic-settings.index')
            ->with(
                'success',
                'تم تفعيل الفصل الدراسي بنجاح.'
            );
    }
}