<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- icon --}}
    <link rel="icon" href="{{ asset('storage/uploads/VIECTOT.png') }}" type="image/x-icon" />

    <!-- font google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    @yield('head')
</head>
<body>
    <div class="container">
        <header>
            <div class="header__item">
                <a href="{{ route('home') }}">Trang chủ</a>
            </div>
            <div class="header__item">
                <a href="{{ route('admin.listAlert') }}">Xử lý báo cáo</a>
            </div>
            <div class="header__item">
                <a href="{{ route('admin.listCandidate') }}">Danh sách hệ thống</a>
            </div>
            <div class="header__item">
                <a href="{{ route('admin.dashboard') }}">Thống kê hệ thống</a>
            </div>
        </header>

        <a style="font-size: 1.4rem; color: var(--color-white); background: var(--color-base); border-radius: 12px; padding: 8px 16px; position: fixed; bottom: 10px; right: 10px;" href="{{ route('logout') }}">Đăng xuất</a>

        @yield('content')

        <section class="footer">
            <p class="copyright">
                Copyright &copy;
                <script>document.write(new Date().getFullYear());</script> 
                Bản quyền thuộc Sungga 
            </p>
        </section>

        @yield('appendage')
        @yield('js')
    </div>
</body>
</html>