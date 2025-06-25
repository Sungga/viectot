
@extends('layouts.adminLayout')
@section('head')
    <title>Việc tốt</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- load css --}}
    @vite(['resources/css/admin/dashboard.css'])
@endsection
@section('content')
        <div class="dashboard">
            <div class="dashboard__container" style="display: block;">
                <h3 class="dashboard__title">Thị trường việc làm hôm nay của Viêc Tốt<span></span></h3>
                <div class="grid-container">
                    <div class="dashboard__content">
                        <div class="dashboard__right">
                            <div class="dashboard__right--top">
                                <div class="dashboard__rigthTop--item">
                                    <p class="dashboard__rightTop--quantity">{{ $jobTotalToDay }}</p>
                                    <p class="dashboard__rightTop--name">Việc làm mới hôm nay</p>
                                </div>
                                <div class="dashboard__rigthTop--item">
                                    <p class="dashboard__rightTop--quantity">{{ $jobTotal }}</p>
                                    <p class="dashboard__rightTop--name">Việc làm đang tuyển</p>
                                </div>
                                <div class="dashboard__rigthTop--item">
                                    <p class="dashboard__rightTop--quantity">{{ $branchTotal }}</p>
                                    <p class="dashboard__rightTop--name">Công ty đang tuyển</p>
                                </div>
                            </div>
                            <div class="dashboard__right--bottom">
                                <div class="dashboard__rightBottom--item">
                                    <h4 class="dashboard__rightBottom--title">Số lượng việc làm qua các ngày</h4>
                                    <div class="dashborad__rightBottom--boxChart">
                                        <canvas id="myChartJobOpportunityGrowthDashboard"></canvas>
                                    </div>
                                </div>
                                <div class="dashboard__rightBottom--item">
                                    <h4 class="dashboard__rightBottom--title">Top công viêc có nhu cầu cao nhất</h4>
                                    <div class="dashborad__rightBottom--boxChart">
                                        <canvas id="myChartDemandJobDashboard"></canvas>
                                        {{-- <div id="htmlLegendDemandJobDashboard">
                                            <div class="item">
                                                <div class="color" style="background-color: red;"></div>
                                                <div class="text">Kinh doanh/Bán hàng</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('appendage')
    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dataJobTotal = @json($results);
        // console.log(dataJobTotal);
        const keysJobTotal = dataJobTotal.map(item => item.date);
        const valuesJobTotal = dataJobTotal.map(item => item.total);
        // console.log(keysJobTotal);
        // console.log(valuesJobTotal);
        const listDayJobChart = keysJobTotal;
        const dataJobChart = {
            labels: listDayJobChart,
            datasets: [
                {
                    label: 'Lượt truy cập',
                    backgroundColor: '#FFD700',
                    // borderColor: 'rgba(0, 0, 0, 1)',
                    borderColor: '#046bc9',
                    data: valuesJobTotal
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
        const dataTopCategories = @json($topCategories);
        console.log(dataTopCategories);
        const keysTopCategories = dataTopCategories.map(item => item.name);
        const valuesTopCategories = dataTopCategories.map(item => item.posts_count);
        console.log(keysTopCategories)
        console.log(valuesTopCategories)

        const jobCategories = keysTopCategories;

        // Số lượng tin tuyển dụng mỗi ngành (có thể thay đổi dựa vào dữ liệu thực tế)
        const jobDemandData = valuesTopCategories;

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
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(199, 199, 199, 0.6)',
                        'rgba(83, 102, 255, 0.6)',
                        'rgba(60, 179, 113, 0.6)',
                        'rgba(255, 99, 71, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)',
                        'rgba(83, 102, 255, 1)',
                        'rgba(60, 179, 113, 1)',
                        'rgba(255, 99, 71, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            display: true, // ✅ hiện tên chuyên ngành dưới cột
                            maxRotation: 45, // xoay để không bị tràn
                            minRotation: 30  // hoặc dùng 0 nếu tên ngắn
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
