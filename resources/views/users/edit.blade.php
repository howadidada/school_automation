<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تعديل المستخدم</title>

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
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .topbar h2 {
            font-size: 26px;
            margin-bottom: 5px;
        }

        .topbar p {
            color: #6b7280;
        }

        .back-btn {
            background: #e5e7eb;
            color: #1f2937;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 9px;
        }

        .form-box {
            max-width: 850px;
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #4f46e5;
        }

        .save-btn {
            margin-top: 22px;
            background: #111827;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
        }

        .save-btn:hover {
            background: #374151;
        }

        .save-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .errors ul {
            padding-right: 20px;
        }

        .info-box {
            background: #eff6ff;
            color: #1e40af;
            padding: 15px 18px;
            border-radius: 9px;
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.9;
            border: 1px solid #bfdbfe;
        }

        .hint {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.7;
        }

        .required {
            color: #dc2626;
        }

        .hidden {
            display: none !important;
        }

        .status-badge {
            display: inline-block;
            width: fit-content;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }

            .main {
                padding: 20px;
            }

            .topbar {
                flex-wrap: wrap;
                gap: 15px;
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
                <h2>تعديل المستخدم</h2>
                <p>تعديل بيانات الحساب والدور الوظيفي</p>
            </div>

            <a href="{{ route('users.index') }}" class="back-btn">
                العودة للمستخدمين
            </a>

        </div>

        @if($errors->any())
            <div class="errors" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-box">

            <div class="info-box">
                <strong>إدارة بيانات الحساب</strong>
                <br>
                يمكنك تعديل البيانات الأساسية والدور الوظيفي
                والمرحلة والقسم حسب طبيعة الوظيفة.
                <br>
                لا يمكن تغيير كلمة مرور المستخدم من هذه الصفحة.
            </div>

            <form
                method="POST"
                action="{{ route('users.update', $user) }}"
                id="editUserForm"
            >

                @csrf
                @method('PUT')

                <div class="form-grid">

                    <!-- الاسم -->
                    <div class="form-group">

                        <label for="name">
                            الاسم الكامل
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            maxlength="255"
                            required
                        >

                    </div>

                    <!-- البريد الإلكتروني -->
                    <div class="form-group">

                        <label for="email">
                            البريد الإلكتروني
                            <span class="required">*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            maxlength="255"
                            dir="ltr"
                            required
                        >

                        <span class="hint">
                            تغيير البريد الإلكتروني لحساب ينتظر التفعيل
                            يتطلب إرسال رابط تفعيل جديد.
                        </span>

                    </div>

                    <!-- رقم الهاتف -->
                    <div class="form-group">

                        <label for="phone">
                            رقم الهاتف
                        </label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            maxlength="30"
                        >

                    </div>

                    <!-- الدور الوظيفي -->
                    <div class="form-group">

                        <label for="role_id">
                            الدور الوظيفي
                            <span class="required">*</span>
                        </label>

                        <select
                            id="role_id"
                            name="role_id"
                            required
                        >

                            <option value="">
                                اختر الدور الوظيفي
                            </option>

                            @foreach($roles as $role)

                                <option
                                    value="{{ $role->id }}"
                                    data-role="{{ $role->name }}"
                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}
                                >
                                    {{ $role->description ?? $role->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- المرحلة الدراسية -->
                    <div
                        class="form-group hidden"
                        id="educationStageGroup"
                    >

                        <label for="education_stage">
                            المرحلة الدراسية
                            <span class="required">*</span>
                        </label>

                        <select
                            id="education_stage"
                            name="education_stage"
                        >

                            <option value="">
                                اختر المرحلة الدراسية
                            </option>

                            <option
                                value="primary"
                                {{ old('education_stage', $user->education_stage) === 'primary' ? 'selected' : '' }}
                            >
                                المرحلة الابتدائية
                            </option>

                            <option
                                value="middle"
                                {{ old('education_stage', $user->education_stage) === 'middle' ? 'selected' : '' }}
                            >
                                المرحلة الإعدادية
                            </option>

                            <option
                                value="secondary"
                                {{ old('education_stage', $user->education_stage) === 'secondary' ? 'selected' : '' }}
                            >
                                المرحلة الثانوية
                            </option>

                        </select>

                    </div>

                    <!-- القسم -->
                    <div
                        class="form-group hidden"
                        id="genderSectionGroup"
                    >

                        <label for="gender_section">
                            القسم
                            <span class="required">*</span>
                        </label>

                        <select
                            id="gender_section"
                            name="gender_section"
                        >

                            <option value="">
                                اختر القسم
                            </option>

                            <option
                                value="boys"
                                {{ old('gender_section', $user->gender_section) === 'boys' ? 'selected' : '' }}
                            >
                                بنين
                            </option>

                            <option
                                value="girls"
                                {{ old('gender_section', $user->gender_section) === 'girls' ? 'selected' : '' }}
                            >
                                بنات
                            </option>

                        </select>

                    </div>

                    <!-- حالة الحساب -->
                    <div class="form-group full">

                        <label>حالة الحساب الحالية</label>

                        @if(!$user->is_active && $user->password === null)

                            <span class="status-badge status-pending">
                                بانتظار تفعيل الحساب
                            </span>

                            <span class="hint">
                                يجب أن ينشئ المستخدم كلمة مروره
                                من رابط التفعيل أولًا.
                            </span>

                        @elseif($user->is_active)

                            <span class="status-badge status-active">
                                الحساب نشط
                            </span>

                        @else

                            <span class="status-badge status-inactive">
                                الحساب معطّل
                            </span>

                        @endif

                    </div>

                </div>

                <button
                    type="submit"
                    class="save-btn"
                    id="submitButton"
                >
                    حفظ التعديلات
                </button>

            </form>

        </div>

    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const roleSelect =
            document.getElementById('role_id');

        const educationStageGroup =
            document.getElementById('educationStageGroup');

        const genderSectionGroup =
            document.getElementById('genderSectionGroup');

        const educationStageSelect =
            document.getElementById('education_stage');

        const genderSectionSelect =
            document.getElementById('gender_section');

        const form =
            document.getElementById('editUserForm');

        const submitButton =
            document.getElementById('submitButton');

        function updateFields() {

            const selectedOption =
                roleSelect.options[roleSelect.selectedIndex];

            const selectedRole =
                selectedOption?.dataset.role || '';

            const isSupervisor =
                selectedRole === 'supervisor';

            const isVicePrincipal =
                selectedRole === 'vice_principal';

            const showGenderSection =
                isSupervisor || isVicePrincipal;

            // المرحلة الدراسية للمشرف فقط
            educationStageGroup.classList.toggle(
                'hidden',
                !isSupervisor
            );

            educationStageSelect.required = isSupervisor;
            educationStageSelect.disabled = !isSupervisor;

            // القسم للمشرف والوكيل
            genderSectionGroup.classList.toggle(
                'hidden',
                !showGenderSection
            );

            genderSectionSelect.required = showGenderSection;
            genderSectionSelect.disabled = !showGenderSection;
        }

        roleSelect.addEventListener('change', updateFields);

        updateFields();

        form.addEventListener('submit', function () {

            if (!form.checkValidity()) {
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'جارٍ حفظ التعديلات...';
        });

    });
</script>

</body>
</html>