
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
                @if ($idEmployer == 'false')
                    <div class="listAlert__title">
                        DANH SÁCH ỨNG VIÊN
                    </div>
                    <div class="listAlert__list">
                        @foreach ($candidates as $candidate)
                            <div class="listAlert__item">
                                <a href="{{ $candidate->account->role == '2' ? route('admin.candidateProfile', ['id' => $candidate->id]) : '#' }}">
                                    <p class="listAlert__item--title">{{ $candidate->account->role == '1' ? 'Nhà tuyển dụng' : 'Ứng viên' }}: {{ $candidate->name }} #{{ $candidate->account->id }}</p>
                                </a>
                                @if ($candidate->account->status == 'lock')
                                    <p><a class="action_unlock" href="{{ route('admin.unlockUser', ['id_user' => $candidate->account->id]) }}">Mở khóa</a></p>
                                @else
                                    <p><a class="action_lock" href="{{ route('admin.lockUser', ['id_user' => $candidate->account->id]) }}">Khóa</a></p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="listAlert__title">
                        DANH SÁCH NHÀ TUYỂN DỤNG
                    </div>
                    <div class="listAlert__list">
                        @foreach ($employers as $employer)
                            <div class="listAlert__item">
                                <a href="#">
                                    <p class="listAlert__item--title">{{ $employer->account->role == '1' ? 'Nhà tuyển dụng' : 'Ứng viên' }}: {{ $employer->name }} #{{ $employer->account->id }}</p>
                                </a>
                                @if ($employer->account->status == 'lock')
                                    <p><a class="action_unlock" href="{{ route('admin.unlockUser', ['id_user' => $employer->account->id]) }}">Mở khóa</a></p>
                                @else
                                    <p><a class="action_lock" href="{{ route('admin.lockUser', ['id_user' => $employer->account->id]) }}">Khóa</a></p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
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
