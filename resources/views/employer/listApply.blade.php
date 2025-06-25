@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>
    @vite(['resources/css/employer/listApplication.css'])
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
@endsection

<?php
    // B1: Tạo map từ id_user => candidate
    $candidateMap = [];
    foreach ($candidates as $candidateItem) {
        $candidateMap[$candidateItem['id_user']] = $candidateItem;
    }

    // B2: Gộp vào từng phần tử của $cvs
    foreach ($cvs as &$cv) {
        $id_user = $cv['id_user'];
        if (isset($candidateMap[$id_user])) {
            // $cv['candidate'] = $candidateMap[$id_user]; // Gộp toàn bộ
            // Hoặc chỉ lấy 1 vài trường:
            $cv['candidate_name'] = $candidateMap[$id_user]['name'];
            $cv['id_user'] = $candidateMap[$id_user]['id'];
            $cv['avatar'] = $candidateMap[$id_user]['avatar'];
            $cv['province'] = $candidateMap[$id_user]['province'];
            $cv['id_category'] = $candidateMap[$id_user]['id_category'];
        }
    }
    unset($cv); // tránh lỗi tham chiếu
?>

@section('content')
    <section class="listApplication">
        <div class="grid-container">
            <div class="listApplication__container">
                <div class="apply__logoCompany"><img src="{{ asset('storage/uploads/'.$post->branch->company->logo) }}" alt=""></div>
                <h1 class="apply__title">Công việc: {{ $post->title }}</h1>
                <h2 class="apply__branch">Tên chi nhánh: {{ $post->branch->name }}</h2>
                <h2 class="apply__company">Thuộc công ty: {{ $post->branch->company->name }}</h2>
                <div class="listApplication__list">
                    <h3 class="listApplication__list--title">Danh sách ứng tuyển</h3>
                    <div class="listCv__list">
                        @foreach ($cvs as $cv)
                            @continue($cv->statusApply != 'pending')
                            <div class="listCv__item">
                                <a href="{{ route('employer.candidateProfile', ['id' => $cv->id_user, 'id_apply' =>$cv->apply->id]) }}">
                                    <div class="listCv__contCv">
                                        {{-- <div style="background-color: var(--color-white);"> --}}
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
                                    <div class="listCv__province">Thành phố Hồ Chí Minh</div>
                                    <br>
                                    <div class="listCv__category">Công nghệ thông tin</div>
                                </a>    
                                <div class="listCv__item--bottom">
                                    <div class="listCv__item--form" style="display: inline;">
                                        <input type="hidden" class="id_candidate" value="{{ $cv->id_user }}">
                                        <input type="hidden" class="id_apply" value="{{ $cv->apply->id }}">
                                        {{-- @csrf --}}
                                        <button type="submit" class="listCv__item--btn listCv__item--btnPass">Duyệt</button>
                                    </div>
    
                                    <form class="listCv__item--form" action="{{ route('reject', ['id_post' => $post->id, 'id_candidate' => $cv->id_user]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="listCv__item--btn">Từ chối</button>
                                    </form> 
                                </div>  
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="listApplication__list">
                    <h3 class="listApplication__list--title">Danh sách trúng tuyển</h3>
                    <div class="listCv__list">
                        @foreach ($cvs as $cv)
                            @continue($cv->statusApply != 'pass')
                            <div class="listCv__item">
                                <a href="{{ route('employer.candidateProfile', ['id' => $cv->id_user, 'id_apply' =>$cv->apply->id]) }}">
                                    <div class="listCv__contCv">
                                        {{-- <div style="background-color: var(--color-white);"> --}}
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
                                    <div class="listCv__province">Thành phố Hồ Chí Minh</div>
                                    <br>
                                    <div class="listCv__category">Công nghệ thông tin</div>
                                </a>  
                                <div class="listCv__item--bottom">
                                    <a class="listCv__item--contact" href="{{ route('chat', ['type' => 'branch', 'id_branch' => $post->branch->id, 'id_candidate' => $cv->id_user, 'id_apply' => $cv->apply->id]) }}">Liên hệ</a>
                                </div>  
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="formPass">
        <form class="formPass__container" action="{{ route('pass') }}" method="post">
            @csrf
            <p class="formPass__exit"><i class="fa-solid fa-circle-xmark formPass__exit--icon"></i></p>
            <input type="hidden" name="id_post" value="{{ $post->id }}" required>
            <input type="hidden" name="id_candidate" value="" required id="id_candidate">
            <input type="hidden" name="id_apply" value="" required id="id_apply">
            <input type="text" name="message" value="Chúng tôi thấy CV của bạn rất phù hợp với công việc. Hãy liên hệ với chúng tôi sớm để được làm việc." required>
            <button>Gửi</button>
        </form>
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
    @vite(['resources/js/employer/listApplication.js'])
            {{-- js show img cv (pdf to img) --}}
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
