<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صلة | تعديل توزيع المعلم</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0;font-family:"Segoe UI",Tahoma,Arial,sans-serif}
        body{background:#f5f7fb;color:#1f2937}
        .layout{display:flex;min-height:100vh}
        .sidebar{width:260px;min-width:260px;background:#111827;color:white;padding:25px 18px;display:flex;flex-direction:column}
        .brand{margin-bottom:35px;padding:0 10px}
        .brand h1{font-size:30px;margin-bottom:5px}
        .brand span{font-size:13px;color:#cbd5e1}
        .menu{display:flex;flex-direction:column;gap:8px}
        .menu-item{display:block;text-decoration:none;color:#d1d5db;padding:13px 14px;border-radius:9px;font-size:15px}
        .menu-item:hover,.menu-item.active{background:#1f2937;color:white}
        .logout-area{margin-top:auto;padding-top:25px}
        .logout-btn{width:100%;padding:11px;border:0;border-radius:8px;background:#ef4444;color:white;cursor:pointer}
        .main{flex:1;min-width:0;padding:30px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:25px}
        .topbar h2{font-size:26px;margin-bottom:6px}
        .topbar p{color:#6b7280;font-size:14px;line-height:1.8}
        .back-btn{background:#e5e7eb;color:#1f2937;text-decoration:none;padding:11px 18px;border-radius:9px;white-space:nowrap}
        .form-box{max-width:750px;background:white;padding:28px;border-radius:15px;box-shadow:0 4px 14px rgba(0,0,0,.05)}
        .form-group{display:flex;flex-direction:column;gap:7px;margin-bottom:18px}
        label{font-size:14px;font-weight:600}
        select,input{width:100%;padding:11px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;background:white;outline:none}
        select:focus{border-color:#4f46e5}
        select:disabled{background:#f3f4f6;cursor:not-allowed}
        .readonly-field{background:#f1f5f9!important;color:#334155}
        .hint{font-size:12px;color:#64748b;line-height:1.8}
        .info-box{background:#eef2ff;color:#3730a3;padding:15px 18px;border-radius:10px;margin-bottom:20px;line-height:1.9;font-size:14px}
        .stage-box{display:none;padding:12px 14px;margin-bottom:18px;background:#ecfdf5;color:#065f46;border-radius:9px;font-size:14px}
        .stage-box.error{background:#fee2e2;color:#991b1b}
        .errors,.alert-error{background:#fee2e2;color:#991b1b;padding:14px 18px;border-radius:9px;margin-bottom:20px;line-height:1.8}
        .errors ul{padding-right:20px}
        .save-btn{margin-top:10px;background:#111827;color:white;border:0;padding:12px 22px;border-radius:9px;cursor:pointer;font-size:14px}
        .save-btn:hover{background:#1f2937}
        .save-btn:disabled{background:#94a3b8;cursor:not-allowed}
        @media(max-width:900px){
            .layout{flex-direction:column}
            .sidebar{width:100%;min-width:0}
            .main{padding:18px}
            .topbar{flex-wrap:wrap}
        }
    </style>
</head>
<body>
<div class="layout">
    @include('partials.sidebar')

    <main class="main">
        <div class="topbar">
            <div>
                <h2>تعديل توزيع المعلم</h2>
                <p>تعديل المعلم أو الصف والشعبة مع الالتزام بالمادة الأساسية والمرحلة التعليمية.</p>
            </div>
            <a href="{{ route('teacher-assignments.index') }}" class="back-btn">
                العودة للتوزيعات
            </a>
        </div>

        @if($errors->any())
            <div class="errors">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="info-box">
            <strong>تعديل التوزيع:</strong>
            يمكن تغيير المعلم أو الصف والشعبة، بشرط أن تكون الشعبة
            ضمن مرحلة المعلم التعليمية. المادة تُحدد تلقائيًا
            من بيانات المعلم ولا يمكن اختيار مادة أخرى.
        </div>

        <div class="form-box">
            <form
                method="POST"
                action="{{ route('teacher-assignments.update', $teacherAssignment) }}"
                id="assignmentForm"
            >
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="teacher_id">المعلم</label>
                    <select id="teacher_id" name="teacher_id" required>
                        <option value="">اختر المعلم</option>

                        @foreach($teachers as $teacher)
                            <option
                                value="{{ $teacher->id }}"
                                data-stage="{{ $teacher->education_stage }}"
                                data-subject-id="{{ $teacher->subject_id }}"
                                data-subject-name="{{ $teacher->subject?->name ?? '' }}"
                                @selected(old('teacher_id', $teacherAssignment->teacher_id) == $teacher->id)
                            >
                                {{ $teacher->user?->name ?? 'معلم بدون اسم' }}
                                -
                                {{ match($teacher->education_stage) {
                                    'primary' => 'الابتدائية',
                                    'middle' => 'الإعدادية',
                                    'secondary' => 'الثانوية',
                                    default => 'مرحلة غير محددة'
                                } }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="stageBox" class="stage-box" role="status"></div>

                <div class="form-group">
                    <label for="subject_display">المادة الأساسية للمعلم</label>
                    <input
                        type="text"
                        id="subject_display"
                        class="readonly-field"
                        placeholder="تظهر المادة بعد اختيار المعلم"
                        readonly
                    >
                    <input type="hidden" id="subject_id" name="subject_id" value="">
                    <span class="hint">
                        لا يمكن تغيير المادة من صفحة التوزيع.
                        لتغييرها، يجب أولًا معالجة توزيعات المعلم من إدارة المعلمين.
                    </span>
                </div>

                <div class="form-group">
                    <label for="section_id">الصف والشعبة</label>
                    <select id="section_id" name="section_id" required disabled>
                        <option value="">اختر المعلم أولًا</option>
                        @foreach($sections as $section)
                            <option
                                value="{{ $section->id }}"
                                data-stage="{{ $section->schoolClass?->education_stage }}"
                            >
                                {{ $section->schoolClass?->name ?? 'بدون صف' }}
                                - الشعبة {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" id="saveBtn" class="save-btn" disabled>
                    حفظ التعديلات
                </button>
            </form>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const teacherSelect = document.getElementById('teacher_id');
    const subjectId = document.getElementById('subject_id');
    const subjectDisplay = document.getElementById('subject_display');
    const sectionSelect = document.getElementById('section_id');
    const stageBox = document.getElementById('stageBox');
    const saveBtn = document.getElementById('saveBtn');
    const form = document.getElementById('assignmentForm');

    const originalSections = Array.from(
        sectionSelect.querySelectorAll('option[data-stage]')
    ).map(option => option.cloneNode(true));

    const selectedSectionId = @json(
        (string) old('section_id', $teacherAssignment->section_id)
    );

    function stageName(stage) {
        return {
            primary: 'المرحلة الابتدائية',
            middle: 'المرحلة المتوسطة / الإعدادية',
            secondary: 'المرحلة الثانوية'
        }[stage] || 'مرحلة غير محددة';
    }

    function updateForm(keepSelectedSection = false) {
        const option = teacherSelect.selectedOptions[0];
        const stage = option?.dataset.stage || '';
        const subject = option?.dataset.subjectId || '';
        const subjectName = option?.dataset.subjectName || '';

        subjectId.value = subject;
        subjectDisplay.value = subjectName;

        sectionSelect.innerHTML = '<option value="">اختر الصف والشعبة</option>';
        sectionSelect.disabled = true;
        saveBtn.disabled = true;

        stageBox.style.display = 'none';
        stageBox.classList.remove('error');

        if (!teacherSelect.value) {
            subjectId.value = '';
            subjectDisplay.value = '';
            sectionSelect.innerHTML = '<option value="">اختر المعلم أولًا</option>';
            return;
        }

        stageBox.style.display = 'block';

        if (!subject || !subjectName) {
            stageBox.classList.add('error');
            stageBox.textContent = 'هذا المعلم ليس لديه مادة أساسية محددة. حددي مادته من إدارة المعلمين أولًا.';
            return;
        }

        if (!['primary', 'middle', 'secondary'].includes(stage)) {
            stageBox.classList.add('error');
            stageBox.textContent = 'يجب تحديد مرحلة تعليمية صحيحة للمعلم أولًا.';
            return;
        }

        stageBox.textContent =
            'مرحلة المعلم: ' + stageName(stage) +
            ' | المادة: ' + subjectName;

        const allowed = originalSections.filter(
            section => section.dataset.stage === stage
        );

        allowed.forEach(section => {
            const cloned = section.cloneNode(true);
            cloned.selected = false;
            sectionSelect.appendChild(cloned);
        });

        if (allowed.length === 0) {
            stageBox.classList.add('error');
            stageBox.textContent = 'لا توجد صفوف وشعب متاحة لمرحلة هذا المعلم.';
            sectionSelect.innerHTML = '<option value="">لا توجد شعب متاحة</option>';
            return;
        }

        sectionSelect.disabled = false;

        if (
            keepSelectedSection &&
            allowed.some(section => section.value === selectedSectionId)
        ) {
            sectionSelect.value = selectedSectionId;
        }

        updateSaveButton();
    }

    function updateSaveButton() {
        saveBtn.disabled = !(
            teacherSelect.value &&
            subjectId.value &&
            sectionSelect.value &&
            !sectionSelect.disabled
        );
    }

    teacherSelect.addEventListener('change', () => updateForm(false));
    sectionSelect.addEventListener('change', updateSaveButton);

    form.addEventListener('submit', function (event) {
        updateSaveButton();
        if (saveBtn.disabled) event.preventDefault();
    });

    updateForm(true);
});
</script>
</body>
</html>