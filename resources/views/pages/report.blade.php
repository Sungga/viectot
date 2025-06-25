@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/pages/report.css'])
@endsection

@section('content')
    <section class="report">
        <div class="grid-container">
            <form method="POST" action="{{ route('report', ['id_post' => $post->id]) }}" class="report__container" enctype="multipart/form-data">
                @csrf
                <h1>Báo cáo vi phạm</h1>
                <p class="report__item">Báo cáo bài viết: {{ $post->title }}</p>
                <p class="report__item">Thuộc chi nhánh: {{ $post->branch->name }}</p>
                <p class="report__item">Thuộc công ty: {{ $post->branch->company->name }}</p>
                <textarea name="content" id="" style="width: 100%;" rows="10" required></textarea>
                <label for="img">Hình ảnh liên quan</label>
                <input type="file" name="img" id="img" accept="image/*">

                <div class="report__btn">
                    <button>Gửi</button>
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
    @vite(['resources/js/pages/report.js'])
@endsection