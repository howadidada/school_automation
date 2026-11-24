<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تفاصيل الإعلان المدرسي</title>

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

        /* القائمة الجانبية */

        .sidebar {
            width: 260px;
            min-width: 260px;
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

        /* المحتوى الرئيسي */

        .main {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 6px;
        }

        .topbar p {
            color: #6b7280;
            line-height: 1.8;
            font-size: 14px;
        }

        .header-actions,
        .bottom-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .back-btn,
        .edit-btn,
        .delete-btn {
            display: inline-block;
            padding: 11px 19px;
            border-radius: 9px;
            text-decoration: none;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
        }

        .back-btn {
            background: #e2e8f0;
            color: #334155;
        }

        .back-btn:hover {
            background: #cbd5e1;
        }

        .edit-btn {
            background: #111827;
            color: white;
        }

        .edit-btn:hover {
            background: #1f2937;
        }

        .delete-btn {
            background: #fee2e2;
            color: #991b1b;
        }

        .delete-btn:hover {
            background: #fecaca;
        }

        .delete-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* بطاقات التفاصيل */

        .detail-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        }

        .detail-card h3 {
            font-size: 19px;
            color: #111827;
            margin-bottom: 22px;
        }

        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .title-row h3 {
            margin-bottom: 0;
            overflow-wrap: anywhere;
        }

        .announcement-content {
            margin-top: 22px;
            color: #334155;
            line-height: 2;
            font-size: 15px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        /* معلومات الإعلان */

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .info-item {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 17px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-width: 0;
        }

        .info-item span {
            color: #64748b;
            font-size: 13px;
        }

        .info-item strong {
            color: #111827;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        /* الأولوية والحالة */

        .status-badge,
        .priority-badge {
            display: inline-block;
            width: fit-content;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-published {
            background: #dcfce7;
            color: #166534;
        }

        .status-scheduled {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-draft {
            background: #f1f5f9;
            color: #475569;
        }

        .status-expired {
            background: #fee2e2;
            color: #991b1b;
        }

        .priority-normal {
            background: #f1f5f9;
            color: #475569;
        }

        .priority-important {
            background: #fef3c7;
            color: #92400e;
        }

        .priority-urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        /* الفئات المستهدفة */

        .target-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .target-card:last-child {
            margin-bottom: 0;
        }

        .target-title {
            color: #1d4ed8;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .target-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .target-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .target-item span {
            color: #64748b;
            font-size: 13px;
        }

        .target-item strong {
            color: #334155;
            font-size: 14px;
        }

        .empty-message {
            padding: 20px;
            text-align: center;
            color: #64748b;
            background: #f8fafc;
            border-radius: 10px;
        }

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 15px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            line-height: 1.9;
            font-size: 14px;
        }

        .bottom-actions {
            margin-bottom: 30px;
        }

        /* التوافق مع الشاشات */

        @media (max-width: 1100px) {
            .info-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-width: 0;
            }

            .main {
                padding: 18px;
            }
        }

        @media (max-width: 650px) {
            .info-grid,
            .target-grid {
                grid-template-columns: 1fr;
            }

            .detail-card {
                padding: 17px;
            }

            .header-actions {
                width: 100%;
            }
        }
    </style>
</head>

<body>

@php
    $currentTime = now();

    $roleLabels = [
        'all' => 'جميع مستخدمي النظام',
        'parents' => 'أولياء الأمور',
        'teachers' => 'المعلمون والمعلمات',
        'students' => 'الطلاب والطالبات',
        'deputies' => 'الوكلاء',
        'supervisors' => 'مشرفو المراحل',
        'academic_council' => 'المجلس الأكاديمي',
        'quality' => 'إدارة التطوير والجودة',
        'administrative_staff' => 'الإداريون والخدمات',
    ];

    $stageLabels = [
        'primary' => 'المرحلة الابتدائية',
        'middle' => 'المرحلة الإعدادية',
        'secondary' => 'المرحلة الثانوية',
    ];

    $genderLabels = [
        'boys' => 'بنين',
        'girls' => 'بنات',
    ];

    $priorityLabels = [
        'normal' => 'عادي',
        'important' => 'مهم',
        'urgent' => 'عاجل',
    ];

    /*
    |--------------------------------------------------------------------------
    | تحديد الحالة الفعلية للإعلان
    |--------------------------------------------------------------------------
    */

    if ($announcement->status === 'draft') {
        $displayStatus = 'draft';
    } elseif (
        $announcement->expires_at &&
        $announcement->expires_at->lte($currentTime)
    ) {
        $displayStatus = 'expired';
    } elseif (
        $announcement->published_at &&
        $announcement->published_at->gt($currentTime)
    ) {
        $displayStatus = 'scheduled';
    } elseif (
        $announcement->published_at &&
        $announcement->published_at->lte($currentTime)
    ) {
        $displayStatus = 'published';
    } else {
        $displayStatus = 'draft';
    }

    $statusLabels = [
        'draft' => 'مسودة',
        'published' => 'منشور',
        'scheduled' => 'مجدول',
        'expired' => 'منتهي',
    ];

    $priority = $announcement->priority ?? 'normal';
@endphp

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        {{-- عنوان الصفحة --}}

        <div class="topbar">

            <div>
                <h2>تفاصيل الإعلان المدرسي</h2>

                <p>
                    عرض بيانات الإعلان والفئات المستهدفة
                    داخل نظام صلة.
                </p>
            </div>

            <div class="header-actions">

                <a
                    href="{{ route('announcements.edit', $announcement) }}"
                    class="edit-btn"
                >
                    تعديل الإعلان
                </a>

                <a
                    href="{{ route('announcements.index') }}"
                    class="back-btn"
                >
                    العودة للإعلانات
                </a>

            </div>

        </div>

        {{-- رسالة توضيحية --}}

        <div class="info-box">
            تعرض هذه الصفحة تفاصيل الإعلان المسجل،
            بما في ذلك حالته وأولويته والفئات التي تم تحديدها لاستقباله.
        </div>

        {{-- محتوى الإعلان --}}

        <div class="detail-card">

            <div class="title-row">

                <h3>{{ $announcement->title }}</h3>

                <span class="status-badge status-{{ $displayStatus }}">
                    {{ $statusLabels[$displayStatus] }}
                </span>

            </div>

            <div class="announcement-content">{{ $announcement->content }}</div>

        </div>

        {{-- معلومات الإعلان --}}

        <div class="detail-card">

            <h3>معلومات الإعلان</h3>

            <div class="info-grid">

                <div class="info-item">
                    <span>أولوية الإعلان</span>

                    <strong>
                        <span class="priority-badge priority-{{ $priority }}">
                            {{ $priorityLabels[$priority] ?? 'عادي' }}
                        </span>
                    </strong>
                </div>

                <div class="info-item">
                    <span>حالة الإعلان</span>

                    <strong>
                        <span class="status-badge status-{{ $displayStatus }}">
                            {{ $statusLabels[$displayStatus] }}
                        </span>
                    </strong>
                </div>

                <div class="info-item">
                    <span>موعد النشر</span>

                    <strong>
                        {{ $announcement->published_at?->format('Y-m-d H:i') ?? 'لم يُحدد' }}
                    </strong>
                </div>

                <div class="info-item">
                    <span>تاريخ انتهاء الإعلان</span>

                    <strong>
                        {{ $announcement->expires_at?->format('Y-m-d H:i') ?? 'بدون تاريخ انتهاء' }}
                    </strong>
                </div>

                <div class="info-item">
                    <span>أنشئ بواسطة</span>

                    <strong>
                        {{ $announcement->creator?->name ?? 'غير معروف' }}
                    </strong>
                </div>

                <div class="info-item">
                    <span>تاريخ الإنشاء</span>

                    <strong>
                        {{ $announcement->created_at?->format('Y-m-d H:i') ?? 'غير متوفر' }}
                    </strong>
                </div>

            </div>

        </div>

        {{-- الفئات المستهدفة --}}

        <div class="detail-card">

            <h3>الفئات المستهدفة</h3>

            @forelse($announcement->targets as $target)

                <div class="target-card">

                    <div class="target-title">
                        {{ $roleLabels[$target->role] ?? $target->role }}
                    </div>

                    <div class="target-grid">

                        <div class="target-item">
                            <span>القسم</span>

                            <strong>
                                {{ $genderLabels[$target->section_gender] ?? 'جميع الأقسام' }}
                            </strong>
                        </div>

                        <div class="target-item">
                            <span>المرحلة الدراسية</span>

                            <strong>
                                {{ $stageLabels[$target->education_stage] ?? 'جميع المراحل' }}
                            </strong>
                        </div>

                        <div class="target-item">
                            <span>الصف الدراسي</span>

                            <strong>
                                {{ $target->schoolClass?->name ?? 'جميع الصفوف' }}
                            </strong>
                        </div>

                        <div class="target-item">
                            <span>الشعبة</span>

                            <strong>
                                {{ $target->section?->name ?? 'جميع الشعب' }}
                            </strong>
                        </div>

                    </div>

                </div>

            @empty

                <div class="empty-message">
                    لم يتم تحديد فئات مستهدفة لهذا الإعلان.
                </div>

            @endforelse

        </div>

        {{-- الإجراءات --}}

        <div class="bottom-actions">

            <a
                href="{{ route('announcements.edit', $announcement) }}"
                class="edit-btn"
            >
                تعديل الإعلان
            </a>

            <form
                method="POST"
                action="{{ route('announcements.destroy', $announcement) }}"
                id="deleteAnnouncementForm"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    id="deleteAnnouncementBtn"
                    class="delete-btn"
                >
                    حذف الإعلان
                </button>

            </form>

        </div>

    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteForm = document.getElementById('deleteAnnouncementForm');
    const deleteButton = document.getElementById('deleteAnnouncementBtn');

    if (!deleteForm) {
        return;
    }

    deleteForm.addEventListener('submit', function (event) {

        if (deleteForm.dataset.submitting === 'true') {
            event.preventDefault();
            return;
        }

        const confirmed = confirm(
            'هل أنت متأكد من حذف هذا الإعلان المدرسي؟'
        );

        if (!confirmed) {
            event.preventDefault();
            return;
        }

        deleteForm.dataset.submitting = 'true';

        if (deleteButton) {
            deleteButton.disabled = true;
            deleteButton.textContent = 'جارٍ الحذف...';
        }
    });

});
</script>

</body>
</html>