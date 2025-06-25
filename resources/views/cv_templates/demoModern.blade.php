<!-- resources/views/cv_templates/modern.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $cv->name }} - CV</title>
    <style>
        html {
            font-size: 62.5%; /* 1rem = 10px */
            scroll-behavior: smooth;
        }

        body { font-family: Arial, sans-serif; margin: 0; padding: 0; display: flex; background-color: #f4f4f4; width: 100%; }
        
        .container {
            margin: 20px auto;
        }
        
        .cv-container {
            width: 100%;
            max-width: 800px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            padding: 20px;
        }

        .cv-top {
            width: 100%;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .cv-top-left {
            width: 20%;
        }

        .cv-avt {
            width: 100%;
            aspect-ratio: 1 / 1; /* Luôn là hình vuông */
            object-fit: cover;
            border-radius: 50%;
        }

        .cv-top-right {
            width: 70%;
        }

        .cv-name {
            width: 100%;
            text-align: left;
            font-size: 2.8rem;
        }

        .cv-contact-info p {
            width: 100%;
            text-align: left;
            font-size: 1.4rem;
            margin: 0.5rem 0;
        }

        .cv-section {
            width: 100%
        }

        .cv-section-title {
            font-size: 2rem;
            margin: 1rem 0 0.5rem;
            color: #333;
        }

        .cv-section-content {
            font-size: 1.2rem;
            margin: 0.5rem 0;
            line-height: 1.5;
            color: #555;
        }

        .cv-save {
            padding: 12px 20px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cv-container">
            <div class="cv-top">
                <div class="cv-top-left">
                    @if($cv->avatar_path)
                        <img src="{{ asset($cv->avatar_path) }}" alt="Avatar" class="cv-avt">
                    @else
                        <img src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="cv-avt">
                    @endif
                </div>
                <div class="cv-top-right">
                    <h1 class="cv-name">{{ $cv->name }}</h1>
                    <div class="cv-contact-info">
                        <p>Ngày sinh: {{ $cv->dob }}</p>
                        <p>Giới tính: {{ $cv->gender }}</p>
                        <p>Email: {{ $cv->email }}</p>
                        <p>Số điện thoại: {{ $cv->phone }}</p>
                        <p>Địa chỉ: {{ $cv->address }}</p>
                    </div>
                    <p style="text-align:center; color:#555;">
                    </p>
                </div>
            </div>


            <div class="cv-section">
                <h2 class="cv-section-title">Mục tiêu nghề nghiệp</h2>
                <hr>
                <p class="cv-section-content">{!! nl2br(e($cv->career_goal ?? '')) !!}</p>
            </div>

            <div class="cv-section">
                <h2 class="cv-section-title">Học vấn</h2>
                <hr>
                <p class="cv-section-content">{!! nl2br(e($cv->education ?? '')) !!}</p>
            </div>

            <div class="cv-section">
                <h2 class="cv-section-title">Kinh nghiệm</h2>
                <hr>
                <p class="cv-section-content">{!! nl2br(e($cv->experience ?? '')) !!}</p>
            </div>

            <div class="cv-section">
                <h2 class="cv-section-title">Dự án</h2>
                <hr>
                <p class="cv-section-content">{!! nl2br(e($cv->projects ?? '')) !!}</p>
            </div>

            <div class="cv-section">
                <h2 class="cv-section-title">Kỹ năng</h2>
                <hr>
                <p class="cv-section-content">{!! nl2br(e($cv->skills ?? '')) !!}</p>
            </div>

            <div class="cv-section">
                <h2 class="cv-section-title">Chứng chỉ</h2>
                <hr>
                <p class="cv-section-content">{!! nl2br(e($cv->certificates ?? '')) !!}</p>
            </div>

            <form action="{{ route('makeCv.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Ẩn toàn bộ dữ liệu dưới dạng input để gửi lại --}}
                <input type="hidden" name="nameCV" value="{{ $cv->nameCV }}">
                <input type="hidden" name="name" value="{{ $cv->name }}">
                <input type="hidden" name="dob" value="{{ $cv->dob }}">
                <input type="hidden" name="gender" value="{{ $cv->gender }}">
                <input type="hidden" name="phone" value="{{ $cv->phone }}">
                <input type="hidden" name="email" value="{{ $cv->email }}">
                <input type="hidden" name="address" value="{{ $cv->address }}">
                <input type="hidden" name="career_goal" value="{{ $cv->career_goal }}">
                <input type="hidden" name="template" value="{{ $cv->template }}">
                
                {{-- Mảng bạn encode JSON rồi gửi --}}
                {{-- <input type="hidden" name="education" value="{{ json_encode($cv->education, JSON_UNESCAPED_UNICODE) }}">
                <input type="hidden" name="experience" value="{{ json_encode($cv->experience, JSON_UNESCAPED_UNICODE) }}">
                <input type="hidden" name="projects" value="{{ json_encode($cv->projects, JSON_UNESCAPED_UNICODE) }}">
                <input type="hidden" name="skills" value="{{ json_encode($cv->skills, JSON_UNESCAPED_UNICODE) }}">
                <input type="hidden" name="certificates" value="{{ json_encode($cv->certificates, JSON_UNESCAPED_UNICODE) }}"> --}}
                
                <input type="hidden" name="education" value="{!! nl2br(e($cv->education ?? '')) !!}">
                <input type="hidden" name="experience" value="{!! nl2br(e($cv->experience ?? '')) !!}">
                <input type="hidden" name="projects" value="{!! nl2br(e($cv->projects ?? '')) !!}">
                <input type="hidden" name="skills" value="{!! nl2br(e($cv->skills ?? '')) !!}">
                <input type="hidden" name="certificates" value="{!! nl2br(e($cv->certificates ?? '')) !!}">

                {{-- <input type="hidden" name="avatar" value="{{ json_encode($cv->avatar_path) }}"> --}}
                <input type="hidden" name="avatar" value="{{ $cv->avatar_path }}">

                {{-- Nếu có avatar thì gửi file lại hoặc gửi đường dẫn (tùy bạn xử lý) --}}
                {{-- Có thể lưu avatar khi demo hoặc bắt buộc upload lại ở bước lưu --}}

                <button class="cv-save" type="submit">Lưu CV</button>
            </form>
        </div>
    </div>
</body>
</html>
