@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/pages/listPost.css'])

    {{-- js bieu do --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- gọi thư viên pdf to img --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
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
                            <p class="slider__workMarket--newJob">Việc làm mới hôm nay <span>4.538</span></p>
                        </div>
                        <div class="slider__workMarket--right">
                            <div class="slider__workMarket--totalJob">
                                <p>Tổng việc làm <span>87.328</span></p>
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

    <div class="listJob">
        <div class="grid-container">
            <div class="listJob__top">
                <h2 class="listJob__title">Việc làm mới nhất</h2>
                {{-- <a href="{{ route('listPost') }}" class="listJob__seeAll">Xem tất tất cả <span>&#187;</span></a> --}}
            </div>
            <div class="listJob__filter">
                <div class="listJob__filter--type">
                    <span class="listJob__filter--typeTitle"><i class="fa-solid fa-filter"></i> Lọc theo:</span>
                    <span class="listJob__filter--typeSelect">Địa điểm <i class="fa-solid fa-chevron-down"></i></span>
                    <ul class="listJob__filter--typeListType">
                        <li class="listJob__filter--typeItemType listJob__filter--cities selected">Địa điểm</li>
                        <li class="listJob__filter--typeItemType listJob__filter--salary">Mức lương</li>
                        <li class="listJob__filter--typeItemType listJob__filter--experience">Kinh nghiệm</li>
                        <li class="listJob__filter--typeItemType listJob__filter--categories">Ngành nghề</li>
                    </ul>
                </div>
                <div class="listJob__filter--right">
                    <i class="fa-solid fa-arrow-left listJob__filter--moveLeft"></i>
                    <div class="listJob__filter--filterCities listJob__filter--filterContainer">
                        <div class="listJob__filter--filterList">
                            <div class="listJob__filter--filterItem selected">Ngẫu nhiên</div>
                            <div class="listJob__filter--filterItem">Hà Nội</div>
                            <div class="listJob__filter--filterItem">Thành phố Hồ Chí Minh</div>
                            <div class="listJob__filter--filterItem">Miền Bắc</div>
                            <div class="listJob__filter--filterItem">Miền Trung</div>
                            <div class="listJob__filter--filterItem">Miền Nam</div>
                        </div>
                    </div>
                    <div class="listJob__filter--filterSalary listJob__filter--filterContainer">
                        <div class="listJob__filter--filterList">
                            <div class="listJob__filter--filterItem selected">Tất cả</div>
                            <div class="listJob__filter--filterItem">Dưới 10 triệu</div>
                            <div class="listJob__filter--filterItem">Từ 10-15 triệu</div>
                            <div class="listJob__filter--filterItem">Từ 15-20 triệu</div>
                            <div class="listJob__filter--filterItem">Từ 20-25 triệu</div>
                            <div class="listJob__filter--filterItem">Từ 25-30 triệu</div>
                            <div class="listJob__filter--filterItem">Từ 30-50 triệu</div>
                            <div class="listJob__filter--filterItem">Trên 50 triệu</div>
                            <div class="listJob__filter--filterItem">Thỏa thuận</div>
                        </div>
                    </div>
                    <div class="listJob__filter--filterExperience listJob__filter--filterContainer">
                        <div class="listJob__filter--filterList">
                            <div class="listJob__filter--filterItem selected">Tất cả</div>
                            <div class="listJob__filter--filterItem">Chưa có kinh ngiệm</div>
                            <div class="listJob__filter--filterItem">1 năm trở xuống</div>
                            <div class="listJob__filter--filterItem">1 năm</div>
                            <div class="listJob__filter--filterItem">2 năm</div>
                            <div class="listJob__filter--filterItem">3 năm</div>
                            <div class="listJob__filter--filterItem">Từ 4-5 năm</div>
                            <div class="listJob__filter--filterItem">Trên 5 năm</div>
                        </div>
                    </div>
                    <div class="listJob__filter--filterCategories listJob__filter--filterContainer">
                        <div class="listJob__filter--filterList">
                            <div class="listJob__filter--filterItem selected">Tất cả</div>
                            <div class="listJob__filter--filterItem">Kinh doanh/Bán hàng</div>
                            <div class="listJob__filter--filterItem">Biên/Phiên dịch</div>
                            <div class="listJob__filter--filterItem">Báo chí/Truyền hình</div>
                            <div class="listJob__filter--filterItem">Bưu chính/Viễn thông</div>
                            <div class="listJob__filter--filterItem">Bưu chính - Viễn thông</div>
                            <div class="listJob__filter--filterItem">Bảo hiểm</div>
                            <div class="listJob__filter--filterItem">Bất Động sản</div>
                        </div>
                    </div>
                    <i class="fa-solid fa-arrow-right listJob__filter--moveRight"></i>
                </div>
            </div>
            <div class="listJob__list">
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
                                <a href="{{ route('branch.show', ['id_branch' => $post->branch->id]) }}" class="listJob__item--company">{{ $post->branch->company->name }}</a>
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
            {{-- {{ dd($totalPosts) }} --}}
            <div class="listJob__bottom">
                @if ($pageName == 'listPostSearch')
                    <a @if($page > 1) href="{{ route('listPost.search', ['search' => $search, 'page' => $page - 1]) }}"  @endif>
                        <i class="fa-solid fa-chevron-left @if($page <= 1) listJob__bottom--cantSelect @endif"></i>
                    </a>
                    <p><span>{{ $page }}</span> / {{ $totalPages }} trang</p>
                    <a @if($page < $totalPages) href="{{ route('listPost.search', ['search' => $search, 'page' => $page + 1]) }}"  @endif>
                        <i class="fa-solid fa-chevron-right @if($page >= $totalPages) listJob__bottom--cantSelect @endif"></i>
                    </a>
                @elseif($pageName == 'listPostSearchId')
                    <a @if($page > 1) href="{{ route('listPost.search', ['id_branch' => $id_branch, 'page' => $page - 1]) }}"  @endif>
                        <i class="fa-solid fa-chevron-left @if($page <= 1) listJob__bottom--cantSelect @endif"></i>
                    </a>
                    <p><span>{{ $page }}</span> / {{ $totalPages }} trang</p>
                    <a @if($page < $totalPages) href="{{ route('listPost.search', ['id_branch' => $id_branch, 'page' => $page + 1]) }}"  @endif>
                        <i class="fa-solid fa-chevron-right @if($page >= $totalPages) listJob__bottom--cantSelect @endif"></i>
                    </a>
                @else
                    <a @if($page > 1) href="{{ route('listPost', ['page' => $page - 1]) }}"  @endif>
                        <i class="fa-solid fa-chevron-left @if($page <= 1) listJob__bottom--cantSelect @endif"></i>
                    </a>
                    <p><span>{{ $page }}</span> / {{ $totalPages }} trang</p>
                    <a @if($page < $totalPages) href="{{ route('listPost', ['page' => $page + 1]) }}"  @endif>
                        <i class="fa-solid fa-chevron-right @if($page >= $totalPages) listJob__bottom--cantSelect @endif"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/js/pages/listPost.js'])
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
        if(query['search'] != undefined) {
            newParams.set('search', query['search']);
        }
        else if(query['id_branch'] != undefined) {
            newParams.set('id_branch', query['id_branch']);
        } 
        else {
            newParams.set('search', query['search']);
        }

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