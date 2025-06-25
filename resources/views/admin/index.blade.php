
@extends('layouts.adminLayout')
@section('head')
    <title>Việc tốt</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- load css --}}
    @vite(['resources/css/admin/index.css'])
@endsection
@section('content')
    <h1>Chào mừng đến trang quản lý Việc Tốt</h1>
@endsection
@section('appendage')
    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>
@endsection
@section('js')
@endsection
