<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>صلة | الإعدادات الأكاديمية</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f1f5f9;
            color: #1e293b;
            font-family: Tahoma, Arial, sans-serif;
        }

        .page {
            max-width: 1100px;
            margin: 35px auto;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        h1 {
            font-size: 26px;
            margin: 0 0 10px;
        }

        h2 {
            font-size: 19px;
            margin-top: 0;
        }

        .description {
            color: #64748b;
            font-size: 14px;
            line-height: 1.8;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #e2e8f0;
            margin-bottom: 22px;
        }

        .active-card {
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 13px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: white;
            font-size: 14px;
        }

        .field {
            margin-bottom: 20px;
        }

        .period-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        button,
        .button {
            display: inline-block;
            border: none;
            padding: 12px 20px;
            border-radius: 9px;
            cursor: pointer;
            text-decoration: none;
            font-family: inherit;
            font-size: 13px;
        }

        .primary {
            background: #0f172a;
            color: white;
        }

        .secondary {
            background: #e2e8f0;
            color: #334155;
        }

        .add {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .remove {
            background: #fee2e2;
            color: #b91c1c;
        }

        .activate {
            background: #dcfce7;
            color: #166534;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }

        th {
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-active {
            background: #dcfce7;
            color: #166534;
        }

        .badge-inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }

        .checkbox label {
            margin: 0;
        }

        @media (max-width: 700px) {

            .grid {
                grid-template-columns: 1fr;
            }

            .header {
                flex-wrap: wrap;
                gap: 15px;
            }

            .page {
                margin: 10px auto;
            }
        }
            .layout {
    display: flex;
    min-height: 100vh;
    width: 100%;
}

.sidebar {
    width: 260px;
    min-width: 260px;
    min-height: 100vh;
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
    display: block;
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

.page {
    max-width: none;
    margin: 0;
    padding: 0;
}

body {
    background: #f5f7fb;
    font-family: "Segoe UI", Tahoma, Arial, sans-serif;
}

@media (max-width: 900px) {
    .layout {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        min-width: 0;
        min-height: auto;
    }

    .main {
        padding: 15px;
    }
}
    </style>

</head>

<body>

<div class="layout">

    @include('partials.sidebar')

    <main class="main">

        <div class="page">

    <div class="header">

        <div>

            <h1>الإعدادات الأكاديمية</h1>

            <div class="description">
                إدارة العام الدراسي والفصل الدراسي
                وفترات تقييم الطلاب في نظام صلة.
            </div>

        </div>

        <a href="{{ route('dashboard') }}"
           class="button secondary">

            العودة للرئيسية

        </a>

    </div>

    @if(session('success'))

        <div class="success">

            {{ session('success') }}

        </div>

    @endif

    @if($errors->any())

        <div class="errors">

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="card active-card">

        <h2>الفصل الدراسي النشط</h2>

        @if($activeSetting)

            <p>

                العام الدراسي:

                <strong>
                    {{ $activeSetting->academic_year }}
                </strong>

            </p>

            <p>

                الفصل الدراسي:

                <strong>

                    {{ $activeSetting->semester === 'first'
                        ? 'الفصل الأول'
                        : 'الفصل الثاني' }}

                </strong>

            </p>

            <p>

                فترات التقييم:

                {{ implode('، ',
                    $activeSetting->evaluation_periods ?? []) }}

            </p>

        @else

            <p>لم يتم تحديد فصل دراسي نشط بعد.</p>

        @endif

    </div>

    <div class="card">

        <h2>إضافة أو تحديث الإعدادات الأكاديمية</h2>

        <form method="POST"
              action="{{ route('academic-settings.store') }}">

            @csrf

            <div class="grid">

                <div class="field">

                    <label>العام الدراسي *</label>

                    <input
                        type="text"
                        name="academic_year"
                        value="{{ old('academic_year') }}"
                        placeholder="2026-2027"
                        maxlength="20"
                        required
                    >

                </div>

                <div class="field">

                    <label>الفصل الدراسي *</label>

                    <select name="semester" required>

                        <option value="">
                            اختر الفصل الدراسي
                        </option>

                        <option value="first"
                            @selected(old('semester') === 'first')>

                            الفصل الدراسي الأول

                        </option>

                        <option value="second"
                            @selected(old('semester') === 'second')>

                            الفصل الدراسي الثاني

                        </option>

                    </select>

                </div>

            </div>

            <div class="field">

                <label>فترات التقييم *</label>

                <div id="periods">

                    @foreach(old('evaluation_periods',
                        ['الشهر الأول',
                         'الشهر الثاني',
                         'الاختبار النهائي']) as $period)

                        <div class="period-row">

                            <input
                                type="text"
                                name="evaluation_periods[]"
                                value="{{ $period }}"
                                maxlength="50"
                                required
                            >

                            <button
                                type="button"
                                class="remove"
                                onclick="removePeriod(this)">

                                حذف

                            </button>

                        </div>

                    @endforeach

                </div>

                <button
                    type="button"
                    class="add"
                    onclick="addPeriod()">

                    + إضافة فترة تقييم

                </button>

            </div>

            <div class="checkbox">

                <input
                    type="checkbox"
                    id="is_active"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', true))
                >

                <label for="is_active">

                    تفعيل هذا الفصل الدراسي

                </label>

            </div>

            <button type="submit" class="primary">

                حفظ الإعدادات الأكاديمية

            </button>

        </form>

    </div>

    <div class="card">

        <h2>الإعدادات الأكاديمية المسجلة</h2>

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>العام الدراسي</th>

                        <th>الفصل الدراسي</th>

                        <th>فترات التقييم</th>

                        <th>الحالة</th>

                        <th>الإجراء</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($settings as $setting)

                        <tr>

                            <td>
                                {{ $setting->academic_year }}
                            </td>

                            <td>

                                {{ $setting->semester === 'first'
                                    ? 'الفصل الأول'
                                    : 'الفصل الثاني' }}

                            </td>

                            <td>

                                {{ implode('، ',
                                    $setting->evaluation_periods ?? []) }}

                            </td>

                            <td>

                                @if($setting->is_active)

                                    <span class="badge badge-active">

                                        نشط

                                    </span>

                                @else

                                    <span class="badge badge-inactive">

                                        غير نشط

                                    </span>

                                @endif

                            </td>

                            <td>

                                @unless($setting->is_active)

                                    <form method="POST"
                                          action="{{ route(
                                            'academic-settings.activate',
                                            $setting->id
                                          ) }}">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="activate">

                                            تفعيل

                                        </button>

                                    </form>

                                @else

                                    —

                                @endunless

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                لا توجد إعدادات أكاديمية مسجلة.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

    function addPeriod() {

        const container =
            document.getElementById('periods');

        if (container.children.length >= 20) {

            alert('الحد الأقصى 20 فترة تقييم.');

            return;
        }

        const row = document.createElement('div');

        row.className = 'period-row';

        row.innerHTML = `
            <input
                type="text"
                name="evaluation_periods[]"
                placeholder="اسم فترة التقييم"
                maxlength="50"
                required
            >

            <button
                type="button"
                class="remove"
                onclick="removePeriod(this)">

                حذف

            </button>
        `;

        container.appendChild(row);
    }

    function removePeriod(button) {

        const container =
            document.getElementById('periods');

        if (container.children.length <= 1) {

            alert('يجب وجود فترة تقييم واحدة على الأقل.');

            return;
        }

        button.closest('.period-row').remove();
    }

</script>
</main>

</div>
</body>

</html>