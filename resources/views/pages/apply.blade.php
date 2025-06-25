@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/pages/apply.css'])    

    {{-- js bieu do --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
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

        .listCv__contCv {
            height: 200px;
            overflow: hidden;
            margin-bottom: 8px;
        }
    </style>
@endsection

@section('content')
    <section class="apply">
        <div class="grid-container">
            <form action="{{ route('apply', ['id_post' => $id_post]) }}" method="post">
                @csrf
                <div class="apply__container">
                    <div class="apply__logoCompany"><img style="border-radius: 50%;" src="{{ asset('storage/uploads/'.$post->branch->company->logo) }}" alt=""></div>
                    <h1 class="apply__title">Ứng tuyển công việc: {{ $post->title }}</h1>
                    <h2 class="apply__branch">Tên chi nhánh: {{ $post->branch->name }}</h2>
                    <h2 class="apply__company">Thuộc công ty: {{ $post->branch->company->name }}</h2>
                    {{-- <input type="hidden" name="idPost" value="{{ $id_post }}"> --}}
                    <textarea class="apply__mail" name="profileSummary" id="" rows="10" placeholder="Hãy viết một đoạn giới thiệu tuyệt vời để tạo ấn tượng đối với nhà tuyển dụng" required></textarea>
                    <h3 class="apply__titleCv">Chọn CV bạn muốn gửi <a href="{{ route('listCv') }}" class="apply__addCv">Tạo hoặc thêm CV mới</a></h3>
                    <div class="listCv__list">
                        {{-- list cv in here --}}
                        @foreach ($cvs as $cv)
                            {{-- <div class="listCv__item">
                                <div class="listCv__item--img">
                                    <a href="#">
                                        <div>
                                            <canvas id="pdf-canvas-{{ $cv->id }}" style="width: 100%; height: auto; border-radius: 8px;"></canvas>
                                        </div>
                                    </a>
                                </div>
                                <div class="listCv__item--name">{{ $cv->name }}</div>
                                <div class="listCv__item--bottom">
                                    <input hidden class="listCv__item--select" type="radio" name="idCv" id="idCv{{ $cv->id }}" value="{{ $cv->id }}" required>
                                    <label class="listCv__item--selectLabel" for="idCv{{ $cv->id }}">Chọn</label>
                                </div>
                            </div> --}}
                            <div class="listCv__item">
                                <a href="{{ route('employer.candidateProfile', ['id' => $cv->id_user]) }}">
                                    <div class="listCv__contCv">
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
                                        <div class="listCv__cv"><canvas id="pdf-canvas-{{ $cv->id }}" style="width: 100%; height: auto; border-radius: 8px;"></canvas></div>
                                    </div>
                                    <div class="listCv__candidate">
                                        <img src="{{ asset('storage/uploads/'.$cv->avatar) }}" alt="" class="listCv__avt">
                                        <div class="listCv__name">{{ $cv->candidate_name }}</div>
                                    </div>
                                </a>   
                                <div class="listCv__item--name">{{ $cv->name }}</div>
                                <div class="listCv__item--bottom">
                                    <input hidden class="listCv__item--select" type="radio" name="idCv" id="idCv{{ $cv->id }}" value="{{ $cv->id }}" required>
                                    <label class="listCv__item--selectLabel" for="idCv{{ $cv->id }}">Chọn</label>
                                </div> 
                            </div>
                        @endforeach
                    </div>
                    <button class="apply__btn">Ứng tuyển</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('appendage')
    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>

    <!-- chat bot -->
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
      intent="WELCOME"
      chat-title="Chưa có data câu hỏi mô!"
      agent-id="020b6d32-f258-4915-93aa-7290ea1ae3c6"
      language-code="vi"
    ></df-messenger>
@endsection

@section('js')
    <!-- Load JS -->
    @vite(['resources/js/pages/apply.js'])
    
    {{-- ------------------<< show page 1 of file pdf >>-------------------------------- --}}
      {{-- gọi thư viên pdf to img --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const cvs = @json($cvs);

        pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

        cvs.forEach(cv => {
            const canvas = document.getElementById(`pdf-canvas-${cv.id}`);
            const ctx = canvas.getContext("2d");

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
        });
    });
    </script>
@endsection