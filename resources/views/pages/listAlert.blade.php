@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>
    @vite(['resources/css/chat/listChat.css'])
@endsection

@section('content')
    <section class="chat">
        <div class="grid-container">
            <div class="chat__container">
                <h2 class="chat__title">DANH SÁCH TIN NHẮN CỦA BẠN</h2>
                <div class="chat_list">
                    @foreach ($alerts as $alert)
                        <a href="{{ route('alert.show', ['id' => $alert->id]) }}">
                            <div class="chat__item {{ $alert->status == 'unread' ? 'unread' : '' }}">
                                <div class="chat__item--left">
                                    <img src="{{ match($alert->type) {
                                        'pass' => asset('storage/images/pass.jpg'),
                                        'reject' => asset('storage/images/reject.jpg'),
                                        default => asset('storage/images/controller.png'),
                                    } }}" alt="">
                                </div>
                                <div class="chat__item--right">
                                    <div class="chat__item--title">
                                        {{ $alert->title }}
                                    </div>
                                    <div class="chat__item--content">
                                        {!! $alert->content !!}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@section('appendage')
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
      intent="WELCOME"
      chat-title="Chưa có data câu hỏi mô!"
      agent-id="020b6d32-f258-4915-93aa-7290ea1ae3c6"
      language-code="vi"
    ></df-messenger>
@endsection

@section('js')
    @vite(['resources/js/chat/listChat.js'])
@endsection
