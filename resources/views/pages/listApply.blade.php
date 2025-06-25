@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/pages/listPost.css'])

    {{-- js bieu do --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- gọi thư viên pdf to img --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
@endsection

@section('content')
    <div class="listJob">
        <div class="grid-container">
            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 20px; width: 100%; flex-wrap: wrap;">
                <a style="padding: 8px 16px; background: var(--color-base); color: var(--color-white); font-size: 1.4rem; border-radius: 12px; margin: 0 8px;" href="{{ route('apply.list.pending') }}">Công việc đang xử lý</a>
                <a style="padding: 8px 16px; background: var(--color-base); color: var(--color-white); font-size: 1.4rem; border-radius: 12px; margin: 0 8px;" href="{{ route('apply.list.pass') }}">Công việc đã trúng tuyển</a>
                <a style="padding: 8px 16px; background: var(--color-base); color: var(--color-white); font-size: 1.4rem; border-radius: 12px; margin: 0 8px;" href="{{ route('apply.list.reject') }}">Công việc đã bị từ chối</a>
            </div>
            <div class="listJob__top">
                <h2 class="listJob__title" style="width: 100%; text-align: center; margin-bottom: 20px;">
                    @if ($pageName == 'listApplyPending')
                        Việc làm bạn đang xử lý
                    @elseif($pageName == 'listApplyPass')
                        Việc làm bạn đã được chấp nhận
                    @else
                        Việc làm bạn đã bị từ chối
                    @endif
                </h2>
                {{-- <a href="{{ route('listPost') }}" class="listJob__seeAll">Xem tất tất cả <span>&#187;</span></a> --}}
            </div>
            <div class="listJob__list">
                @foreach ($posts as $post)
                    <div class="listJob__item">
                        <div class="listJob__item--body">
                            <a href="{{ route('post.show', ['id_post' => $post->post->id]) }}">
                                <div class="listJob__item--left">
                                    <img src="{{ asset('storage/uploads/'.$post->post->branch->company->logo) }}" alt="" class="listJob__item--logo">
                                </div>
                            </a>
                            <div class="listJob__item--right">
                                <a href="{{ route('post.show', ['id_post' => $post->post->id]) }}" class="listJob__item--title">{{ $post->post->title }}</a>
                                <a href="#" class="listJob__item--company">{{ $post->post->branch->company->name }}</a>
                            </div>
                        </div>
                        <div class="listJob__item--footer">
                            <div class="listJob__item--footerItem">
                                <span class="listJob__item--info">{{ $post->post->salary_show }}</span>
                                <span class="listJob__item--info">{{ $post->post->work_mode_show }}</span>
                            </div>
                        </div>
                        <div class="listJob__item--desc" id="bigBlock">
                            <h3 class="listJob__item--descTitle">{{ $post->post->title }}</h3>
                            <p class="listJob__item--descDeadline">Hạn đến: {{ \Carbon\Carbon::parse($post->post->deadline)->format('d/m/Y') }} - Số lượng: {{ $post->post->quantity }}</p>
                            <div class="listJob__item--descItem">
                                <span>{{ $post->post->salary_show }}</span>
                                <span>{{ $post->post->work_mode_show }}</span>
                                @if ($post->post->gender_show != '')
                                    <span>Giới tính: {{ $post->post->gender_show }}</span>
                                @endif
                                <span>{{ $post->post->experience_show }}</span>
                                <span>{{ $post->post->degree_show }}</span>
                            </div>
                            <div class="listJob__item--descDesc">{!! $post->post->description !!}</div>
                        </div>
                        @if($pageName == 'listApplyPass')
                            <div class="listCv__item--bottom">
                                <a class="listCv__item--contact" href="{{ route('chat', ['type' => 'candidate', 'id_branch' => $post->post->id_branch, 'id_candidate' => session('user.id'), 'id_apply' => $post->id]) }}"  style="margin-top: 12px;font-size:1.4rem;padding:8px;border:1px solid #ccc;border-radius:12px;text-align:center;cursor:pointer;background:var(--color-base);color:var(--color-white);display:block;">Liên hệ</a>
                            </div>  
                        @endif
                    </div>
                @endforeach
            </div>
            {{-- {{ dd($totalPosts) }} --}}
            <div class="listJob__bottom">
                <a @if($page > 1) href="{{ route('listPost', ['page' => $page - 1]) }}"  @endif>
                    <i class="fa-solid fa-chevron-left @if($page <= 1) listJob__bottom--cantSelect @endif"></i>
                </a>
                <p><span>{{ $page }}</span> / {{ $totalPages }} trang</p>
                <a @if($page < $totalPages) href="{{ route('listPost', ['page' => $page + 1]) }}"  @endif>
                    <i class="fa-solid fa-chevron-right @if($page >= $totalPages) listJob__bottom--cantSelect @endif"></i>
                </a>
            </div>
        </div>
    </div>
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
    @vite(['resources/js/pages/listPost.js'])
@endsection