<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>صلة | تعديل مادة دراسية</title>

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
            flex-shrink: 0;
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
            gap: 20px;
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
            white-space: nowrap;
        }

        .back-btn:hover {
            background: #d1d5db;
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

        input,
        textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            background: white;
        }

        input:focus,
        textarea:focus {
            border-color: #2563eb;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
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

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
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
                <h2>تعديل المادة الدراسية</h2>

                <p>
                    تعديل اسم المادة ووصفها
                </p>
            </div>

            <a
                href="{{ route('subjects.index') }}"
                class="back-btn"
            >
                العودة للمواد الدراسية
            </a>

        </div>


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


        <div class="form-box">

            <form
                method="POST"
                action="{{ route('subjects.update', $subject) }}"
            >

                @csrf
                @method('PUT')


                <div class="form-group">

                    <label for="name">
                        اسم المادة
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $subject->name) }}"
                        placeholder="اكتب اسم المادة"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="description">
                        وصف المادة
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        placeholder="اكتب وصف المادة إن وجد"
                    >{{ old('description', $subject->description) }}</textarea>

                </div>


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

</body>

</html>