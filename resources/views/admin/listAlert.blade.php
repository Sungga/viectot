
@extends('layouts.adminLayout')
@section('head')
    <title>Việc tốt</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- load css --}}
    @vite(['resources/css/admin/listAlert.css'])
@endsection
@section('content')
    <section class="listAlert">
        <div class="grid-container">
            <div class="listAlert__container">
                <div class="listAlert__top">
                    <a href="{{ route('admin.listAlert') }}">Danh sách chưa xử lý</a>
                    <a href="{{ route('admin.listAlertOk') }}">Danh sách đã xử lý</a>
                </div>
                <div class="listAlert__title">
                    DANH SÁCH CHƯA XỬ LÝ
                </div>
                <div class="listAlert__list">
                    @foreach ($alerts as $alert)
                        <a href="{{ route('admin.alert', ['id' => $alert->id]) }}">
                            <div class="listAlert__item">
                                <p class="listAlert__item--title">Ứng viên: {{ $reporter[$alert->id]->name }}</p>
                                <p class="listAlert__item--content"><strong>Nội dung:</strong> {{ $alert->content }}</p>
                            </div>
                        </a>
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
