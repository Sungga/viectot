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
            <a href="./" class="exit"><i class="fa-solid fa-house"></i></a>
            <img class="login__background" src="{{ asset('storage/uploads/blue2.jpg') }}" alt="">
            <div class="login__container">
                <h3 class="login__title"><i class="fa-solid fa-registered"></i> ĐĂNG KÝ</h3>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    {{-- {!! csrf_field() !!} --}}
                    <div class="login__input">
                        <input type="text" name="name" id="name" placeholder="" required>
                        <label for="name">Họ và tên</label>
                    </div>
                    <div class="login__input">
                        <input type="text" name="username" id="username" placeholder="" required>
                        <label for="username">Tên đăng nhập</label>
                    </div>
                    <div class="login__input">
                        <input type="email" name="email" id="email" placeholder="" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="login__input">
                        <input type="password" name="password" id="password" placeholder="" required>
                        <label for="password">Mật khẩu</label>
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
                        <select name="role" id="role">
                            <option value="ungvien">Tôi là ứng viên</option>
                            <option value="nhatuyendung">Tôi là nhà tuyển dụng</option>
                        </select>
                    </div>
                    <div class="login__terms">
                        <input type="checkbox" id="terms" required>
                        <span>Tôi đồng ý với các <a href="#">điều khoản</a> và <a href="#">chính sách</a></span>
                    </div>
                    @if ($errors->any())
                        <div class="error-box">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li style="color: red">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <button type="submit" class="login__submit">Đăng ký</button>
                </form>
                <p style="margin: 16px 0; text-align: center; font-size: 1.4rem;">hoặc</p>
                {{-- <a href="#" class="login__another"><i class="fa-brands fa-google" style="color: #de3d31;"></i> Đăng nhập bằng google</a>
                <a href="#" class="login__another"><i class="fa-brands fa-facebook" style="color: #106aff;"></i> Đăng nhập bằng facebook</a> --}}
                <p class="login__register">Đã có tài khoản? <a href="{{ route('login.form') }}">Đăng nhập ngay</a></p>
            </div>
        </div>
    </div>

    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>

    @vite(['resources/js/auth.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script>
        let code = '';
        let countdown = 60; // Thời gian đếm ngược (giây)
        let timer; // Biến lưu bộ đếm

        $(document).ready(function () {
            // gui ma xac nhan
            $(".send-code").click(function (e) {
                e.preventDefault(); // Ngăn chặn hành động mặc định của button (nếu có)

                let email = $("#email").val();
                if (!email) {
                    alert("Vui lòng nhập email trước khi gửi mã!");
                    return;
                }

                // Kiểm tra nếu đang trong thời gian đếm ngược thì không cho gửi tiếp
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
                        console.log(response);
                        code = response.data;
                        alert("Đã gửi mã xác nhận thành công!");

                        // // Vô hiệu hóa nút gửi mã và bắt đầu đếm ngược
                        // $(".send-code").prop("disabled", true).text(`Gửi lại sau ${countdown}s`);
                        // timer = setInterval(updateCountdown, 1000);
                    },
                    error: function () {
                        alert("Gửi mã thất bại! Vui lòng thử lại.");
                    }
                });
            });

            function updateCountdown() {
                countdown--;
                $(".send-code").text(`Gửi lại sau ${countdown}s`);

                if (countdown <= 0) {
                    clearInterval(timer);
                    $(".send-code").prop("disabled", false).text("Gửi mã");
                    countdown = 60; // Reset thời gian đếm ngược
                }
            }

            $("form").submit(function (e) {
                let inputCode = $("#code").val();
                if (inputCode !== code) {
                    alert("Mã xác nhận không đúng! Vui lòng kiểm tra lại.");
                    e.preventDefault();
                } else {
                    alert("Mã xác nhận đúng. Đang tiến hành đăng ký...");
                }
            });
        });

    </script> --}}
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