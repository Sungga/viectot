@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>
    @vite(['resources/css/chat/listChat.css'])
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 20px;
        }

        .makeCv {
            margin-top: calc(var(--header-height) + 20px);
        }

        h1 {
            text-align: center;
            color: #2f3640;
            margin-bottom: 20px;
            font-size: 2.8rem
        }

        form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h3 {
            font-size: 1.2rem;
            color: #40739e;
            margin-top: 30px;
            margin-bottom: 10px;
            border-left: 4px solid #3498db;
            padding-left: 10px;
        }

        label {
            font-size: 1.2rem;
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="file"],
        select,
        textarea {
            font-size: 1.2rem;
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #dcdde1;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
        }

        textarea {
            font-size: 1.2rem;
            resize: vertical;
        }

        button[type="submit"] {
            font-size: 1.2rem;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            font-size: 1.2rem;
            background-color: #2980b9;
        }
    </style>
@endsection

@section('content')
    <section class="makeCv">
        <div class="grid-container">
            <div class="makeCv__container">
                
                <form action="{{ route('makeCv.demo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h1>HÃY NHẬP THÔNG TIN CV BẠN MUỐN TẠO</h1>
                    <label for="name">Tên CV:</label>
                    <input type="text" name="nameCV" required >
                    <h3>Thông tin cá nhân:</h3>
                    <label for="name">Họ và tên:</label>
                    <input type="text" name="name" id="name" required><br>

                    <label for="dob">Ngày sinh:</label>
                    <input type="date" name="dob" id="dob"><br>

                    <label for="gender">Giới tính:</label>
                    <select name="gender" id="gender">
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select><br>

                    <label for="phone">Số điện thoại:</label>
                    <input type="text" name="phone" id="phone"><br>

                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email"><br>

                    <label for="address">Địa chỉ:</label>
                    <input type="text" name="address" id="address"><br>

                    <label for="avatar">Ảnh đại diện:</label>
                    <input type="file" name="avatar" id="avatar"><br>

                    <h3>Mục tiêu nghề nghiệp:</h3>
                    <textarea name="career_goal" rows="4" cols="50"></textarea><br>

                    <h3>Trình độ học vấn:</h3>
                    <label for="education">Thông tin học vấn:</label>
                    <textarea name="education" rows="4" cols="50"></textarea><br>

                    <h3>Kinh nghiệm làm việc:</h3>
                    <label for="experience">Thông tin kinh nghiệm:</label>
                    <textarea name="experience" rows="4" cols="50"></textarea><br>

                    <h3>Dự án hoặc hoạt động:</h3>
                    <label for="projects">Dự án/hoạt động:</label>
                    <textarea name="projects" rows="4" cols="50"></textarea><br>

                    <h3>Kỹ năng:</h3>
                    <label for="skills">Danh sách kỹ năng:</label>
                    <textarea name="skills" rows="4" cols="50" placeholder="VD: HTML, CSS, Laravel, Giao tiếp, Làm việc nhóm,..."></textarea><br>

                    <h3>Chứng chỉ & Giải thưởng:</h3>
                    <label for="certificates">Chứng chỉ/Giải thưởng:</label>
                    <textarea name="certificates" rows="4" cols="50"></textarea><br>

                    <h3>Chọn mẫu CV:</h3>
                    <select name="template" required>
                        <option value="classic">Mẫu CV cổ điển</option>
                        <option value="modern">Mẫu CV hiện đại</option>
                    </select><br><br>

                    <button type="submit">Tạo CV</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('appendage')
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
      intent="WELCOME"
      chat-title="Chưa có data câu hỏi mô!"
      agent-id="020b6d32-f258-4915-93aa-7290ea1ae3c6"
      language-code="vi"
    ></df-messenger>
@endsection

@section('js')
    @vite(['resources/js/chat/listChat.js'])
@endsection
