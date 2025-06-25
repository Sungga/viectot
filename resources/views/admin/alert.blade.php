
@extends('layouts.adminLayout')
@section('head')
    <title>Việc tốt</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- load css --}}
    @vite(['resources/css/admin/alert.css'])
@endsection
@section('content')
    <section class="listAlert">
        <div class="grid-container">
            @if ($alert->reporter_name == 'Báo cáo bài viết')
                <div class="listAlert__container">
                    <div class="listAlert__top">
                        BÁO CÁO BÀI VIẾT
                    </div>
                    <div class="listAlert__name">Người báo cáo: {{ $reporter->name }} - #{{ $alert->reporter_id }}</div>
                    <div class="listAlert__content">Nội dung: {{ $alert->content }}</div>
                    <div class="listAlert__img"><img src="{{ asset('storage/report/'.$alert->image_path) }}" alt=""></div>
                    <div class="listAlert__path"><a href="{{ route('post.show', ['id_post' => $alert->post_id]) }}">Xem bài viết</a></div>
                    <div class="listAlert__action">
                        <a class="lock" href="{{ route('admin.lockPost', ['id' => $alert->id]) }}">Khóa bài viết</a>
                        <a class="reject" href="{{ route('admin.alert.reject', ['id' => $alert->id]) }}">Không duyệt báo cáo</a>
                    </div>
                </div>
            @elseif($alert->reporter_name == 'Khiếu nại bài viết')
                <div class="listAlert__container">
                    <div class="listAlert__top">
                        KHIẾU NẠI BÀI VIẾT
                    </div>
                    <div class="listAlert__name">Người khiếu nại: {{ $reporter->name }} - #{{ $alert->reporter_id }}</div>
                    <div class="listAlert__content">Nội dung: {{ $alert->content }}</div>
                    <div class="listAlert__img"><img src="{{ asset('storage/report/'.$alert->image_path) }}" alt=""></div>
                    <div class="listAlert__path"><a href="{{ route('post.show', ['id_post' => $alert->post_id]) }}">Xem bài viết</a></div>
                    <div class="listAlert__action">
                        <a class="unlock" href="{{ route('admin.unlockPost', ['id' => $alert->id]) }}">Mở khóa bài viết</a>
                        <a class="reject" href="{{ route('admin.alert.reject', ['id' => $alert->id]) }}">Không duyệt khiếu nại</a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@section('appendage')
    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>
@endsection
@section('js')
@endsection
