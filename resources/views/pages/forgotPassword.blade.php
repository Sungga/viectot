<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Việc tốt</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- font google -->
    <link rel="stylesheet" href="{{ asset('fonts/Inter/Inter-VariableFont_opsz,wght.ttf') }}">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Load CSS -->
    @vite(['resources/css/auth.css'])
</head>
<body>
    <div class="container">
        <div class="login">
            <img class="login__background" src="{{ asset('storage/uploads/blue2.jpg') }}" alt="">
            <a href="./" class="exit"><i class="fa-solid fa-house"></i></a>
            <div class="login__container">
                <h3 class="login__title"><i class="fa-solid fa-hotel"></i> Quên mật khẩu</h3>
                <form action="{{ route('forgotPassword') }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="login__input">
                        <input type="email" id="email" placeholder="" name="email" required>
                        <label for="email">Email khôi phục</label>
                    </div>
                    <div class="login__input">
                        <input type="text" name="code" id="code" placeholder="" class="send-code__input" required>
                        <label for="code">Mã xác nhận</label>
                        <div class="send-code">Gửi mã</div>
                        @error('code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="login__input">
                        <input type="password" id="password" placeholder="" name="password" required>
                        <label for="password">Mật khẩu mới</label>
                        <button type="button" onclick="togglePassword()" class="togglePass">🙈</button>
                    </div>
                    <p class="login__recoverPass"><a href="{{ route('login.form') }}">Đăng nhập</a></p>
                    <button class="login__submit">Đổi mật khẩu</button>
                </form>
                <p style="margin: 16px 0; text-align: center; font-size: 1.4rem;">hoặc</p>
                <a href="#" class="login__another"><i class="fa-brands fa-google" style="color: #de3d31;"></i> Đăng nhập bằng google</a>
                <a href="#" class="login__another"><i class="fa-brands fa-facebook" style="color: #106aff;"></i> Đăng nhập bằng facebook</a>
                <p class="login__register">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký tài khoản mới</a></p>
            </div>
        </div>
    </div>

    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>
    
    @vite(['resources/js/auth.js'])z
    {{-- an hien mat khau --}}
    <script>
        let toggleBtn = document.querySelector(".togglePass");
        function togglePassword() {
            let pass = document.getElementById("password");

            // pass.type = pass.type === "password" ? "text" : "password";

            if (pass.type === "password") {
                pass.type = "text";
                toggleBtn.innerHTML = "🐵"; // Icon mắt có gạch
            } else {
                pass.type = "password";
                toggleBtn.innerHTML = "🙈"; // Icon mắt bình thường
            }
        }
    </script>

    {{-- gui ma xac nhan --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let countdown = 60; // Thời gian đếm ngược (giây)
        let timer; // Biến lưu bộ đếm
    
        $(document).ready(function () {
            // Gửi mã xác nhận
            $(".send-code").click(function (e) {
                e.preventDefault();
    
                let email = $("#email").val();
                if (!email) {
                    alert("Vui lòng nhập email trước khi gửi mã!");
                    return;
                }
    
                // Kiểm tra nếu đang trong thời gian đếm ngược
                if (countdown < 60) {
                    alert(`Vui lòng chờ ${countdown} giây trước khi gửi lại.`);
                    return;
                }
    
                // Vô hiệu hóa nút gửi mã và bắt đầu đếm ngược
                $(".send-code").prop("disabled", true).text(`Gửi lại sau ${countdown}s`);
                timer = setInterval(updateCountdown, 1000);
    
                // Gửi yêu cầu AJAX
                $.ajax({
                    url: "/send-code",
                    type: "POST",
                    data: {
                        email: email,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                        } else {
                            alert(response.message);
                            // Nếu gửi thất bại, reset countdown
                            clearInterval(timer);
                            $(".send-code").prop("disabled", false).text("Gửi mã");
                            countdown = 60;
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = "Gửi mã thất bại! Vui lòng thử lại.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        alert(errorMsg);
                        clearInterval(timer);
                        $(".send-code").prop("disabled", false).text("Gửi mã");
                        countdown = 60;
                    }
                });
            });
    
            function updateCountdown() {
                countdown--;
                $(".send-code").text(`Gửi lại sau ${countdown}s`);
    
                if (countdown <= 0) {
                    clearInterval(timer);
                    $(".send-code").prop("disabled", false).text("Gửi mã");
                    countdown = 60;
                }
            }
    
            // Không cần kiểm tra mã ở frontend nữa
            // Backend sẽ tự kiểm tra khi submit form
        });
    </script>
</body>
</html>