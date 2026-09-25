<aside class="sidebar">

    <div class="brand">
        <h1>صلة</h1>
        <span>SILA</span>
        <br>
        <span>للتواصل بين المدرسة والأسرة</span>
    </div>

    <nav class="menu">

        <a
            href="{{ route('dashboard') }}"
            class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            الرئيسية
        </a>

        @if(auth()->user()->hasPermission('إدارة المستخدمين'))
            <a
                href="{{ route('users.index') }}"
                class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}"
            >
                إدارة المستخدمين
            </a>
        @endif

        @if(auth()->user()->hasPermission('إدارة الأدوار والصلاحيات'))
            <a
                href="{{ route('roles.index') }}"
                class="menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}"
            >
                الأدوار والصلاحيات
            </a>
        @endif

        @if(auth()->user()->hasPermission('إدارة الطلاب'))
            <a
                href="{{ route('students.index') }}"
                class="menu-item {{ request()->routeIs('students.*') ? 'active' : '' }}"
            >
                الطلاب
            </a>
        @endif

        @if(auth()->user()->hasPermission('إدارة المعلمين'))
            <a
                href="{{ route('teachers.index') }}"
                class="menu-item {{ request()->routeIs('teachers.*') ? 'active' : '' }}"
            >
                المعلمون
            </a>
        @endif

        @if(auth()->user()->hasPermission('إدارة أولياء الأمور'))
            <a
                href="{{ route('parents.index') }}"
                class="menu-item {{ request()->routeIs('parents.*') ? 'active' : '' }}"
            >
                أولياء الأمور
            </a>
        @endif

        @if(auth()->user()->hasPermission('إدارة الفصول والشعب'))
            <a
                href="{{ route('classes.index') }}"
                class="menu-item {{
                    request()->routeIs('classes.*') ||
                    request()->routeIs('sections.*')
                    ? 'active'
                    : ''
                }}"
            >
                الفصول والشعب
            </a>
        @endif

        @if(auth()->user()->hasPermission('إدارة المواد الدراسية'))
            <a
                href="{{ route('subjects.index') }}"
                class="menu-item {{ request()->routeIs('subjects.*') ? 'active' : '' }}"
            >
                المواد الدراسية
            </a>
        @endif

        @if(auth()->user()->hasPermission('إسناد المعلمين'))
            <a
                href="{{ route('teacher-assignments.index') }}"
                class="menu-item {{ request()->routeIs('teacher-assignments.*') ? 'active' : '' }}"
            >
                إسناد المعلمين
            </a>
        @endif

        @if(auth()->user()->hasPermission('رفع الجدول الدراسي'))
            <a
                href="{{ route('schedule-files.index') }}"
                class="menu-item {{ request()->routeIs('schedule-files.*') ? 'active' : '' }}"
            >
                الجدول الدراسي
            </a>
        @endif

    </nav>

    <div class="logout-area">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-btn">
                تسجيل الخروج
            </button>
        </form>

    </div>

</aside>