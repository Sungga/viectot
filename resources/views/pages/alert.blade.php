@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/base/base.css'])
    @vite(['resources/css/base/reset.css'])
    @vite(['resources/css/base/header.css'])
    @vite(['resources/css/base/footer.css'])

    <style>
        .alert {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert__container {
            width: 500px;
            max-width: 100%;
            padding: 16px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }

        .title {
            text-align: center;
            width: 100%;
            font-size: 2rem;
            color: var(--color-base);
            margin-bottom: 20px;
        }

        .content {
            font-size: 1.4rem;
            width: 500px;
            text-align: center;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .btnChat {
            font-size: 1.4rem;
            padding: 8px 16px;
            border: 1px solid var(--color-base);
            color: var(--color-white);
            background-color: var(--color-base);
        }
    </style>
@endsection

@section('content')
    <section class="alert">
        <div class="alert__container">
            <h3 class="title">{{ $alertMain->title }}</h3>
            <div class="content">{!! $alertMain->content !!}</div>
            @if ($alertMain->type == 'pass' && $alertMain->id_branch != '')
                <a class="btnChat" href="/chat/candidate/{{ $alertMain->id_branch }}/{{ session('user.id') }}">Liên hệ</a>
            @elseif ($alertMain->type == 'lock' && session('user.role') == '1')
                <a class="btnChat" href="{{ route('complaint.form', ['id_post' => $alertMain->id_post]) }}">Khiếu nại</a>
            @endif
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
    @vite(['resources/js/base/header.js'])
    @vite(['resources/js/base/base.js'])
@endsection