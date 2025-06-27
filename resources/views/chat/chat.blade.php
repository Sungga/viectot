
@extends('layouts.baseLayout')
@section('head')
    <title>Việc tốt</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- load css --}}
    @vite(['resources/css/chat/chat.css'])
@endsection
@section('content')
    <section class="chat">
        <div class="grid-container">
            <div class="chat__container">
                <div class="chat__left">
                    <a href="#" class="chat__left--item">
                        <div class="chat__left--left">
                            <img src="{{ asset('storage/images/avt.jpg') }}" alt="">
                        </div>
                        <div class="chat__left--right">
                            <div class="chat__left--nameMain">{{ $type == 'branch' ? $candidate->name : $branch->name }}</div>
                            {{-- <div class="chat__left--content">Bên mình có tuyển công việc này không ạ?</div> --}}
                        </div>
                    </a>
                </div>
                <div class="chat__right">
                    <div class="chat__right--top">
                        <h1 class="chat__right--branch"><a href="{{ route('branch.show', ['id_branch' => $branch->id]) }}">{{ $branch->name }}</a></h1>
                        <p class="chat__right--company">Thuộc: {{ $company->name }}</p>
                        <p class="chat__right--candidate" style="font-size:1.4rem; color:var(--color-base);"><a href="{{ route('post.show', ['id_post' => $apply->post->id]) }}">{{ $apply->post->title }}</a></p>
                        <p class="chat__right--candidate"><a href="{{ route('employer.candidateProfile', ['id' => $candidate->id, 'id_apply' =>$apply->id]) }}">Ứng viên: {{ $candidate->name }}</a></p>
                    </div>
                    <div id="chat-box" style="">
                        <!-- Tin nhắn sẽ hiển thị ở đây -->
                        @foreach ($messages as $message)
                            <p class="{{ $message->sender == $type ? 'sender' : 'receiver' }}"><span>{{ $message->message }}</span></p>
                        @endforeach
                    </div>
                    <div class="chat__right--bottom">
                        <textarea id="message" placeholder="Nhập tin nhắn..." row="1"></textarea>
                        <button id="send-btn">Gửi</button>
                    </div>
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
    @php
        $lastMessage = $messages->last();
    @endphp
    <!-- Load JS -->
    @vite(['resources/js/chat/chat.js'])
    <script>
        // Thay 2 biến này bằng ID thật của bạn (ví dụ lấy từ session)
        var id_branch = {{ $branch->id }};
        var id_candidate = {{ $candidate->id }};
        var id_post = {{ $apply->post->id }};
        // var id_apply = {{ $apply->post->id }};
        // console.log(id_apply)
        var type = '{{ $type }}';

        // id mess cuoi
        var idMax = {{ $lastMessage->id }};

        function loadMessages() {
            const chatBox = $('#chat-box');
            const isNearBottom = chatBox[0].scrollHeight - chatBox.scrollTop() - chatBox.outerHeight() < 10;
            $.get('/chat/messages/' + id_branch + '/' + id_candidate + '/' + id_post + '/' + idMax, function(data) {
                console.log(data);
                // chatBox.empty(); // Xóa nội dung cũ nếu có
                data.forEach(function(msg) {
                    // console.log(msg.type);
                    // const messageClass = (msg.sender_id == sender_id) ? 'sender' : 'receiver';
                    const messageClass = (msg.sender == type) ? 'sender' : 'receiver';
                    const messageHtml = $('<p>', { class: messageClass }).append(
                        $('<span>').text(msg.message)
                    );
                    chatBox.append(messageHtml);
                    idMax = msg.id;
                });
                // Cuộn xuống cuối
                // Chỉ scroll xuống nếu đang gần cuối
                if (isNearBottom) {
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                }
            });
        }
        // Hàm gửi tin nhắn
        function sendMessage() {
            var message = $('#message').val();
            if (message.trim() === '') return;
            $.post('/chat/send', {
                id_branch: id_branch,
                id_candidate: id_candidate,
                id_post: id_post,
                type: type,
                message: message,
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#message').val('');
                // loadMessages();
            });
        }

        // Khi click nút Send
        $('#send-btn').click(sendMessage);

        // Khi nhấn Enter trong textarea
        $('#message').on('keydown', function(e) {
            // e.which == 13 → phím Enter
            // !e.shiftKey để Enter bình thường (không kèm Shift) mới gửi
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();  // ngăn không xuống dòng
                sendMessage();
            }
        });
        // Load tin nhắn mỗi 5 giây
        setInterval(loadMessages, 5000);
        loadMessages();
    </script>
@endsection
