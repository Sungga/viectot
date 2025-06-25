@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/employer/listCv.css'])

    {{-- js bieu do --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .cv-container {
            width: 100%;
            max-width: 800px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            padding: 20px;
        }

        .cv-top {
            width: 100%;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            height: auto !important;
        }

        .cv-top-left {
            width: 20%;
            height: auto !important;
        }

        .cv-avt {
            width: 100%;
            aspect-ratio: 1 / 1; /* Luôn là hình vuông */
            object-fit: cover !important;
            border-radius: 50%;
        }

        .cv-top-right {
            width: 70%;
            height: auto !important;
            
        }

        .cv-name {
            width: 100%;
            text-align: left;
            font-size: 1.4rem;
        }

        .cv-contact-info {
            height: auto !important;
        }

        .cv-contact-info p {
            width: 100%;
            text-align: left;
            font-size: 1.4rem;
            margin: 0.5rem 0;
        }

        .cv-section {
            width: 100%
        }

        .cv-section-title {
            font-size: 1.2rem;
            margin: 1rem 0 0.05rem;
            color: #333;
            text-align: left;
        }

        .cv-section-content {
            font-size: 1rem;
            margin: 0.5rem 0;
            line-height: 1.5;
            color: #555;
        }
    </style>
@endsection

<?php
    // B1: Tạo map từ id_user => candidate
    $candidateMap = [];
    foreach ($candidates as $candidateItem) {
        $candidateMap[$candidateItem['id_user']] = $candidateItem;
    }

    // B2: Gộp vào từng phần tử của $cvs
    foreach ($cvs as &$cv) {
        $id_user = $cv['id_user'];
        if (isset($candidateMap[$id_user])) {
            // $cv['candidate'] = $candidateMap[$id_user]; // Gộp toàn bộ
            // Hoặc chỉ lấy 1 vài trường:
            $cv['candidate_name'] = $candidateMap[$id_user]['name'];
            $cv['avatar'] = $candidateMap[$id_user]['avatar'];
            $cv['province'] = $candidateMap[$id_user]['province'];
            $cv['id_category'] = $candidateMap[$id_user]['id_category'];
        }
    }
    unset($cv); // tránh lỗi tham chiếu
?>

@section('content')
    <section class="slider">
        <div class="slide__background">
            <img src="{{ asset('storage/uploads/slide2.jpg') }}" alt="">
        </div>
        <div class="grid-container">
            <div class="slider__top" style="width: 100%; height: 24px;"></div>
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

    <section class="listCv">
        <div class="grid-container">
            <div class="listCv__top">
                <h2 class="listCv__title">Danh sách ứng viên</h2>
            </div>
            <div class="listCv__filter">
                <div class="listCv__filter--type">
                    <span class="listCv__filter--typeTitle"><i class="fa-solid fa-filter"></i> Lọc theo:</span>
                    <span class="listCv__filter--typeSelect">Địa điểm <i class="fa-solid fa-chevron-down"></i></span>
                    <ul class="listCv__filter--typeListType">
                        <li class="listCv__filter--typeItemType listCv__filter--cities selected">Địa điểm</li>
                        <li class="listCv__filter--typeItemType listCv__filter--salary">Mức lương</li>
                        <li class="listCv__filter--typeItemType listCv__filter--experience">Kinh nghiệm</li>
                        <li class="listCv__filter--typeItemType listCv__filter--categories">Ngành nghề</li>
                    </ul>
                </div>
                <div class="listCv__filter--right">
                    <i class="fa-solid fa-arrow-left listCv__filter--moveLeft"></i>
                    <div class="listCv__filter--filterCities listCv__filter--filterContainer">
                        <div class="listCv__filter--filterList">
                            <div class="listCv__filter--filterItem selected">Ngẫu nhiên</div>
                            <div class="listCv__filter--filterItem">Hà Nội</div>
                            <div class="listCv__filter--filterItem">Thành phố Hồ Chí Minh</div>
                            <div class="listCv__filter--filterItem">Miền Bắc</div>
                            <div class="listCv__filter--filterItem">Miền Trung</div>
                            <div class="listCv__filter--filterItem">Miền Nam</div>
                        </div>
                    </div>
                    <div class="listCv__filter--filterSalary listCv__filter--filterContainer">
                        <div class="listCv__filter--filterList">
                            <div class="listCv__filter--filterItem selected">Tất cả</div>
                            <div class="listCv__filter--filterItem">Dưới 10 triệu</div>
                            <div class="listCv__filter--filterItem">Từ 10-15 triệu</div>
                            <div class="listCv__filter--filterItem">Từ 15-20 triệu</div>
                            <div class="listCv__filter--filterItem">Từ 20-25 triệu</div>
                            <div class="listCv__filter--filterItem">Từ 25-30 triệu</div>
                            <div class="listCv__filter--filterItem">Từ 30-50 triệu</div>
                            <div class="listCv__filter--filterItem">Trên 50 triệu</div>
                            <div class="listCv__filter--filterItem">Thỏa thuận</div>
                        </div>
                    </div>
                    <div class="listCv__filter--filterExperience listCv__filter--filterContainer">
                        <div class="listCv__filter--filterList">
                            <div class="listCv__filter--filterItem selected">Tất cả</div>
                            <div class="listCv__filter--filterItem">Chưa có kinh ngiệm</div>
                            <div class="listCv__filter--filterItem">1 năm trở xuống</div>
                            <div class="listCv__filter--filterItem">1 năm</div>
                            <div class="listCv__filter--filterItem">2 năm</div>
                            <div class="listCv__filter--filterItem">3 năm</div>
                            <div class="listCv__filter--filterItem">Từ 4-5 năm</div>
                            <div class="listCv__filter--filterItem">Trên 5 năm</div>
                        </div>
                    </div>
                    <div class="listCv__filter--filterCategories listCv__filter--filterContainer">
                        <div class="listCv__filter--filterList">
                            <div class="listCv__filter--filterItem selected">Tất cả</div>
                            <div class="listCv__filter--filterItem">Kinh doanh/Bán hàng</div>
                            <div class="listCv__filter--filterItem">Biên/Phiên dịch</div>
                            <div class="listCv__filter--filterItem">Báo chí/Truyền hình</div>
                            <div class="listCv__filter--filterItem">Bưu chính/Viễn thông</div>
                            <div class="listCv__filter--filterItem">Bưu chính - Viễn thông</div>
                            <div class="listCv__filter--filterItem">Bảo hiểm</div>
                            <div class="listCv__filter--filterItem">Bất Động sản</div>
                        </div>
                    </div>
                    <i class="fa-solid fa-arrow-right listCv__filter--moveRight"></i>
                </div>
            </div>
            <div class="listCv__list">
                @foreach ($cvs as $cv)
                    <div class="listCv__item">
                        <a href="{{ route('employer.candidateProfile', ['id' => $cv->id_user]) }}">
                            <div class="listCv__contCv">
                                {{-- <div style="background-color: var(--color-white);"> --}}
                                        @if ($cv->file_name == 'CV của hệ thống')
                                            
                                            @if ($generatedCvs[$cv->id]->template == 'classic')
                                                <div class="cv-container">
                                                    <div style="width: 20%; height: auto;">
                                                        @if($generatedCvs[$cv->id]->avatar_path)
                                                            <img src="{{ asset($generatedCvs[$cv->id]->avatar_path) }}" alt="Avatar" class="cv-avt">
                                                        @else
                                                            <img src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="cv-avt">
                                                        @endif
                                                    </div>
                                                    <h2 class="cv-name" style="text-align:center;">{{ $generatedCvs[$cv->id]->name }}</h2>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>Ngày sinh:</strong> {{ $generatedCvs[$cv->id]->dob ?? '...' }}</p>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>Giới tính:</strong> {{ $generatedCvs[$cv->id]->gender ?? '...' }}</p>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>SĐT:</strong> {{ $generatedCvs[$cv->id]->phone ?? '...' }}</p>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>Email:</strong> {{ $generatedCvs[$cv->id]->email ?? '...' }}</p>
                                                    <p class="cv-about" style="font-size: 1rem; text-align:center; width:100%;"><strong>Địa chỉ:</strong> {{ $generatedCvs[$cv->id]->address ?? '...' }}</p>
                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Mục tiêu nghề nghiệp</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->career_goal ?? '')) !!}</p>
                                                    </div>
                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Học vấn</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->education ?? '')) !!}</p>
                                                    </div>
                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Kinh nghiệm</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->experience ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Dự án</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->projects ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Kỹ năng</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->skills ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Chứng chỉ</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->certificates ?? '')) !!}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="cv-container">
                                                    <div class="cv-top">
                                                        <div class="cv-top-left">
                                                            @if($generatedCvs[$cv->id]->avatar_path)
                                                                <img src="{{ asset($generatedCvs[$cv->id]->avatar_path) }}" alt="Avatar" class="cv-avt">
                                                            @else
                                                                <img src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="cv-avt">
                                                            @endif
                                                        </div>
                                                        <div class="cv-top-right">
                                                            <h1 class="cv-name">{{ $generatedCvs[$cv->id]->name }}</h1>
                                                            <div class="cv-contact-info">
                                                                <p style="font-size: 1rem;">Ngày sinh: {{ $generatedCvs[$cv->id]->dob }}</p>
                                                                <p style="font-size: 1rem;">Giới tính: {{ $generatedCvs[$cv->id]->gender }}</p>
                                                                <p style="font-size: 1rem;">Email: {{ $generatedCvs[$cv->id]->email }}</p>
                                                                <p style="font-size: 1rem;">Số điện thoại: {{ $generatedCvs[$cv->id]->phone }}</p>
                                                                <p style="font-size: 1rem;">Địa chỉ: {{ $generatedCvs[$cv->id]->address }}</p>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Mục tiêu nghề nghiệp</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->career_goal ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Học vấn</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->education ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Kinh nghiệm</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->experience ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Dự án</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->projects ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Kỹ năng</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->skills ?? '')) !!}</p>
                                                    </div>

                                                    <div class="cv-section">
                                                        <h2 class="cv-section-title">Chứng chỉ</h2>
                                                        <hr>
                                                        <p class="cv-section-content">{!! nl2br(e($generatedCvs[$cv->id]->certificates ?? '')) !!}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                <div class="listCv__cv"><canvas id="pdf-canvas-{{ $cv->id }}" style="width: 100%; height: auto; border-radius: 8px;"></canvas></div>
                            </div>
                            <div class="listCv__candidate">
                                <img src="{{ asset('storage/uploads/'.$cv->avatar) }}" alt="" class="listCv__avt">
                                <div class="listCv__name">{{ $cv->candidate_name }}</div>
                            </div>
                            <div class="listCv__province">Thành phố Hồ Chí Minh</div>
                            <br>
                            <div class="listCv__category">Công nghệ thông tin</div>
                        </a>    
                    </div>
                @endforeach
            </div>
    </section>
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
    @vite(['resources/js/employer/listCv.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
    {{-- js show img cv (pdf to img) --}}
    {{-- gọi thư viên pdf to img --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cvs = @json($cvs);

            pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

            cvs.forEach(cv => {
                const canvas = document.getElementById(`pdf-canvas-${cv.id}`);
                const ctx = canvas.getContext("2d");

                const url = `/storage/cv/${cv.file_name}`;

                pdfjsLib.getDocument(url).promise.then(pdf => {
                    pdf.getPage(1).then(page => {
                        const viewport = page.getViewport({ scale: 1.0 });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: ctx,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                });
            });
        });
    </script>
    {{-- js hien thi tinh thanh cua cac ung vien --}}
    <script>
        async function getDataAPI() {
            const url = "https://provinces.open-api.vn/api/";

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Lỗi: ${response.status}`);
                }
                const data = await response.json();
                return Array.isArray(data) ? data : []; // Đảm bảo luôn là mảng
            } catch (error) {
                console.error("Lỗi khi gọi API:", error);
                return []; // Trả về mảng rỗng nếu có lỗi
            }
        }

        // Gọi và lấy dữ liệu ngay
        async function getData() {
            const data = await getDataAPI(); // Lấy dữ liệu
            return data;
        }
        
        async function showProvinces() {
            const provinces = await getData();
            // console.log(provinces);
            const cvs = @json($cvs);
            // console.log(cvs)

            
            // Mảng province và cvs đã có từ trước
            const cvsWithProvinceName = cvs.map(cv => {
                // Tìm province tương ứng theo mã code
                const matchedProvince = provinces.find(p => p.code === cv.province);
                
                // Trả về CV với tên tỉnh/thành phố thêm vào
                return {
                    // ...cv,
                    // lưu lại giá trị 1 là ứng viên có địa chỉ cụ thể, 2 là ứng viên có địa chỉ Việt Nam
                    province_name: matchedProvince ? matchedProvince.name : "Việt Nam"
                };
            });

            // // In ra thử xem
            // console.log(cvsWithProvinceName);

            let listCvProvince = document.querySelectorAll('.listCv__province');
            listCvProvince.forEach((item, index) => {
                item.innerHTML = cvsWithProvinceName[index].province_name
            });
        }
        showProvinces();

        
    </script>
    {{-- js hien thi chuyen nganh quan tam cua ung vien --}}
    <script>
        const categories = @json($categories);
        console.log(categories);
        const cvs = @json($cvs);
        console.log(cvs);
        let listCvCategory = document.querySelectorAll('.listCv__category');

        const cvsWithCategoryName = cvs.map(cv => {
            // Tìm category theo id tương ứng
            const matchedCategory = categories.find(p => p.id === cv.id_category);
            
            // Trả về CV với tên của category tương ứng
            return {
                name: matchedCategory ? matchedCategory.name : "Không có"
            };
        });

        listCvCategory.forEach((item, index) => {
            item.innerHTML = cvsWithCategoryName[index].name;
        });
    </script>
@endsection