@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/home.css'])

    {{-- js bieu do --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <section class="slider">
        <div class="slide__background">
            <img src="{{ asset('storage/uploads/slide2.jpg') }}" alt="">
        </div>
        <div class="grid-container">
            <div class="slider__top" style="width: 100%; height: 24px;"></div>
            <form action="{{ route('listPost.search') }}" method="get">
                <div class="search">
                    <input type="text" name="search" id="" class="search__text" placeholder="Vị trí tuyển dụng, tên công ty">
                    <div class="search__center">
                        <div class="search__location">
                            <div class="search__location--btn">
                                <p style="user-select: none;"><i class="fa-solid fa-location-dot"></i>Địa điểm</p>
                                <p><i class="fa-solid fa-angle-down"></i></p>
                            </div>
                            <div class="search__location--list">
                                <div class="search__location--province">
                                    <div class="search__location--provinceSearch">
                                        <input type="text" name="" id="" placeholder="Tìm tỉnh, thành phố,...">
                                    </div>
                                    <ul>
                                        <!-- <li>
                                            <input type="checkbox" name="" id="">
                                            <p>Hồ Chí Minh</p>
                                            <i class="fa-solid fa-angle-right search__location--toDistrict"></i>
                                        </li> -->
                                    </ul>
                                </div>
                                <div class="search__location--district">
                                    <h3>QUẬN/HUYỆN</h3>
                                    <ul>
                                        <!-- <li><input type="checkbox" name="" id="">
                                            <p>Quận 1</p>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="search__right"><button class="search__btn"><i class="fa-solid fa-magnifying-glass"></i>Tìm kiếm</button></div>
                </div>
            </form>
            <div class="slider__bottom">
                <div class="slider__category">
                    <?php 
                        $countCategoriesPage = 1;
                        $countCategoriesOnPage = 1;
                    ?>
                    @foreach ($categories as $category)
                        @if ($countCategoriesOnPage == 1)
                            <ul class="slider__category--list">
                        @endif
                            <li>
                                <a href="#" class="slider__category--item">
                                    <p class="slider__category--name">{{ $category['name'] }}</p>
                                    <i class="fa-solid fa-angle-right slider__category--iconToRight"></i>
                                </a>
                                <div class="slider__category--topSearch">
                                    <h3>Gợi ý tìm kiếm</h3>
                                    @foreach ($category->keyWord as $keyWord)
                                        <a href="{{ route('listPost.search', ['search' => $keyWord->keyword]) }}"><i class="fa-solid fa-fire" style="color: #ff4242;"></i>{{ $keyWord->keyword }}</a>
                                    @endforeach
                                </div>
                            </li>
                        @if ($countCategoriesOnPage == 6 || $loop->last)
                            </ul>
                            <?php $countCategoriesPage++; ?>
                        @endif
                        <?php
                            $countCategoriesOnPage++;
                            if ($countCategoriesOnPage > 6) $countCategoriesOnPage = 1;  // Reset countCategoriesOnPage when it reach 6
                        ?>
                    @endforeach
                    <div class="slider__pagination">
                        <div class="slider__pagination--left">
                            <p>1/4</p>
                        </div>
                        <div class="slider__pagination--right">
                            <i class="fa-solid fa-angle-left prevButton disabled" style="margin-right: 4px;"></i>
                            <i class="fa-solid fa-angle-right nextButton"></i>
                        </div>
                    </div>
                </div>
                <div class="slider__bottom--right">
                    <div class="slider__jobAd">
                        <a href="#">
                            <img src="{{ asset('storage/uploads/slider_ad1.png') }}" alt="">
                        </a>
                        <a href="#">
                            <img src="{{ asset('storage/uploads/slider_ad2.jpg') }}" alt="">
                        </a>
                        <a href="#">
                            <img src="{{ asset('storage/uploads/slider_ad3.jpg') }}" alt="">
                        </a>
                    </div>
                    <div class="slider__workMarket">
                        <div class="slider__workMarket--left">
                            <h3 class="slider__workMarket--title">
                                <i class="fa-solid fa-briefcase"></i>
                                Thị trường việc làm <span class="slider__workMarket--time"></span>
                            </h3>
                            <p class="slider__workMarket--newJob">Việc làm mới hôm nay <span>Loading..</span></p>
                        </div>
                        <div class="slider__workMarket--right">
                            <div class="slider__workMarket--totalJob">
                                <p>Tổng việc làm <span>Loading..</span></p>
                            </div>
                            <!-- <canvas class="slider__workMarket--jobChart"></canvas> -->
                            <div class="slider__workMarket--seeMore">
                                <p>Xem chi tiết</p>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- click div class slider__workMarket--seeMore then show block__dashboard -->
    <div class="dashboard">
        <div class="dashboard__container" style="display: block; background: url('{{ asset('storage/uploads/bgr_white.jpg') }}');">
            <div class="dashboard__close">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <h3 class="dashboard__title">Thị trường việc làm hôm nay <span></span></h3>
            <div class="dashboard__content" style="min-height: 388px">
                <div class="loader-cont" style="width:100%;height:100%;display:flex;">
                    <div class="loader" style="margin: auto"></div>
                </div>
                <style>
                    .loader {
                    width: fit-content;
                    font-weight: bold;
                    font-family: monospace;
                    font-size: 30px;
                    background: radial-gradient(circle closest-side,#000 94%,#0000) right/calc(200% - 1em) 100%;
                    animation: l24 1s infinite alternate linear;
                    }
                    .loader::before {
                    content: "Loading...";
                    line-height: 1em;
                    color: #0000;
                    background: inherit;
                    background-image: radial-gradient(circle closest-side,#fff 94%,#000);
                    -webkit-background-clip:text;
                            background-clip:text;
                    }

                    @keyframes l24{
                    100%{background-position: left}
                    }
                </style>
            </div>
        </div>
    </div>

    <div class="listJob" style="padding-bottom: 20px;">
        <div class="grid-container">
            <div class="listJob__top">
                <h2 class="listJob__title">Việc làm mới nhất</h2>
                <a href="{{ route('listPost') }}" class="listJob__seeAll">Xem tất tất cả <span>&#187;</span></a>
            </div>
            <div class="listJob__list">
                @if ($posts->isEmpty())
                    <h2 style="width: 100%; text-align: center; margin-bottom: 20px;">Không có bài đăng tuyển dụng</h2>
                @else
                @endif
                @foreach ($posts as $post)
                    <div class="listJob__item">
                        <div class="listJob__item--body">
                            <a href="{{ route('post.show', ['id_post' => $post->id]) }}">
                                <div class="listJob__item--left">
                                    <img src="{{ asset('storage/uploads/'.$post->branch->company->logo) }}" alt="" class="listJob__item--logo">
                                </div>
                            </a>
                            <div class="listJob__item--right">
                                <a href="{{ route('post.show', ['id_post' => $post->id]) }}" class="listJob__item--title">{{ $post->title }}</a>
                                <a href="#" class="listJob__item--company">{{ $post->branch->company->name }}</a>
                            </div>
                        </div>
                        <div class="listJob__item--footer">
                            <div class="listJob__item--footerItem">
                                <span class="listJob__item--info">{{ $post->salary_show }}</span>
                                <span class="listJob__item--info">{{ $post->work_mode_show }}</span>
                            </div>
                        </div>
                        <div class="listJob__item--desc" id="bigBlock">
                            <h3 class="listJob__item--descTitle">{{ $post->title }}</h3>
                            <p class="listJob__item--descDeadline">Hạn đến: {{ \Carbon\Carbon::parse($post->deadline)->format('d/m/Y') }} - Số lượng: {{ $post->quantity }}</p>
                            <div class="listJob__item--descItem">
                                <span>{{ $post->salary_show }}</span>
                                <span>{{ $post->work_mode_show }}</span>
                                @if ($post->gender_show != '')
                                    <span>Giới tính: {{ $post->gender_show }}</span>
                                @endif
                                <span>{{ $post->experience_show }}</span>
                                <span>{{ $post->degree_show }}</span>
                            </div>
                            <div class="listJob__item--descDesc">{!! $post->description !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="topCompany">
        <div class="grid-container">
            <div class="topCompany__container">
                <div class="topCompany__top">
                    <h3 class="topCompany__title">Thương hiệu bạn có thể biết</h3>
                </div>
                <div class="topCompany__list">
                    @foreach ($branches as $branch)
                        <a href="{{ route('branch.show', ['id_branch' => $branch->id]) }}" class="topCompany__item">
                            <div class="topCompany__item--top">
                                <div class="topCompany__item--left">
                                    <img src="{{ asset('storage/uploads/'.$branch->company->logo) }}" alt="">
                                </div>
                                <div class="topCompany__item--right">
                                    <div class="topCompany__item--name">{{ $branch->name }}</div>
                                    <div class="topCompany__item--field">{{ $branch->company->name }}</div>
                                </div>
                            </div>
                            <div class="topCompany__item--bottom">
                                <i class="fa-solid fa-briefcase"></i>
                                {{ count($branch->post) }} việc làm
                            </div>
                        </a>
                    @endforeach
                </div>
                {{-- <a href="#" class="topCompany__seeAll">Xem tất cả</a> --}}
            </div>
        </div>
    </div>

    {{-- code thong bao su kien --}}
    @if (session('error'))
    <script>
        window._alertErrorMsg = @json(session('error'));
    </script>
    @endif
    @if(session('info'))
        <script>
            window._alertInfoMsg = @json(session('info'));
        </script>
    @endif
    @if(session('success'))
        <script>
            window._alertSuccessMsg = @json(session('success'));
        </script>
    @endif

    @if (session('debug'))
        <script>console.log("Có vào được WITH INFO");</script>
    @endif
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
    @vite(['resources/js/pages/home.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- @vite(['resources/css/home.css', 'resources/js/pages/home.js']) --}}
    {{-- <script>
        const listDayJobChart = ['07/02', '13/02', '19/02', '25/02', '03/02', '09/02'];
        const dataJobChart = {
            labels: listDayJobChart,
            datasets: [
                {
                    label: 'Lượt truy cập',
                    backgroundColor: '#FFD700',
                    // borderColor: 'rgba(0, 0, 0, 1)',
                    borderColor: '#046bc9',
                    data: [9865, 12000, 74712, 58911, 89011, 57911]
                }
            ]
        }
        const config = {
            type: 'line',
            data: dataJobChart,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { color: '#000' }, // Màu chữ của ngày tháng
                        grid: { color: 'rgba(0, 0, 0, 0.08)' } // Màu lưới ngang
                    },
                    y: {
                        ticks: { color: '#000' }, // Màu chữ trục Y
                        grid: { color: 'rgba(0, 0, 0, 0.08)' } // Màu lưới dọc
                    }
                },
                plugins: {
                    legend: {
                        display: false // Ẩn legend
                    }
                }
            }
        };
        const canvasJobChart = document.getElementById('myChartJobOpportunityGrowthDashboard');
        const chart = new Chart(canvasJobChart, config);
    </script>
    <script>
        const jobCategories = [
            "Kinh doanh / Bán hàng",
            "Hành chính / Văn phòng",
            "Dịch vụ khách hàng",
            "Marketing / Truyền thông / Quảng cáo",
            "Tư vấn"
        ];

        // Số lượng tin tuyển dụng mỗi ngành (có thể thay đổi dựa vào dữ liệu thực tế)
        const jobDemandData = [120, 90, 110, 80, 100];

        const ctx = document.getElementById('myChartDemandJobDashboard').getContext('2d');
        const jobDemandChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: jobCategories,
                datasets: [{
                    label: 'Số lượng tin tuyển dụng',
                    data: jobDemandData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            display: false // Ẩn labels trên trục X
                        },
                        grid: {
                            drawTicks: false, // Ẩn cả dấu tick trên trục X
                            drawBorder: false // Ẩn viền trục X nếu cần
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false // Ẩn legend
                    }
                }
            }
        });

        

        // chú thích
        const jobCategoriesWithColor = [
            { name: "Kinh doanh / Bán hàng", color: "#ff4d4d" },
            { name: "Hành chính / Văn phòng", color: "#4d79ff" },
            { name: "Dịch vụ khách hàng", color: "#ffd24d" },
            { name: "Marketing / Truyền thông / Quảng cáo", color: "#4dd2ff" },
            { name: "Tư vấn", color: "#b366ff" }
        ];

        const legendContainer = document.getElementById("htmlLegendDemandJobDashboard");

        // Xóa nội dung cũ (nếu có)
        legendContainer.innerHTML = "";

        jobCategoriesWithColor.forEach(category => {
            const item = document.createElement("div");
            item.classList.add("item");

            const colorBox = document.createElement("div");
            colorBox.classList.add("color");
            colorBox.style.backgroundColor = category.color;

            const text = document.createElement("div");
            text.classList.add("text");
            text.textContent = category.name;

            item.appendChild(colorBox);
            item.appendChild(text);
            legendContainer.appendChild(item);
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const today = new Date();
            const formattedDate = today.toLocaleDateString("vi-VN", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric"
            });

            const workMarketTitle = document.querySelector(".slider__workMarket h3 span");
            workMarketTitle.innerHTML += ` ${formattedDate}`;

            const dashboardTitle = document.querySelector(".dashboard__title span");
            dashboardTitle.innerHTML += ` ${formattedDate}`;
        });
    </script> --}}

    {{-- loc --}}
        <script>
        // Lấy các biến GET từ URL hiện tại
        const params = new URLSearchParams(window.location.search);
        const newParams = new URLSearchParams();
        const query = {};
        for (const [key, value] of params.entries()) {
            if (query[key]) {
                // Nếu biến GET trùng, chuyển thành mảng
                query[key] = [].concat(query[key], value);
            } else {
                query[key] = value;
            }
        };
        newParams.set('search', query['search']);

        const filterTypeBtn = document.querySelectorAll('.listJob__filter--filterItem');

        const contentMap = {
            // Khu vực
            'Hà Nội': { key: 'provinceSearch', value: '1' },
            'Thành phố Hồ Chí Minh': { key: 'provinceSearch', value: '79' },
            'Miền Bắc': { key: 'region', value: 'bac' },
            'Miền Trung': { key: 'region', value: 'trung' },
            'Miền Nam': { key: 'region', value: 'nam' },

            // Lương
            'Dưới 10 triệu': { key: 'salary', value: 'Dưới 10 triệu' },
            'Từ 10-15 triệu': { key: 'salary', value: 'Từ 10-15 triệu' },
            'Từ 15-20 triệu': { key: 'salary', value: 'Từ 15-20 triệu' },
            'Từ 20-25 triệu': { key: 'salary', value: 'Từ 20-25 triệu' },
            'Từ 25-30 triệu': { key: 'salary', value: 'Từ 25-30 triệu' },
            'Từ 30-50 triệu': { key: 'salary', value: 'Từ 30-50 triệu' },
            'Trên 50 triệu': { key: 'salary', value: 'Trên 50 triệu' },
            'Thỏa thuận': { key: 'salary', value: 'Thỏa thuận' },

            // Kinh nghiệm
            'Chưa có kinh ngiệm': { key: 'experience', value: 'Chưa có kinh nghiệm' },
            '1 năm trở xuống': { key: 'experience', value: '1 năm trở xuống' },
            '1 năm': { key: 'experience', value: '1 năm' },
            '2 năm': { key: 'experience', value: '2 năm' },
            '3 năm': { key: 'experience', value: '3 năm' },
            'Từ 4-5 năm': { key: 'experience', value: 'Từ 4-5 năm' },
            'Trên 5 năm': { key: 'experience', value: 'Trên 5 năm' },

            // Ngành nghề
            'Kinh doanh/Bán hàng': { key: 'category', value: 'Kinh doanh/Bán hàng' },
            'Biên/Phiên dịch': { key: 'category', value: 'Biên/Phiên dịch' },
            'Báo chí/Truyền hình': { key: 'category', value: 'Báo chí/Truyền hình' },
            'Bưu chính/Viễn thông': { key: 'category', value: 'Bưu chính/Viễn thông' },
            'Bưu chính - Viễn thông': { key: 'category', value: 'Bưu chính - Viễn thông' },
            'Bảo hiểm': { key: 'category', value: 'Bảo hiểm' },
            'Bất Động sản': { key: 'category', value: 'Bất Động sản' }
        };

        filterTypeBtn.forEach((item) => {
            item.addEventListener('click', () => {
                const content = item.textContent.trim();

                if (content === 'Ngẫu nhiên' || content === 'Tất cả') return;

                const mapped = contentMap[content];
                if (mapped) {
                    newParams.set(mapped.key, mapped.value);
                }

                // Nếu cần chuyển hướng:
                const newUrl = '/listPost/search' + '?' + newParams.toString();
                window.location.href = newUrl;
            });
        });

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const today = new Date();
            const formattedDate = today.toLocaleDateString("vi-VN", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric"
            });

            const workMarketTitle = document.querySelector(".slider__workMarket h3 span");
            workMarketTitle.innerHTML += ` ${formattedDate}`;

            const dashboardTitle = document.querySelector(".dashboard__title span");
            dashboardTitle.innerHTML += ` ${formattedDate}`;
        });
    </script>
@endsection