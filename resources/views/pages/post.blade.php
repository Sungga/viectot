@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/pages/post.css'])
    @vite(['resources/css/pages/addBasicInfo.css'])
@endsection

@section('content')
    <section class="post">
        <div class="grid-container">
            <div class="post__container">
                <div class="post__path">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <i class="fa-solid fa-angle-right"></i>
                    <a href="#">Danh mục</a>
                    <i class="fa-solid fa-angle-right"></i>
                    <a href="#">{{ $post->title }}</a>
                </div>

                @if ($post->deadline < now())
                    <div class="post__expired" style="width: 100%; text-align: center; padding: 20px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px; font-size: 1.6rem; font-weight: 600;">
                        <p>Hết hạn nộp hồ sơ</p>
                    </div>
                @endif

                <div class="post__left">
                    <div class="post__leftTop">
                        <h2 class="post__leftTop--title">{{ $post->title }}</h2>
                        <div class="post__leftTop--about">
                            <div>
                                <div><i class="fa-solid fa-dollar-sign"></i></div>
                                <p><span class="post__leftTop--aboutTitle">Thu nhập</span><span class="post__leftTop--aboutValue">{{ $post->salary_show }}</span></p>
                            </div>
                            <div>
                                <div><i class="fa-solid fa-location-dot"></i></div>
                                <p><span class="post__leftTop--aboutTitle">Địa điểm</span><span class="post__leftTop--aboutValue">{{ $post->branch->branchProvince->name }}</span></p>
                            </div>
                            <div>
                                <div><i class="fa-regular fa-hourglass-half"></i></div>
                                <p><span class="post__leftTop--aboutTitle">Kinh nghiệm</span><span class="post__leftTop--aboutValue">{{ $post->experience_show }}</span></p>
                            </div>
                        </div>
                        <div class="post__leftTop--deadline">
                            <p><i class="fa-solid fa-clock"></i> Hạn nộp hồ sơ: {{ \Carbon\Carbon::parse($post->deadline)->format('d/m/Y') }}</p>
                        </div>
                        <div class="post__leftTop--quantity">
                            <p><i class="fa-solid fa-user"></i> Số lượng tuyển: {{ $post->quantity }}</p>
                        </div>
                        <div class="post__topLeft--bottom">
                            @if (session('user.role') == '0')
                                @if ($post->status == 'lock')
                                    <a style="width:100%;background:var(--color-green)" href="{{ route('admin.lockPost', ['id' => $post->id]) }}" class="post__topLeft--apply">Mở khóa bài viết</a>
                                @else
                                    <a style="width:100%;background:var(--color-red)" href="{{ route('admin.unlockPost', ['id' => $post->id]) }}" class="post__topLeft--apply">Khóa bài viết</a>
                                @endif
                                {{-- <a style="width:calc(70% - 8px);" href="{{ route('apply.form', ['id_post' => $post->id]) }}" class="post__topLeft--apply">Ứng tuyển</a> --}}
                            @elseif(session('user.role') == '2')
                                <a style="width:100%;" href="{{ route('apply.form', ['id_post' => $post->id]) }}" class="post__topLeft--apply">Ứng tuyển</a>
                            @elseif(session('user.role') == '1')
                                @if ($post->status == 'lock')
                                    <a style="width:100%;background:var(--color-red)" href="{{ route('complaint.form', ['id_post' => $post->id]) }}" class="post__topLeft--apply">Khiếu nại</a>
                                @endif
                            @endif
                            {{-- <a href="#" class="post__topLeft--save">Lưu tin</a> --}}
                        </div>
                    </div>
                    <div class="post__leftBottom">
                        <h2 class="post__leftBottom--title">Chi tiết tin tuyển dụng</h2>
                        <p><a href="#" class="post__leftBottom--category">Chuyên ngành {{ $categoryName }}</a></p>
                        <p class="post__leftBottom--skillTitle">Các kỹ năng yêu cầu: 
                            @foreach(json_decode($post->skills, true) as $item)
                                <a href="#" class="post__leftBottom--skill">{{ $item['value'] }}</a>
                            @endforeach
                        </p>
                        <h3 class="post__leftBottom--descTitle">Mô tả công việc</h3>
                        <div class="post__leftBottom--desc">
                            {!! $post->description !!}
                        </div>
                        <div>
                            @if(session('user.role') == '2')
                                <a href="{{ route('apply.form', ['id_post' => $post->id]) }}" class="post__leftBottom--apply">Ứng tuyển ngay</a>
                            @endif
                            {{-- <a href="#" class="post__leftBottom--save">Lưu tin</a> --}}
                        </div>
                        <p class="post__leftBottom--bottom"><i class="fa-solid fa-circle-info"></i> Báo cáo tin tuyển dụng: Nếu bạn thấy rằng tin tuyển dụng này không đúng hoặc có dấu hiệu lừa đảo, hãy <a href="{{ route('report.form', ['id_post' => $post->id]) }}">phản ánh với chúng tôi.</a></p>
                    </div>
                </div>
                <div class="post__right">
                    <div class="post__rightTop">
                        <div class="post__rightTop--top">
                            <div class="post__rightTop--logo">
                                <img style="width: 100px; height: 100px;" src="{{ asset('storage/uploads/'.$post->branch->company->logo) }}" alt="">
                            </div>
                            <a href="{{ route('branch.show', ['id_branch' => $post->branch->id]) }}" class="post__rightTop--branch">{{ $post->branch->name }}</a>
                        </div>
                        <a href="{{ route('company.show', ['id_company' => $post->branch->company->id]) }}" class="post__rightTop--company">{{ $post->branch->company->name }}</a>
                        <p class="post__rightTop--address">Địa chỉ: {{ $address }}</p>
                        <p class="post__rightTop--seeCompany"><a href="{{ route('branch.show', ['id_branch' => $post->branch->id]) }}">Xem trang công ty <i class="fa-solid fa-arrow-right-to-city"></i></a></p>
                    </div>
                    <div class="top__rightCenter">
                        <h3 class="top__rightCenter--title">Thông tin chung</h3>
                        <div class="top__rightCenter--item">
                            <div class="top__rightCenter--icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <p><span class="post__rightCenter--title">Học vấn</span><span class="post__rightCenter--value">{{ $post->degree_show }}</span></p>
                        </div>
                        <div class="top__rightCenter--item">
                            <div class="top__rightCenter--icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <p><span class="post__rightCenter--title">Số lượng</span><span class="post__rightCenter--value">{{ $post->quantity }}</span></p>
                        </div>
                        <div class="top__rightCenter--item">
                            <div class="top__rightCenter--icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <p><span class="post__rightCenter--title">Hình thức làm việc</span><span class="post__rightCenter--value">{{ $post->work_mode_show }}</span></p>
                        </div>
                    </div>
                    <div class="post__rightBottom">
                        <p><i class="fa-solid fa-circle-info"></i> Báo cáo tin tuyển dụng: Nếu bạn thấy rằng tin tuyển dụng này không đúng hoặc có dấu hiệu lừa đảo, hãy <a href="{{ route('report.form', ['id_post' => $post->id]) }}">phản ánh với chúng tôi.</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- code thong bao su kien --}}
    @if (session('error'))
    <script>
        window._alertErrorMsg = @json(session('error'));
    </script>
    @endif
    @if(session('info'))
        <script>
            window._alertInfoMsg = @json(session('info'));
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
    @vite(['resources/js/pages/post.js'])
@endsection