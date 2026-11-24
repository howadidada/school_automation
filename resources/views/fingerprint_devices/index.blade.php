<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | إدارة أجهزة البصمة</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
        }

        body {
            background: #f5f7fb;
            color: #1f2937;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #111827;
            color: white;
            padding: 25px 18px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            margin-bottom: 35px;
            padding: 0 10px;
        }

        .brand h1 {
            font-size: 30px;
            margin-bottom: 5px;
        }

        .brand span {
            font-size: 13px;
            color: #cbd5e1;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            text-decoration: none;
            color: #d1d5db;
            padding: 13px 14px;
            border-radius: 9px;
            font-size: 15px;
        }

        .menu-item:hover,
        .menu-item.active {
            background: #1f2937;
            color: white;
        }

        .logout-area {
            margin-top: auto;
            padding-top: 25px;
        }

        .logout-btn {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 8px;
            background: #ef4444;
            color: white;
            cursor: pointer;
        }

        .main {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 5px;
        }

        .topbar p {
            color: #6b7280;
            line-height: 1.8;
        }

        .add-btn {
            display: inline-block;
            background: #111827;
            color: white;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
        }

        .add-btn:hover {
            background: #1f2937;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 13px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
            border: 1px solid #eef0f4;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }

        .stat-card.active .stat-value {
            color: #15803d;
        }

        .stat-card.inactive .stat-value {
            color: #dc2626;
        }

        .table-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1050px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
            white-space: nowrap;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-size: 13px;
            font-weight: 700;
        }

        td {
            font-size: 14px;
        }

        tbody tr:hover {
            background: #fafbff;
        }

        .device-name {
            font-weight: 700;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .device-type {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 12px;
            font-weight: 700;
        }

        .actions {
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap;
        }

        .edit-btn {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 7px;
        }

        .toggle-btn {
            border: none;
            border-radius: 7px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 13px;
        }

        .disable-btn {
            background: #fee2e2;
            color: #991b1b;
        }

        .enable-btn {
            background: #dcfce7;
            color: #166534;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #6b7280;
        }

        .pagination {
            padding: 20px;
        }

        @media (max-width: 1200px) {
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .main {
                padding: 15px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .topbar h2 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="topbar">

            <div>
                <h2>إدارة أجهزة البصمة</h2>
                <p>
                    تسجيل أجهزة البصمة وإدارة إعداداتها
                    ومتابعة حالتها وآخر مزامنة مسجلة
                </p>
            </div>

            <a
                href="{{ route('fingerprint-devices.create') }}"
                class="add-btn"
            >
                + إضافة جهاز بصمة
            </a>

        </div>

        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif

        @php
            $totalDevices = \App\Models\FingerprintDevice::count();

            $activeDevices = \App\Models\FingerprintDevice::where(
                'is_active',
                true
            )->count();

            $inactiveDevices = \App\Models\FingerprintDevice::where(
                'is_active',
                false
            )->count();

            $flutterDevices = \App\Models\FingerprintDevice::where(
                'device_type',
                'flutter_simulator'
            )->count();
        @endphp

        <div class="stats">

            <div class="stat-card">
                <div class="stat-label">إجمالي الأجهزة</div>
                <div class="stat-value">
                    {{ $totalDevices }}
                </div>
            </div>

            <div class="stat-card active">
                <div class="stat-label">الأجهزة المفعّلة</div>
                <div class="stat-value">
                    {{ $activeDevices }}
                </div>
            </div>

            <div class="stat-card inactive">
                <div class="stat-label">الأجهزة المعطّلة</div>
                <div class="stat-value">
                    {{ $inactiveDevices }}
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">أجهزة تطبيق Flutter</div>
                <div class="stat-value">
                    {{ $flutterDevices }}
                </div>
            </div>

        </div>

        <div class="table-box">

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الجهاز</th>
                        <th>رمز الجهاز</th>
                        <th>نوع الجهاز</th>
                        <th>الموقع</th>
                        <th>طريقة الاتصال</th>
                        <th>آخر مزامنة</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($devices as $device)

                    <tr>

                        <td>{{ $device->id }}</td>

                        <td class="device-name">
                            {{ $device->name }}
                        </td>

                        <td>{{ $device->device_code }}</td>

                        <td>
                            <span class="device-type">
                                {{ $device->device_type === 'flutter_simulator'
                                    ? 'تطبيق Flutter'
                                    : 'جهاز بصمة فعلي' }}
                            </span>
                        </td>

                        <td>
                            {{ $device->location ?: 'غير محدد' }}
                        </td>

                        <td>
                            @switch($device->connection_type)
                                @case('api')
                                    API
                                    @break

                                @case('tcp_ip')
                                    TCP/IP
                                    @break

                                @case('usb')
                                    USB
                                    @break

                                @default
                                    غير محدد
                            @endswitch
                        </td>

                        <td>
                            {{ $device->last_sync_at
                                ? $device->last_sync_at->format('Y-m-d H:i')
                                : 'لم تتم المزامنة بعد' }}
                        </td>

                        <td>
                            @if($device->is_active)
                                <span class="status status-active">
                                    مفعّل
                                </span>
                            @else
                                <span class="status status-inactive">
                                    معطّل
                                </span>
                            @endif
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('fingerprint-devices.edit', $device) }}"
                                    class="edit-btn"
                                >
                                    تعديل
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('fingerprint-devices.toggle-status', $device) }}"
                                    onsubmit="return confirm('هل تريد تغيير حالة هذا الجهاز؟');"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="toggle-btn {{ $device->is_active ? 'disable-btn' : 'enable-btn' }}"
                                    >
                                        {{ $device->is_active ? 'تعطيل' : 'تفعيل' }}
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="empty">
                            لا توجد أجهزة بصمة مسجلة حاليًا.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

            @if($devices->hasPages())
                <div class="pagination">
                    {{ $devices->links() }}
                </div>
            @endif

        </div>

    </main>

</div>

</body>
</html>