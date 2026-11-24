<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | إضافة إعلان جديد</title>

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

        .back-btn {
            display: inline-block;
            background: #e2e8f0;
            color: #334155;
            padding: 12px 20px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-btn:hover {
            background: #cbd5e1;
        }

        /* البطاقات */

        .form-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 24px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04);
        }

        .form-card h3 {
            font-size: 19px;
            color: #111827;
            margin-bottom: 22px;
        }

        .form-card-description {
            color: #64748b;
            font-size: 14px;
            line-height: 1.9;
            margin-bottom: 20px;
        }

        /* الحقول */

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-bottom: 20px;
            min-width: 0;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .required {
            color: #dc2626;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            background: white;
            color: #1f2937;
            font-size: 14px;
            outline: none;
        }

        .form-group textarea {
            min-height: 160px;
            resize: vertical;
            line-height: 1.9;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
        }

        .form-group input:disabled,
        .form-group select:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .field-note {
            color: #64748b;
            font-size: 12px;
            line-height: 1.8;
        }

        /* رسائل الأخطاء */

        .alert {
            padding: 15px 18px;
            border-radius: 10px;
            margin-bottom: 22px;
            line-height: 1.9;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            padding-right: 22px;
            margin-top: 8px;
        }

        /* توضيح */

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 15px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            line-height: 1.9;
            font-size: 14px;
        }

        /* الفئات المستهدفة */

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .section-heading h3 {
            margin-bottom: 7px;
        }

        .section-heading p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.8;
        }

        .add-target-btn {
            background: #111827;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .add-target-btn:hover {
            background: #1f2937;
        }

        .target-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .target-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .target-header strong {
            color: #111827;
            font-size: 15px;
        }

        .remove-target-btn {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
        }

        .target-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        /* أزرار الحفظ */

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .save-btn {
            background: #111827;
            color: white;
            border: none;
            padding: 13px 28px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
        }

        .save-btn:hover {
            background: #1f2937;
        }

        .save-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .cancel-btn {
            background: #e2e8f0;
            color: #334155;
            padding: 13px 25px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 14px;
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

            .topbar {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 650px) {
            .form-grid,
            .target-grid {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 17px;
            }

            .back-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        {{-- عنوان الصفحة --}}

        <div class="topbar">

            <div>
                <h2>إضافة إعلان مدرسي جديد</h2>

                <p>
                    إنشاء إعلان وتحديد الفئات المستهدفة
                    داخل نظام صلة.
                </p>
            </div>

            <a
                href="{{ route('announcements.index') }}"
                class="back-btn"
            >
                العودة للإعلانات
            </a>

        </div>

        {{-- رسائل الأخطاء --}}

        @if($errors->any())
            <div class="alert alert-error">

                <strong>يرجى تصحيح الأخطاء التالية:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        {{-- توضيح وظيفة الصفحة --}}

        <div class="info-box">
            يمكنك إنشاء إعلان مدرسي وتحديد الفئات التي سيظهر لها.
            <br>
            يمكن نشر الإعلان فورًا، أو جدولته لموعد لاحق،
            أو حفظه كمسودة.
            <br>
            يمكنك أيضًا تخصيص الإعلان حسب القسم والمرحلة
            والصف والشعبة.
        </div>

        {{-- نموذج إنشاء الإعلان --}}

        <form
            method="POST"
            action="{{ route('announcements.store') }}"
            id="announcementForm"
        >

            @csrf

            {{-- بيانات الإعلان --}}

            <div class="form-card">

                <h3>بيانات الإعلان</h3>

                <div class="form-group">

                    <label for="title">
                        عنوان الإعلان
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        maxlength="255"
                        placeholder="مثال: اجتماع أولياء الأمور"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="content">
                        محتوى الإعلان
                        <span class="required">*</span>
                    </label>

                    <textarea
                        id="content"
                        name="content"
                        rows="6"
                        placeholder="اكتبي تفاصيل الإعلان هنا..."
                        required
                    >{{ old('content') }}</textarea>

                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="priority">
                            أولوية الإعلان
                        </label>

                        <select
                            name="priority"
                            id="priority"
                            required
                        >

                            <option
                                value="normal"
                                @selected(old('priority', 'normal') === 'normal')
                            >
                                عادي
                            </option>

                            <option
                                value="important"
                                @selected(old('priority') === 'important')
                            >
                                مهم
                            </option>

                            <option
                                value="urgent"
                                @selected(old('priority') === 'urgent')
                            >
                                عاجل
                            </option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label for="status">
                            حالة الإعلان
                        </label>

                        <select
                            name="status"
                            id="status"
                            required
                        >

                            <option
                                value="draft"
                                @selected(old('status', 'draft') === 'draft')
                            >
                                حفظ كمسودة
                            </option>

                            <option
                                value="published"
                                @selected(old('status') === 'published')
                            >
                                نشر الآن
                            </option>

                            <option
                                value="scheduled"
                                @selected(old('status') === 'scheduled')
                            >
                                جدولة النشر
                            </option>

                        </select>

                    </div>

                </div>

                <div class="form-grid">

                    <div
                        class="form-group"
                        id="publishDateGroup"
                        style="display:none;"
                    >

                        <label for="published_at">
                            موعد النشر
                            <span class="required">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="published_at"
                            id="published_at"
                            value="{{ old('published_at') }}"
                        >

                        <span class="field-note">
                            يظهر هذا الحقل عند اختيار جدولة النشر.
                        </span>

                    </div>

                    <div class="form-group">

                        <label for="expires_at">
                            تاريخ انتهاء الإعلان
                        </label>

                        <input
                            type="datetime-local"
                            name="expires_at"
                            id="expires_at"
                            value="{{ old('expires_at') }}"
                        >

                        <span class="field-note">
                            اختياري، ويمكن تركه فارغًا.
                        </span>

                    </div>

                </div>

            </div>

            {{-- الفئات المستهدفة --}}

            <div class="form-card">

                <div class="section-heading">

                    <div>

                        <h3>الفئات المستهدفة</h3>

                        <p>
                            حددي فئة واحدة أو أكثر،
                            ويمكن تخصيص كل فئة حسب القسم
                            أو المرحلة أو الصف أو الشعبة.
                        </p>

                    </div>

                    <button
                        type="button"
                        id="addTargetBtn"
                        class="add-target-btn"
                    >
                        + إضافة فئة أخرى
                    </button>

                </div>

                <div id="targetsContainer"></div>

            </div>

            {{-- أزرار الحفظ --}}

            <div class="form-actions">

                <button
                    type="submit"
                    id="saveAnnouncementBtn"
                    class="save-btn"
                >
                    حفظ الإعلان
                </button>

                <a
                    href="{{ route('announcements.index') }}"
                    class="cancel-btn"
                >
                    إلغاء
                </a>

            </div>

        </form>

    </main>

</div>

{{-- بيانات الصفوف --}}

<script id="announcementClassesData" type="application/json">
{!! json_encode(
    $classes->map(fn($class) => [
        'id' => $class->id,
        'name' => $class->name,
    ])->values(),
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) !!}
</script>

{{-- بيانات الشعب --}}

<script id="announcementSectionsData" type="application/json">
{!! json_encode(
    $sections->map(fn($section) => [
        'id' => $section->id,
        'name' => $section->name,
        'school_class_id' => $section->school_class_id,
    ])->values(),
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) !!}
</script>

{{-- البيانات القديمة عند وجود أخطاء --}}

<script id="announcementOldTargetsData" type="application/json">
{!! json_encode(
    old('targets', [['role' => 'all']]),
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
) !!}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const classes = JSON.parse(
        document.getElementById('announcementClassesData').textContent
    );

    const sections = JSON.parse(
        document.getElementById('announcementSectionsData').textContent
    );

    const oldTargets = JSON.parse(
        document.getElementById('announcementOldTargetsData').textContent
    );

    const container = document.getElementById('targetsContainer');
    const addButton = document.getElementById('addTargetBtn');

    const statusSelect = document.getElementById('status');
    const publishDateGroup = document.getElementById('publishDateGroup');
    const publishDateInput = document.getElementById('published_at');

    const announcementForm = document.getElementById('announcementForm');
    const saveButton = document.getElementById('saveAnnouncementBtn');

    const roles = {
        all: 'جميع مستخدمي النظام',
        parents: 'أولياء الأمور',
        teachers: 'المعلمون والمعلمات',
        students: 'الطلاب والطالبات',
        deputies: 'الوكلاء',
        supervisors: 'مشرفو المراحل',
        academic_council: 'المجلس الأكاديمي',
        quality: 'إدارة التطوير والجودة',
        administrative_staff: 'الإداريون والخدمات'
    };

    const stages = {
        primary: 'المرحلة الابتدائية',
        middle: 'المرحلة الإعدادية',
        secondary: 'المرحلة الثانوية'
    };

    let targetIndex = 0;

    /*
    |--------------------------------------------------------------------------
    | حماية النصوص عند إنشاء عناصر HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /*
    |--------------------------------------------------------------------------
    | إنشاء خيارات القائمة
    |--------------------------------------------------------------------------
    */

    function optionsFromObject(items, selectedValue, emptyLabel) {

        let html = `
            <option value="">
                ${escapeHtml(emptyLabel)}
            </option>
        `;

        Object.entries(items).forEach(([value, label]) => {

            const selected =
                String(selectedValue ?? '') === String(value)
                    ? 'selected'
                    : '';

            html += `
                <option
                    value="${escapeHtml(value)}"
                    ${selected}
                >
                    ${escapeHtml(label)}
                </option>
            `;
        });

        return html;
    }

    /*
    |--------------------------------------------------------------------------
    | خيارات الصفوف
    |--------------------------------------------------------------------------
    */

    function classOptions(selectedValue) {

        let html = '<option value="">جميع الصفوف</option>';

        classes.forEach(item => {

            const selected =
                String(selectedValue ?? '') === String(item.id)
                    ? 'selected'
                    : '';

            html += `
                <option
                    value="${escapeHtml(item.id)}"
                    ${selected}
                >
                    ${escapeHtml(item.name)}
                </option>
            `;
        });

        return html;
    }

    /*
    |--------------------------------------------------------------------------
    | خيارات الشعب حسب الصف
    |--------------------------------------------------------------------------
    */

    function sectionOptions(classId, selectedValue) {

        let html = '<option value="">جميع الشعب</option>';

        sections
            .filter(item =>
                !classId ||
                String(item.school_class_id) === String(classId)
            )
            .forEach(item => {

                const selected =
                    String(selectedValue ?? '') === String(item.id)
                        ? 'selected'
                        : '';

                html += `
                    <option
                        value="${escapeHtml(item.id)}"
                        ${selected}
                    >
                        ${escapeHtml(item.name)}
                    </option>
                `;
            });

        return html;
    }

    /*
    |--------------------------------------------------------------------------
    | إضافة فئة مستهدفة
    |--------------------------------------------------------------------------
    */

    function addTarget(data = {}) {

        const index = targetIndex++;

        const card = document.createElement('div');

        card.className = 'target-card';

        card.innerHTML = `
            <div class="target-header">

                <strong>
                    الفئة المستهدفة
                </strong>

                <button
                    type="button"
                    class="remove-target-btn"
                >
                    حذف الفئة
                </button>

            </div>

            <div class="target-grid">

                <div class="form-group">

                    <label>
                        الفئة المستهدفة
                        <span class="required">*</span>
                    </label>

                    <select
                        name="targets[${index}][role]"
                        class="target-role"
                        required
                    >
                        ${optionsFromObject(
                            roles,
                            data.role ?? 'all',
                            'اختاري الفئة'
                        )}
                    </select>

                </div>

                <div class="form-group">

                    <label>القسم</label>

                    <select
                        name="targets[${index}][section_gender]"
                        class="target-gender"
                    >

                        <option value="">
                            جميع الأقسام
                        </option>

                        <option
                            value="boys"
                            ${data.section_gender === 'boys' ? 'selected' : ''}
                        >
                            بنين
                        </option>

                        <option
                            value="girls"
                            ${data.section_gender === 'girls' ? 'selected' : ''}
                        >
                            بنات
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label>المرحلة الدراسية</label>

                    <select
                        name="targets[${index}][education_stage]"
                        class="target-stage"
                    >
                        ${optionsFromObject(
                            stages,
                            data.education_stage ?? '',
                            'جميع المراحل'
                        )}
                    </select>

                </div>

                <div class="form-group">

                    <label>الصف الدراسي</label>

                    <select
                        name="targets[${index}][school_class_id]"
                        class="target-class"
                    >
                        ${classOptions(data.school_class_id)}
                    </select>

                </div>

                <div class="form-group">

                    <label>الشعبة</label>

                    <select
                        name="targets[${index}][section_id]"
                        class="target-section"
                    >
                        ${sectionOptions(
                            data.school_class_id,
                            data.section_id
                        )}
                    </select>

                </div>

            </div>
        `;

        container.appendChild(card);

        const roleSelect = card.querySelector('.target-role');
        const genderSelect = card.querySelector('.target-gender');
        const stageSelect = card.querySelector('.target-stage');
        const classSelect = card.querySelector('.target-class');
        const sectionSelect = card.querySelector('.target-section');
        const removeButton = card.querySelector('.remove-target-btn');

        /*
        |--------------------------------------------------------------------------
        | عند اختيار جميع المستخدمين
        |--------------------------------------------------------------------------
        */

        function updateRoleFields() {

            const isAll = roleSelect.value === 'all';

            [
                genderSelect,
                stageSelect,
                classSelect,
                sectionSelect
            ].forEach(select => {

                select.disabled = isAll;

                if (isAll) {
                    select.value = '';
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | تحديث الشعب عند اختيار الصف
        |--------------------------------------------------------------------------
        */

        classSelect.addEventListener('change', function () {

            sectionSelect.innerHTML = sectionOptions(
                classSelect.value,
                ''
            );
        });

        /*
        |--------------------------------------------------------------------------
        | تغيير الفئة
        |--------------------------------------------------------------------------
        */

        roleSelect.addEventListener('change', updateRoleFields);

        /*
        |--------------------------------------------------------------------------
        | حذف فئة
        |--------------------------------------------------------------------------
        */

        removeButton.addEventListener('click', function () {

            const totalTargets =
                container.querySelectorAll('.target-card').length;

            if (totalTargets <= 1) {

                alert(
                    'يجب تحديد فئة مستهدفة واحدة على الأقل.'
                );

                return;
            }

            card.remove();
        });

        updateRoleFields();
    }

    /*
    |--------------------------------------------------------------------------
    | التحكم بموعد النشر
    |--------------------------------------------------------------------------
    */

    function updatePublicationFields() {

        const isScheduled =
            statusSelect.value === 'scheduled';

        publishDateGroup.style.display =
            isScheduled ? 'flex' : 'none';

        publishDateInput.required = isScheduled;

        publishDateInput.disabled = !isScheduled;
    }

    /*
    |--------------------------------------------------------------------------
    | إضافة فئة جديدة
    |--------------------------------------------------------------------------
    */

    addButton.addEventListener('click', function () {

        addTarget({
            role: ''
        });
    });

    /*
    |--------------------------------------------------------------------------
    | تغيير حالة الإعلان
    |--------------------------------------------------------------------------
    */

    statusSelect.addEventListener(
        'change',
        updatePublicationFields
    );

    /*
    |--------------------------------------------------------------------------
    | استرجاع الفئات عند أخطاء التحقق
    |--------------------------------------------------------------------------
    */

    if (
        Array.isArray(oldTargets) &&
        oldTargets.length > 0
    ) {

        oldTargets.forEach(target => {
            addTarget(target);
        });

    } else {

        addTarget({
            role: 'all'
        });
    }

    /*
    |--------------------------------------------------------------------------
    | منع الضغط المتكرر على زر الحفظ
    |--------------------------------------------------------------------------
    */

    announcementForm.addEventListener('submit', function (event) {

        const targetCards =
            container.querySelectorAll('.target-card');

        if (targetCards.length === 0) {

            event.preventDefault();

            alert(
                'يجب تحديد فئة مستهدفة واحدة على الأقل.'
            );

            return;
        }

        const selectedRoles = Array.from(
            container.querySelectorAll('.target-role')
        );

        const hasEmptyRole = selectedRoles.some(
            select => select.value === ''
        );

        if (hasEmptyRole) {

            event.preventDefault();

            alert(
                'يرجى اختيار الفئة المستهدفة لكل إعلان.'
            );

            return;
        }

        if (announcementForm.dataset.submitting === 'true') {

            event.preventDefault();
            return;
        }

        announcementForm.dataset.submitting = 'true';

        saveButton.disabled = true;
        saveButton.textContent = 'جارٍ الحفظ...';
    });

    /*
    |--------------------------------------------------------------------------
    | التهيئة
    |--------------------------------------------------------------------------
    */

    updatePublicationFields();

});
</script>

</body>
</html>