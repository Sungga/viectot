
@extends('layouts.adminLayout')
@section('head')
    <title>Việc tốt</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- load css --}}
    @vite(['resources/css/admin/listAlert.css'])
    <style>
        .listAlert__item {
            display: flex;
            align-content: center;
            justify-content: space-between;
            font-size: 1.4rem;
        }

        .action_lock {
            padding: 0 8px;
            line-height: 43.2px;
            display: block;
            background-color: var(--color-green);
            color: var(--color-white);
        }

        .action_lock:hover {
        }

        .action_unlock {
            padding: 0 8px;
            line-height: 43.2px;
            display: block;
            background-color: var(--color-red);
            color: var(--color-white);
        }

        .action_unlock:hover {
        }
        
    </style>
@endsection
@section('content')
    <section class="listAlert">
        <div class="grid-container">
            <div class="listAlert__container">
                <div class="listAlert__top" style="display: flex; align-item:center; justify-content: space-around">
                    <a href="{{ route('admin.listCandidate') }}">Danh sách ứng viên</a>
                    <a href="{{ route('admin.listEmployer') }}">Danh sách nhà tuyển dụng</a>
                    <a href="{{ route('admin.listPost') }}">Danh sách bài viết</a>
                </div>
                <div class="listAlert__title">
                    DANH SÁCH BÀI VIẾT
                </div>
                <div class="listAlert__list">
                    @foreach ($posts as $post)
                        <div class="listAlert__item">
                            <div>
                                <a href="{{ route('post.show', ['id_post' => $post->id]) }}">
                                    <p class="listAlert__item--title">{{ $post->title }} #{{ $post->id }}</p>
                                </a>
                                <a href="{{ route('branch.show', ['id_branch' => $post->branch->id]) }}">
                                    <p class="listAlert__item--title" style="color:var(--color-base);">Chi nhánh: {{ $post->branch->name }} #{{ $post->branch->id }}</p>
                                </a>
                                <a>
                                    <p class="listAlert__item--title" style="color:#5c5c5c; font-size: 1.2rem; font-style: italic;">Công ty: {{ $post->branch->company->name }} #{{ $post->branch->company->id }}</p>
                                </a>
                            </div>
                            @if ($post->status == 'lock')
                                <p><a class="action_unlock" href="{{ route('admin.unlockPost', ['id' => $post->id]) }}">Mở khóa</a></p>
                            @else
                                <p><a class="action_lock" href="{{ route('admin.lockPost', ['id' => $post->id]) }}">Khóa</a></p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
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
