<?php

namespace App\Http\Controllers;

use App\Models\FingerprintDevice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FingerprintDeviceController extends Controller
{
    /**
     * عرض أجهزة البصمة.
     */
    public function index()
    {
        $devices = FingerprintDevice::latest()->paginate(10);

        return view('fingerprint_devices.index', compact('devices'));
    }

    /**
     * عرض نموذج إضافة جهاز.
     */
    public function create()
    {
        return view('fingerprint_devices.create');
    }

    /**
     * حفظ جهاز جديد.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_code' => [
                'required',
                'string',
                'max:255',
                'unique:fingerprint_devices,device_code',
            ],
            'name' => 'required|string|max:255',
            'device_type' => [
                'required',
                Rule::in(['flutter_simulator', 'physical']),
            ],
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'connection_type' => [
                'required',
                Rule::in(['api', 'tcp_ip', 'usb']),
            ],
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer|min:1|max:65535',
            'notes' => 'nullable|string|max:2000',
        ]);

        $validated['is_active'] = true;

        FingerprintDevice::create($validated);

        return redirect()
            ->route('fingerprint-devices.index')
            ->with('success', 'تمت إضافة جهاز البصمة بنجاح.');
    }

    /**
     * عرض نموذج تعديل الجهاز.
     */
    public function edit(FingerprintDevice $fingerprintDevice)
    {
        return view(
            'fingerprint_devices.edit',
            compact('fingerprintDevice')
        );
    }

    /**
     * تحديث بيانات الجهاز.
     */
    public function update(
        Request $request,
        FingerprintDevice $fingerprintDevice
    ) {
        $validated = $request->validate([
            'device_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fingerprint_devices', 'device_code')
                    ->ignore($fingerprintDevice->id),
            ],
            'name' => 'required|string|max:255',
            'device_type' => [
                'required',
                Rule::in(['flutter_simulator', 'physical']),
            ],
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'connection_type' => [
                'required',
                Rule::in(['api', 'tcp_ip', 'usb']),
            ],
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer|min:1|max:65535',
            'notes' => 'nullable|string|max:2000',
        ]);

        $fingerprintDevice->update($validated);

        return redirect()
            ->route('fingerprint-devices.index')
            ->with('success', 'تم تحديث بيانات جهاز البصمة بنجاح.');
    }

    /**
     * تفعيل الجهاز أو تعطيله.
     */
    public function toggleStatus(FingerprintDevice $fingerprintDevice)
    {
        $fingerprintDevice->update([
            'is_active' => !$fingerprintDevice->is_active,
        ]);

        return redirect()
            ->route('fingerprint-devices.index')
            ->with('success', 'تم تحديث حالة الجهاز بنجاح.');
    }
}