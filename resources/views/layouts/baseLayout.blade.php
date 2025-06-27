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
    {{-- @if (isset($alerts))
        @dd($alerts)
    @endif --}}
    <div class="container">
        <header>
            <div class="header__left">
                {{-- <div class="openMenuMB">
                    <i class="fa-solid fa-bars"></i>
                </div> --}}
                <div class="header__logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('storage/uploads/VIECTOT.png') }}" alt="Logo">
                    </a>
                </div>
                <ul class="header__menu header__menu--left">
                    <li><a class="header__item--left header__item--focus" href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a class="header__item--left" href="{{ route('listPost') }}">Tất cả việc làm</a></li>
                    @if(session()->has('user'))
                        @if (session('user.role') == '1')
                            <li><a class="header__item--left" href="{{ route('employer.listCv') }}">Tuyển dụng</a></li>
                        @endif
                        @if (session('user.role') == '2')
                            <li><a class="header__item--left" href="{{ route('suggestJobs.form') }}">Gợi ý công việc</a></li>
                        @endif
                    @endif
                </ul>
            </div>
            <div class="header__right">
                <ul class="header__menu header__menu--right">
                    @if (session()->has('user'))
                        @if (session('user.role') == '1')
                            <li>
                                <i class="fa-solid fa-bell header__menu--iconAlert {{ isset($alerts) && empty($alerts) ? 'have-new-alert' : '' }}"></i>
                                @if (isset($alerts))
                                    <span class="header__menuAlert--bottom"><a href="{{ route('alert.list') }}">Thông báo</a></span>
                                @endif
                                <ul class="header__menu--alert">
                                    <p class="header__menuAlert--top">Thông báo</p>
                                    @if (isset($alerts) && !empty($alerts))    
                                        {{-- <li class="havenTSeen"><a href="#">
                                            <div class="header__menuAlert--left">
                                                <img src="{{ asset('storage/images/pass.jpg') }}" alt="">
                                            </div>
                                            <div class="header__menuAlert--right">
                                                <p class="header__menuAlert--title">Công ty Samsung abc liên hệ ứng tuyển ngay</p>
                                                <div class="header__menuAlert--content">Xin chúc mừng bạn đã vượt qua vòng xét duyệt hồ sơ của chúng tôi, hãy liên hệ với chúng tôi để được phỏng vấn</div>    
                                            </div>
                                        </a></li> --}}
                                        @foreach ($alerts as $alert)
                                            <li class="{{ $alert->status == 'unread' ? 'havenTRead' : ''}}"><a href="{{ route('alert.show', ['id' => $alert->id]) }}">
                                                <div class="header__menuAlert--left">
                                                    <img src="{{ match($alert->type) {
                                                        'pass' => asset('storage/images/pass.jpg'),
                                                        'reject' => asset('storage/images/reject.jpg'),
                                                        default => asset('storage/images/controller.png'),
                                                    } }}" alt="">
                                                </div>
                                                <div class="header__menuAlert--right">
                                                    <p class="header__menuAlert--title">{{ $alert->title }}</p>
                                                    <div class="header__menuAlert--content">{!! $alert->content !!}</div>    
                                                </div>
                                            </a></li>
                                        @endforeach
                                        <p class="header__menuAlert--bottom"><a href="{{ route('alert.list') }}">&#8810; Xem tất cả &#8811;</a></p>
                                    @else
                                        <p style="width: 100%; text-align: center; margin-bottom: 12px; font-size: 1.2rem;">Bạn không có thông báo nào</p>
                                    @endif
                                </ul>
                            </li>
                            <li>
                                <p class="header__item--right header__item--account"><img src="{{ asset('storage/uploads/user.svg') }}" alt="">{{ $employer['name'] }} #{{ $employer['id_user'] }}</p>
                                <ul class="header__menu--user">
                                    {{-- <li><a href="#">Tài khoản</a></li>
                                    <li><a href="#">Hồ sơ</a></li> --}}
                                    <li><a href="{{ route('logout') }}">Đăng xuất</a></li>
                                </ul>
                            </li>
                            <li><a class="header__item--right header__item--recruit" href="{{ route('employer.listBranch') }}">Đăng tuyển & tìm hồ sơ</a></li>
                        @elseif (session('user.role') == '2') 
                            <li>
                                <i class="fa-solid fa-bell header__menu--iconAlert {{ isset($alerts) && empty($alerts) ? 'have-new-alert' : '' }}"></i>
                                @if (isset($alerts))
                                    <span class="header__menuAlert--bottom"><a href="{{ route('alert.list') }}">Thông báo</a></span>
                                @endif
                                <ul class="header__menu--alert">
                                    <p class="header__menuAlert--top">Thông báo</p>
                                    @if (isset($alerts) && !empty($alerts))
                                        @foreach ($alerts as $alert)
                                            <li class="{{ $alert->status == 'unread' ? 'havenTRead' : ''}}"><a href="{{ route('alert.show', ['id' => $alert->id]) }}">
                                                <div class="header__menuAlert--left">
                                                    <img src="{{ match($alert->type) {
                                                        'pass' => asset('storage/images/pass.jpg'),
                                                        'reject' => asset('storage/images/reject.jpg'),
                                                        default => asset('storage/images/controller.png'),
                                                    } }}" alt="">
                                                </div>
                                                <div class="header__menuAlert--right">
                                                    <p class="header__menuAlert--title">{{ $alert->title }}</p>
                                                    <div class="header__menuAlert--content">{!! $alert->content !!}</div>    
                                                </div>
                                            </a></li>
                                        @endforeach
                                        <p class="header__menuAlert--bottom"><a href="{{ route('alert.list') }}">&#8810; Xem tất cả &#8811;</a></p>
                                    @else
                                        <p style="width: 100%; text-align: center; margin-bottom: 12px; font-size: 1.2rem;">Bạn không có thông báo nào</p>
                                    @endif
                                </ul>
                            </li>
                            <li>
                                <p class="header__item--right header__item--account"><img src="{{ asset('storage/uploads/user.svg') }}" alt="">{{ $candidate['name'] }} #{{ $candidate['id_user'] }}</p>
                                <ul class="header__menu--user">
                                    <li><a href="{{ route('addBasicInfo.form') }}">Tài khoản</a></li>
                                    <li><a href="{{ route('listMessage') }}">Tin nhắn</a></li>
                                    <li><a href="{{ route('apply.list.pending') }}">Danh sách ứng tuyển</a></li>
                                    <li><a href="{{ route('logout') }}">Đăng xuất</a></li>
                                </ul>
                            </li>
                            <li><a class="header__item--right header__item--recruit" href="{{ route('listCv') }}">Quản lý cv</a></li>
                        @elseif(session('user.role') == '0')
                            <a style="font-size:1.4rem;font-weight:600;" href="{{ route('admin') }}">Đến trang quản lý</a>    
                        @endif
                    @else
                        <li><a class="header__item--right header__item--login" href="{{ route('login') }}">Đăng nhập</a></li>
                        <li><a class="header__item--right header__item--register" href="{{ route('register') }}">Đăng ký</a></li>
                    @endif
                </ul>
            </div>
        </header>

        {{-- <div id="menu">
            <div class="menuMB__logo">
                <img src="{{ asset('storage/uploads/VIECTOT.png') }}" alt="Logo">
                <i class="fa-solid fa-circle-xmark menuMB__exit"></i>
            </div>
        </div> --}}
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
    {{-- <script>
        function moveBoxesForMobile(){
            if (window.innerWidth <= 767){
                document.querySelector("#menu").appendChild(document.querySelector(".header__menu--left"));
                document.querySelector("#menu").appendChild(document.querySelector(".header__menu--right"));
            } else {
                document.querySelector(".header__left").appendChild(document.querySelector(".header__menu--left"));
                document.querySelector(".header__right").appendChild(document.querySelector(".header__menu--right"));
            }
        }

        window.addEventListener('load', moveBoxesForMobile);
        window.addEventListener('resize', moveBoxesForMobile);
    </script> --}}
</body>
</html>