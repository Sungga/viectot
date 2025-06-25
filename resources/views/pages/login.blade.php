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
                <h3 class="login__title"><i class="fa-solid fa-hotel"></i> ĐĂNG NHẬP</h3>
                <form action="{{ route('login') }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="login__input">
                        <input type="text" id="username" placeholder="" name="username" required>
                        <label for="username">Tên đăng nhập</label>
                    </div>
                    <div class="login__input">
                        <input type="password" id="password" placeholder="" name="password" required>
                        <label for="password">Mật khẩu</label>
                        <button type="button" onclick="togglePassword()" class="togglePass">🙈</button>
                    </div>
                    @error('code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <p class="login__recoverPass"><a href="{{ route('forgotPassword.form') }}">Quên mật khẩu</a></p>
                    <button class="login__submit">Đăng nhập</button>
                </form>
                <p style="margin: 16px 0; text-align: center; font-size: 1.4rem;">hoặc</p>
                <a href="{{ route('google.login') }}" class="login__another"><i class="fa-brands fa-google" style="color: #de3d31;"></i> Đăng nhập bằng google</a>
                {{-- <a href="{{ route('toFacebook') }}" class="login__another"><i class="fa-brands fa-facebook" style="color: #106aff;"></i> Đăng nhập bằng facebook</a> --}}
                <p class="login__register">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký tài khoản mới</a></p>
            </div>
        </div>
    </div>
    
    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>

    @vite(['resources/js/auth.js'])
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
</body>
</html>