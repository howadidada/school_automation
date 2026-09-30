<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تعديل إسناد المعلم</title>

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

        .info-box {
            background: #eef2ff;
            color: #3730a3;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            line-height: 1.8;
            font-size: 14px;
        }

        .stage-box {
            display: none;
            padding: 12px 14px;
            margin-bottom: 18px;
            background: #ecfdf5;
            color: #065f46;
            border-radius: 9px;
            font-size: 14px;
        }

        .stage-box.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .form-box {
            max-width: 750px;
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 18px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            background: white;
        }

        select:focus {
            border-color: #4f46e5;
        }

        select:disabled {
            background: #f3f4f6;
            cursor: not-allowed;
        }

        .save-btn {
            margin-top: 10px;
            background: #111827;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
        }

        .save-btn:hover {
            background: #1f2937;
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

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
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
                <h2>تعديل إسناد المعلم</h2>

                <p>
                    تعديل المعلم أو المادة أو الفصل والشعبة وفق المرحلة التعليمية
                </p>
            </div>

            <a
                href="{{ route('teacher-assignments.index') }}"
                class="back-btn"
            >
                العودة للإسنادات
            </a>

        </div>

        {{-- أخطاء التحقق --}}
        @if($errors->any())

            <div class="errors">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- رسالة الخطأ المخصصة --}}
        @if(session('error'))

            <div class="alert-error">

                {{ session('error') }}

            </div>

        @endif


        {{-- معلومات --}}
        <div class="info-box">

            يمكنك تعديل الإسناد بالكامل.

            <br>

            كل معلم يمكن إسناده فقط إلى الفصول والشعب التابعة
            <strong>لمرحلته التعليمية</strong>.

            <br>

            المرحلة الابتدائية،
            والمرحلة المتوسطة / الإعدادية،
            والمرحلة الثانوية.

        </div>


        <div class="form-box">

            <form
                method="POST"
                action="{{ route('teacher-assignments.update', $teacherAssignment) }}"
            >

                @csrf
                @method('PUT')


                {{-- ================================================= --}}
                {{-- المعلم --}}
                {{-- ================================================= --}}

                <div class="form-group">

                    <label for="teacher_id">
                        المعلم
                    </label>

                    <select
                        id="teacher_id"
                        name="teacher_id"
                        required
                    >

                        <option value="">
                            اختر المعلم
                        </option>

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                data-stage="{{ $teacher->education_stage }}"
                                {{
                                    old(
                                        'teacher_id',
                                        $teacherAssignment->teacher_id
                                    ) == $teacher->id
                                        ? 'selected'
                                        : ''
                                }}
                            >

                                {{ $teacher->user?->name ?? 'معلم بدون اسم' }}

                                -

                                @if($teacher->education_stage === 'primary')

                                    المرحلة الابتدائية

                                @elseif($teacher->education_stage === 'middle')

                                    المرحلة المتوسطة / الإعدادية

                                @elseif($teacher->education_stage === 'secondary')

                                    المرحلة الثانوية

                                @else

                                    المرحلة غير محددة

                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- عرض مرحلة المعلم --}}
                {{-- ================================================= --}}

                <div
                    id="stageBox"
                    class="stage-box"
                ></div>


                {{-- ================================================= --}}
                {{-- المادة --}}
                {{-- ================================================= --}}

                <div class="form-group">

                    <label for="subject_id">
                        المادة الدراسية
                    </label>

                    <select
                        id="subject_id"
                        name="subject_id"
                        required
                    >

                        <option value="">
                            اختر المادة
                        </option>

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                {{
                                    old(
                                        'subject_id',
                                        $teacherAssignment->subject_id
                                    ) == $subject->id
                                        ? 'selected'
                                        : ''
                                }}
                            >

                                {{ $subject->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- الفصل والشعبة --}}
                {{-- ================================================= --}}

                <div class="form-group">

                    <label for="section_id">
                        الفصل والشعبة
                    </label>

                    <select
                        id="section_id"
                        name="section_id"
                        required
                    >

                        <option value="">
                            اختر الفصل والشعبة
                        </option>

                        @foreach($sections as $section)

                            <option
                                value="{{ $section->id }}"
                                data-stage="{{ $section->schoolClass?->education_stage }}"
                                {{
                                    old(
                                        'section_id',
                                        $teacherAssignment->section_id
                                    ) == $section->id
                                        ? 'selected'
                                        : ''
                                }}
                            >

                                {{ $section->schoolClass?->name ?? 'بدون فصل' }}

                                -

                                الشعبة {{ $section->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- الحفظ --}}
                {{-- ================================================= --}}

                <button
                    type="submit"
                    class="save-btn"
                >
                    حفظ التعديلات
                </button>

            </form>

        </div>

    </main>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const teacherSelect =
            document.getElementById('teacher_id');

        const sectionSelect =
            document.getElementById('section_id');

        const stageBox =
            document.getElementById('stageBox');


        /*
        |--------------------------------------------------------------------------
        | حفظ جميع الشعب الأصلية
        |--------------------------------------------------------------------------
        */

        const originalSections =
            Array.from(
                sectionSelect.querySelectorAll(
                    'option[data-stage]'
                )
            ).map(
                option => option.cloneNode(true)
            );


        /*
        |--------------------------------------------------------------------------
        | الشعبة الحالية
        |--------------------------------------------------------------------------
        */

        let selectedSectionId =
            @json(
                (string) old(
                    'section_id',
                    $teacherAssignment->section_id
                )
            );


        /*
        |--------------------------------------------------------------------------
        | اسم المرحلة بالعربي
        |--------------------------------------------------------------------------
        */

        function getStageName(stage) {

            if (stage === 'primary') {

                return 'المرحلة الابتدائية';

            }

            if (stage === 'middle') {

                return 'المرحلة المتوسطة / الإعدادية';

            }

            if (stage === 'secondary') {

                return 'المرحلة الثانوية';

            }

            return 'مرحلة غير محددة';

        }


        /*
        |--------------------------------------------------------------------------
        | فلترة الشعب حسب مرحلة المعلم
        |--------------------------------------------------------------------------
        */

        function filterSections(
            keepCurrentSection = true
        ) {

            const teacherOption =
                teacherSelect.options[
                    teacherSelect.selectedIndex
                ];

            const teacherStage =
                teacherOption?.dataset?.stage || '';


            /*
            |--------------------------------------------------------------------------
            | تنظيف القائمة
            |--------------------------------------------------------------------------
            */

            sectionSelect.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | لم يتم اختيار معلم
            |--------------------------------------------------------------------------
            */

            if (!teacherSelect.value) {

                sectionSelect.disabled = true;

                sectionSelect.innerHTML =
                    '<option value="">اختر المعلم أولاً</option>';

                stageBox.style.display = 'none';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | مرحلة المعلم غير محددة
            |--------------------------------------------------------------------------
            */

            if (!teacherStage) {

                sectionSelect.disabled = true;

                sectionSelect.innerHTML =
                    '<option value="">مرحلة هذا المعلم غير محددة</option>';

                stageBox.classList.add('error');

                stageBox.style.display = 'block';

                stageBox.textContent =
                    'يجب تحديد المرحلة التعليمية لهذا المعلم أولاً.';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | عرض مرحلة المعلم
            |--------------------------------------------------------------------------
            */

            stageBox.classList.remove('error');

            stageBox.style.display = 'block';

            stageBox.textContent =
                'المرحلة التعليمية للمعلم: ' +
                getStageName(teacherStage);


            /*
            |--------------------------------------------------------------------------
            | الخيار الافتراضي
            |--------------------------------------------------------------------------
            */

            const placeholder =
                document.createElement('option');

            placeholder.value = '';

            placeholder.textContent =
                'اختر الفصل والشعبة';

            sectionSelect.appendChild(
                placeholder
            );


            /*
            |--------------------------------------------------------------------------
            | الفصول والشعب المطابقة للمرحلة
            |--------------------------------------------------------------------------
            */

            const allowedSections =
                originalSections.filter(
                    option =>
                        option.dataset.stage ===
                        teacherStage
                );


            /*
            |--------------------------------------------------------------------------
            | إضافة الشعب المسموحة
            |--------------------------------------------------------------------------
            */

            allowedSections.forEach(
                option => {

                    const clonedOption =
                        option.cloneNode(true);

                    clonedOption.selected = false;

                    if (
                        keepCurrentSection &&
                        String(clonedOption.value) ===
                        String(selectedSectionId)
                    ) {

                        clonedOption.selected = true;

                    }

                    sectionSelect.appendChild(
                        clonedOption
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | لا توجد شعب لهذه المرحلة
            |--------------------------------------------------------------------------
            */

            if (allowedSections.length === 0) {

                sectionSelect.disabled = true;

                sectionSelect.innerHTML =
                    '<option value="">لا توجد فصول أو شعب لهذه المرحلة</option>';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | تفعيل القائمة
            |--------------------------------------------------------------------------
            */

            sectionSelect.disabled = false;


            /*
            |--------------------------------------------------------------------------
            | إعادة تحديد الشعبة الحالية
            |--------------------------------------------------------------------------
            */

            if (
                keepCurrentSection &&
                selectedSectionId
            ) {

                const exists =
                    allowedSections.some(
                        option =>
                            String(option.value) ===
                            String(selectedSectionId)
                    );

                if (exists) {

                    sectionSelect.value =
                        selectedSectionId;

                } else {

                    sectionSelect.value = '';

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | عند تغيير المعلم
        |--------------------------------------------------------------------------
        */

        teacherSelect.addEventListener(
            'change',
            function () {

                selectedSectionId = '';

                filterSections(false);

                sectionSelect.value = '';

            }
        );


        /*
        |--------------------------------------------------------------------------
        | تشغيل الفلترة عند فتح الصفحة
        |--------------------------------------------------------------------------
        */

        filterSections(true);

    }
);

</script>

</body>
</html>