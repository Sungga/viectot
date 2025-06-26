<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Việc tốt</title>

  <!-- font google -->
  <link rel="stylesheet" href="assets/font/Inter/Inter-VariableFont_opsz,wght.ttf">

  <!-- font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

  @vite(['resources/css/listCv.css'])

  {{-- gọi thư viên pdf to img --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

  <style>
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
        height: auto !important;
    }

    .cv-top-left {
        width: 20%;
        height: auto !important;
    }

    .cv-avt {
        width: 100%;
        aspect-ratio: 1 / 1; /* Luôn là hình vuông */
        object-fit: cover !important;
        border-radius: 50%;
    }

    .cv-top-right {
        width: 70%;
        height: auto !important;
        
    }

    .cv-name {
        width: 100%;
        text-align: left;
        font-size: 1.4rem;
    }

    .cv-contact-info {
        height: auto !important;
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
        font-size: 1.2rem;
        margin: 1rem 0 0.05rem;
        color: #333;
        text-align: left;
    }

    .cv-section-content {
        font-size: 1rem;
        margin: 0.5rem 0;
        line-height: 1.5;
        color: #555;
    }
</style>
</head>

<body>
    <div class="container">
        <header style="z-index: 9998">
            <div class="header__left"><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i>Quay lại</a></div>
            <div class="header__center">
                <a href="{{ route('home') }}"><img src="{{ asset('storage/uploads/VIECTOT.png') }}" alt=""></a>
            </div>
            <div class="header__right">
                <ul class="header__menu">
                    <li>
                        <p class="header__item--right header__item--account"><img src="{{ asset('storage/uploads/user.svg') }}" alt="">{{ $candidate['name'] }}</p>
                        <ul class="header__menu--user">
                            <li><a href="#">Tài khoản</a></li>
                            <li><a href="#">Hồ sơ</a></li>
                            <li><a href="{{ route('logout') }}">Đăng xuất</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
    
        {{-- <section class="listCv">
            <div class="grid-container">
                <div class="listCv__top">
                    <a href="{{ route('makeCv.form') }}">Tạo cv</a>
                    <a class="listCv__top--addBtn">Thêm cv</a>
                </div>
                <div class="listCv__list">
                    <div class="listCv__list--top">
                        <h1 class="listCv__title">Danh sách cv của bạn</h1>
                        <div class="listCv__list--left"><span class="listCv__listLeft--quantity">{{ $cvs->count() }}</span>/<span class="listCv__listLeft--limit">{{ $candidate->cv_limit }}</span> <a id="btn-add-limit">Thêm giới hạn cv</a></div>
                    </div>
                    @foreach ($cvs as $cv)
                        <div class="listCv__item">
                            <div class="listCv__item--img">
                                <a href="#">
                                    <div style="background-color: var(--color-white);">
                                        @if ($cv->file_name == 'CV của hệ thống')
                                            
                                            @if ($generatedCvs[$cv->id]->template == 'classic')
                                                <div class="cv-container">
                                                    <div style="width: 20%; height: auto;">
                                                        @if($generatedCvs[$cv->id]->avatar_path)
                                                            <img src="{{ asset($generatedCvs[$cv->id]->avatar_path) }}" alt="Avatar" class="cv-avt">
                                                        @else
                                                            <img src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="cv-avt">
                                                        @endif
                                                    </div>
                                                    <h2 class="cv-name" style="text-align:center;">{{ $generatedCvs[$cv->id]->name }}</h2>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>Ngày sinh:</strong> {{ $generatedCvs[$cv->id]->dob ?? '...' }}</p>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>Giới tính:</strong> {{ $generatedCvs[$cv->id]->gender ?? '...' }}</p>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>SĐT:</strong> {{ $generatedCvs[$cv->id]->phone ?? '...' }}</p>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>Email:</strong> {{ $generatedCvs[$cv->id]->email ?? '...' }}</p>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>Địa chỉ:</strong> {{ $generatedCvs[$cv->id]->address ?? '...' }}</p>
                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Mục tiêu nghề nghiệp</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->career_goal ?? '')) !!}</p>
                                                    </div>
                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Học vấn</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->education ?? '')) !!}</p>
                                                    </div>
                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Kinh nghiệm</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->experience ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Dự án</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->projects ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Kỹ năng</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->skills ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Chứng chỉ</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->certificates ?? '')) !!}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="cv-container">
                                                    <div class="cv-top">
                                                        <div class="cv-top-left">
                                                            @if($generatedCvs[$cv->id]->avatar_path)
                                                                <img src="{{ asset($generatedCvs[$cv->id]->avatar_path) }}" alt="Avatar" class="cv-avt">
                                                            @else
                                                                <img src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="cv-avt">
                                                            @endif
                                                        </div>
                                                        <div class="cv-top-right">
                                                            <h1 class="cv-name">{{ $generatedCvs[$cv->id]->name }}</h1>
                                                            <div class="cv-contact-info">
                                                                <p style="font-size: 1rem;">Ngày sinh: {{ $generatedCvs[$cv->id]->dob }}</p>
                                                                <p style="font-size: 1rem;">Giới tính: {{ $generatedCvs[$cv->id]->gender }}</p>
                                                                <p style="font-size: 1rem;">Email: {{ $generatedCvs[$cv->id]->email }}</p>
                                                                <p style="font-size: 1rem;">Số điện thoại: {{ $generatedCvs[$cv->id]->phone }}</p>
                                                                <p style="font-size: 1rem;">Địa chỉ: {{ $generatedCvs[$cv->id]->address }}</p>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Mục tiêu nghề nghiệp</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->career_goal ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Học vấn</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->education ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Kinh nghiệm</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->experience ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Dự án</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->projects ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Kỹ năng</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->skills ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Chứng chỉ</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->certificates ?? '')) !!}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        <canvas id="pdf-canvas-{{ $cv->id }}" style="width: 100%; height: auto; border-radius: 8px;"></canvas>
                                    </div>
                                </a>
                            </div>
                            <div class="listCv__item--name">{{ $cv->name }}</div>
                            <div class="listCv__item--bottom">
                                @if ($cv->status == 1)
                                    <a href="#"><i class="fa-solid fa-star listCv__item--main"></i></a>
                                @else
                                    <a href="{{ route('changeMain.cv', ['id' => $cv->id]) }}"><i class="fa-solid fa-star"></i></a>
                                @endif
                                <a href="{{ route('delete.cv', ['id' => $cv->id]) }}" class="listCv__item--del">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section> --}}

        <session class="addCv">
            <div class="addCv__container">
                <i class="fa-solid fa-circle-xmark addCv__exit"></i>
                <h2 class="addCv__title">Thêm Cv</h2>
                <form action="{{ route('upload.cv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="name" id="" class="addCv__name" placeholder="Tên của cv" required>
                    <label for="cv" class="addCv__cv" id="drop-zone">Tải lên CV của bạn</label>
                    <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx" style="display: none" required>
                    <button class="addCv__btn" type="submit">Tải lên</button>
                </form>
            </div>
        </session>

        <div class="buyCv">
            {{-- <div class="buyCv__container">
                <i class="fa-solid fa-circle-xmark buyCv__exit"></i>
                <h2 class="buyCv__title">Chọn gói muốn mua</h2>
                <div class="buyCv__list">
                    <div class="buyCv__item">
                        <h3 class="buyCv__name">Gói cơ bản</h3>
                        <p class="buyCv__about">Tăng giới hạn lưu trữ thêm <span style="color: var(--color-base);">+ 3 CV</span></p>
                        <p class="buyCv__price">Giá 19.000₫</p>
                        <form action="{{ route('payment') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="buy3">
                            <input type="hidden" name="amount" value="19000">
                            <button class="buyCv__btn">Mua</button>
                        </form>
                    </div>
                    <div class="buyCv__item">
                        <h3 class="buyCv__name">Gói nâng cao</h3>
                        <p class="buyCv__about">Tăng giới hạn lưu trữ thêm <span style="color: var(--color-base);">+ 10 CV</span></p>
                        <p class="buyCv__price">Giá 39.000₫</p>
                        <form action="{{ route('payment') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="buy10">
                            <input type="hidden" name="amount" value="39000">
                            <button class="buyCv__btn">Mua</button>
                        </form>
                    </div>
                    <div class="buyCv__item">
                        <h3 class="buyCv__name">Gói không giới hạn</h3>
                        <p class="buyCv__about">Lưu trữ CV <span style="color: var(--color-base);">không giới hạn</span></p>
                        <p class="buyCv__price">Giá 100.000₫</p>
                        <form action="{{ route('payment') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="buy9999">
                            <input type="hidden" name="amount" value="100000">
                            <button class="buyCv__btn">Mua</button>
                        </form>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    {{-- {{ route('vnpay.ipn') }} --}}
    {{-- <a href="{{ route('microsoft.auth') }}">Tạo và chỉnh sửa file Word Online</a><br>
    <a href="{{ route('word.download') }}">Tải file Word về server</a> --}}

    {{-- Sẽ thông báo khi bạn thêm cv mà đã vượt quá giới hạn lưu trữ --}}
    @if (session('error'))
        <script>
            window._alertErrorMsg = @json(session('error'));
        </script>
    @endif
    @if(session('info'))
        <script>
            window._alertInfoMsg = @json(session('inf   o'));
        </script>
    @endif
    @if(session('success'))
        <script>
            window._alertSuccessMsg = @json(session('success'));
        </script>
    @endif

    @if (session('debug'))
        <script>console.log("Có vào được WITH INFO");</script>
    @endif

    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>

    {{-- ------------------<< show page 1 of file pdf >>-------------------------------- --}}
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cvs = @json($cvs);

            pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

            cvs.forEach(cv => {
                const canvas = document.getElementById(`pdf-canvas-${cv.id}`);
                const ctx = canvas.getContext("2d");

                if(cv.file_name == 'CV của hệ thống') {

                }else {
                    const url = `/storage/cv/${cv.file_name}`;
    
                    pdfjsLib.getDocument(url).promise.then(pdf => {
                        pdf.getPage(1).then(page => {
                            const viewport = page.getViewport({ scale: 1.0 });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
    
                            const renderContext = {
                                canvasContext: ctx,
                                viewport: viewport
                            };
                            page.render(renderContext);
                        });
                    });
                }

            });
        });
    </script> --}}

    @vite(['resources/js/listCv.js'])
</body>
</html>
