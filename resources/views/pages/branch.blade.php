@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/pages/branch.css'])
@endsection

@section('content')
    <div class="branch">
        <div class="grid-container">
            <div class="branch__container">
                <div class="branch__path">
                    <a href="#">Danh sách công ty</a>
                    &#62;
                    <a href="#">{{ $branch->name }}</a>
                </div>
                <div class="branch__top">
                    <div class="branch__background">
                        <div class="branch__title">
                            <img src="{{ asset('storage/uploads/'.$branch->company->logo) }}" alt="" class="branch__logo">
                            <h1 class="branch__name">
                                {{ $branch->name }}
                                <p style="font-size: 1.4rem; text-align: left; border-left: 4px solid #9c9c9c; padding-left: 4px;">{{ $branch->company->name }}</p>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="branch__center">
                    <div class="branch__center--left">
                        <div class="branch__centerLeft--top">
                            <h2 class="branch__center--title">
                                Giới thiệu công ty
                            </h2>
                            <div class="branch__center--desc">
                                {!! $branch->desc !!}
                            </div>
                        </div>
                        <div class="branch__centerLeft--top">
                            <h2 class="branch__center--title">
                                Tuyển dụng
                            </h2>
                            <div class="branch__center--desc">
                                <div class="listJob__list">
                                    @if ($branch->post->isEmpty())
                                        <h2 style="width: 100%; text-align: center; margin-bottom: 20px;">Không có bài đăng tuyển dụng</h2>
                                    @else
                                    @endif
                                    @foreach ($branch->post as $post)
                                        @if ($post->status == 'lock')
                                            @continue
                                        @endif
                                        <div class="listJob__item">
                                            <div class="listJob__item--body">
                                                <a href="{{ route('post.show', ['id_post' => $post->id]) }}">
                                                    <div class="listJob__item--left">
                                                        <img src="{{ asset('storage/uploads/'.$post->branch->company->logo) }}" alt="" class="listJob__item--logo">
                                                    </div>
                                                </a>
                                                <div class="listJob__item--right">
                                                    <a href="{{ route('post.show', ['id_post' => $post->id]) }}" class="listJob__item--title">{{ $post->title }}</a>
                                                    <a href="#" class="listJob__item--company">{{ $post->branch->company->name }}</a>
                                                </div>
                                            </div>
                                            <div class="listJob__item--footer">
                                                <div class="listJob__item--footerItem">
                                                    <span class="listJob__item--info">{{ $post->salary_show }}</span>
                                                    <span class="listJob__item--info">{{ $post->work_mode_show }}</span>
                                                </div>
                                            </div>
                                            <div class="listJob__item--desc" id="bigBlock">
                                                <h3 class="listJob__item--descTitle">{{ $post->title }}</h3>
                                                <p class="listJob__item--descDeadline">Hạn đến: {{ \Carbon\Carbon::parse($post->deadline)->format('d/m/Y') }} - Số lượng: {{ $post->quantity }}</p>
                                                <div class="listJob__item--descItem">
                                                    <span>{{ $post->salary_show }}</span>
                                                    <span>{{ $post->work_mode_show }}</span>
                                                    @if ($post->gender_show != '')
                                                        <span>Giới tính: {{ $post->gender_show }}</span>
                                                    @endif
                                                    <span>{{ $post->experience_show }}</span>
                                                    <span>{{ $post->degree_show }}</span>
                                                </div>
                                                <div class="listJob__item--descDesc">{!! $post->description !!}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div style="text-align: center; color: var(--color-base);"><a href="{{ route('listPost.search', ['id_branch' => $branch->id]) }}">Xem tất cả</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="branch__center--right">
                        <h2 class="branch__center--title">
                            Thông tin liên hệ
                        </h2>
                        <div class="branch__center--desc">
                            <p style="margin-bottom: 12px">Địa chỉ công ty: {{ $branch->branchDistrict->name }} - {{ $branch->branchProvince->name }}</p>
                            <iframe
                                width="100%"
                                height="200px"
                                style="border:0"
                                loading="lazy"
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps?q=21.2906644,105.9795093&hl=vi&z=15&output=embed">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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