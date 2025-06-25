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
                    @foreach ($messages as $message)
                        <a href="{{ route('chat', ['type' => 'candidate', 'id_branch' => $message->id_branch, 'id_candidate' => $message->id_candidate, 'id_apply' => $message->apply->id]) }}">
                            <div class="chat__item {{ $message->status == 'unread' ? 'unread' : '' }}">
                                <div class="chat__item--left">
                                    <img src="{{ asset('storage/images/bgr_wood.jpg') }}" alt="">
                                </div>
                                <div class="chat__item--right">
                                    <div class="chat__item--title">
                                        {{ $message->branch->name }}
                                    </div>
                                    <div class="chat__item--title" style="font-weight:500; font-style:italic; color:var(--color-base);">
                                        Công việc: {{ $message->post_title }}
                                    </div>
                                    <div class="chat__item--content">
                                        @if ($message->sender == 'candidate')
                                            Bạn: 
                                        @endif
                                        {{ $message->message }}
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
