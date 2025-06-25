<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Việc tốt</title>

  <!-- css thu vien keo tha -->
  <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet"/>
  
  <!-- font google -->
  <link rel="stylesheet" href="assets/font/Inter/Inter-VariableFont_opsz,wght.ttf">
  
  <!-- font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

  @vite(['resources/css/makeCv.css'])
</head>
<body>
    <header>
        <div class="header__left"><a href="{{ route('listCv') }}"><i class="fa-solid fa-arrow-left"></i>Quay lại</a></div>
        <div class="header__center">
            <a href="{{ route('home') }}"><img src="{{ asset('storage/uploads/VIECTOT.png') }}" alt=""></a>
        </div>
        <div class="header__right">
            <ul class="header__menu">
                <li>
                    <p class="header__item--right header__item--account"><img src="{{ asset('storage/uploads/user.svg') }}" alt="">{{ $candidate['name'] }}</p>
                    <ul class="header__menu--user">
                        <li><a href="#">Tài khoản</a></li>
                        <li><a href="#">Hồ sơ</a></li>
                        <li><a href="{{ route('logout') }}">Đăng xuất</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </header>

    <section class="makeCv">
        <div id="blocks">
            <!-- Các block sẽ được thêm vào đây -->
        </div>

        <div id="gjs">
            <h1>Chào mừng đến với trình thiết kế!</h1>
            <p>Bạn có thể kéo thả các khối vào đây.</p>
            <div class="page">Nội dung trang 1</div>
            <div class="page">Nội dung trang 2</div>
        </div>
    </section>

    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>

    <script src="https://unpkg.com/grapesjs"></script>
    @vite(['resources/js/makeCv.js'])
</body>
</html>
