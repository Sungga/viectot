@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/employer/candidateProfile.css'])

    {{-- gọi thư viên pdf to img --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
@endsection

@section('content')
    <section class="candidatePrf">
        <div class="grid-container">
            <p class="back-listCv"><a href="{{ route('employer.listCv') }}"><i class="fa-solid fa-left-long"></i> Quay lại</a></p>
            <div class="candidatePrf__container">
                <img src="{{ asset('storage/uploads/'.$candidatePrf->avatar) }}" alt="" class="candidatePrf__avt">
                <h2 class="candidatePrf__name">{{ $candidatePrf->name }}</h2>
                {{-- <p class="candidatePrf__address"><span class="candidatePrf__district">Việt Nam</span> <i style="font-size: 1.4rem; margin: 0 8px;">--</i> <span class="candidatePrf__province">Vietnamese</span></p> --}}
                <div class="candidatePrf__desc">
                    <div class="candidatePrf__desc--container">
                        <p class="candidatePrf__district">Việt Nam</p>
                        <p class="candidatePrf__province">Vietnamese</p>
                        @if ($candidatePrf->sex == '')
                            <p>Giới tính: Không rõ</p>
                        @else
                            <p>Giới tính: {{ $candidatePrf->sex }}</p>
                        @endif
                        <p class="candidatePrf__age">Tuổi: Không rõ</p>
                        @if ($candidatePrf->sex == '')
                            <p>Số điện thoại: Không rõ</p>
                        @else
                            <p>Số điện thoại: {{ $candidatePrf->phone }}</p>
                        @endif
                        <p class="candidatePrf__category">Chuyên ngành quan tâm: Không có</p>
                    </div>
                </div>
                @if($apply != null)
                    <div style="padding: 20px; width:100%;border-radius:12px;background:var(--color-white);font-size:1.4rem;margin:20px 0;border:1px solid #ccc;text-align:center;">
                        {{ $apply->profileSummary }}
                    </div>
                @endif
                {{-- <p>{{ $cv->name }}</p> --}}
                @if ($cv->file_name == 'CV của hệ thống')
                    @if ($generatedCvs[$cv->id]->template == 'classic')
                        <style>
                            html {
                                font-size: 62.5%; /* 1rem = 10px */
                                scroll-behavior: smooth;
                            }

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

                            .cv-avt {
                                width: 20%;
                                aspect-ratio: 1 / 1; /* Luôn là hình vuông */
                                object-fit: cover;
                                border-radius: 50%;
                            }

                            .cv-name {
                                width: 100%;
                                text-align: center;
                                font-size: 2.8rem;
                                margin: 0.5rem 0;
                            }

                            .cv-about {
                                width: 100%;
                                text-align: center;
                                font-size: 1.2rem;
                                margin: 0.5rem 0;
                            }

                            .cv-section {
                                width: 100%
                            }

                            .cv-section-title {
                                font-size: 2rem;
                                margin: 1rem 0 0.5rem;
                                color: #333;
                            }

                            .cv-section-content {
                                font-size: 1.2rem;
                                margin: 0.5rem 0;
                                line-height: 1.5;
                                color: #555;
                            }

                            .cv-save {
                                padding: 12px 20px;
                                color: #fff;
                                background-color: #007bff;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }
                        </style>
                        <div class="cv-container">
                            @if($generatedCvs[$cv->id]->avatar_path)
                                <img src="{{ asset($generatedCvs[$cv->id]->avatar_path) }}" alt="Avatar" class="cv-avt">
                            @else
                                <img src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="cv-avt">
                            @endif
                            <h2 class="cv-name">{{ $generatedCvs[$cv->id]->name }}</h2>
                            <p class="cv-about"><strong>Ngày sinh:</strong> {{ $generatedCvs[$cv->id]->dob ?? '...' }}</p>
                            <p class="cv-about"><strong>Giới tính:</strong> {{ $generatedCvs[$cv->id]->gender ?? '...' }}</p>
                            <p class="cv-about"><strong>SĐT:</strong> {{ $generatedCvs[$cv->id]->phone ?? '...' }}</p>
                            <p class="cv-about"><strong>Email:</strong> {{ $generatedCvs[$cv->id]->email ?? '...' }}</p>
                            <p class="cv-about"><strong>Địa chỉ:</strong> {{ $generatedCvs[$cv->id]->address ?? '...' }}</p>
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
                                margin-bottom: 20px;
                            }

                            .cv-top-left {
                                width: 20%;
                            }

                            .cv-avt {
                                width: 100%;
                                aspect-ratio: 1 / 1; /* Luôn là hình vuông */
                                object-fit: cover;
                                border-radius: 50%;
                            }

                            .cv-top-right {
                                width: 70%;
                            }

                            .cv-name {
                                width: 100%;
                                text-align: left;
                                font-size: 2.8rem;
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
                                font-size: 2rem;
                                margin: 1rem 0 0.5rem;
                                color: #333;
                            }

                            .cv-section-content {
                                font-size: 1.2rem;
                                margin: 0.5rem 0;
                                line-height: 1.5;
                                color: #555;
                            }

                            .cv-save {
                                padding: 12px 20px;
                                color: #fff;
                                background-color: #007bff;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }
                        </style>
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
                                        <p>Ngày sinh: {{ $generatedCvs[$cv->id]->dob }}</p>
                                        <p>Giới tính: {{ $generatedCvs[$cv->id]->gender }}</p>
                                        <p>Email: {{ $generatedCvs[$cv->id]->email }}</p>
                                        <p>Số điện thoại: {{ $generatedCvs[$cv->id]->phone }}</p>
                                        <p>Địa chỉ: {{ $generatedCvs[$cv->id]->address }}</p>
                                    </div>
                                    <p style="text-align:center; color:#555;">
                                    </p>
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
                @else
                    <iframe src="{{ asset('storage/cv/'.$cv->file_name) }}" width="100%" height="1000px" class="candidatePrf__cv"></iframe>
                @endif
            </div>
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
    @vite(['resources/js/employer/candidateProfile.js'])
    {{-- js hien thi tinh thanh va quan huyen cua ung vien --}}
    <script>
        async function getDataAPI() {
            const url = "https://provinces.open-api.vn/api/?depth=2";

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
        
        async function showProvinceAndDistrict() {
            const data = await getData();
            // console.log(provinces);
            const candidatePrf = @json($candidatePrf);
            // console.log(candidatePrf);
            const provinceCode = candidatePrf.province ? candidatePrf.province : 99999;
            const districtCode = candidatePrf.district ? candidatePrf.district : 99999;

            let provinceHtml = document.querySelector('.candidatePrf__province');
            let districtHtml = document.querySelector('.candidatePrf__district');

            // Tìm tỉnh theo code
            const province = data.find(p => p.code === provinceCode);
            if (!province) provinceHtml.innerHTML = "Vietnamese";
            else provinceHtml.innerHTML = province.name;

            // Tìm quận/huyện trong tỉnh đó theo code
            const district = data.districts.find(d => d.code === districtCode);
            if (!district) districtHtml.innerHTML = "Việt Nam";
            else districtHtml.innerHTML = district.name;
        }
        showProvinceAndDistrict();
    </script>
    {{-- js chuyen doi ngay sinh thanh tuoi --}}
    <script>
        function calculateAge(birthdateString) {
            const today = new Date();
            const birthdate = new Date(birthdateString);
            let age = today.getFullYear() - birthdate.getFullYear();

            const m = today.getMonth() - birthdate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                age--; // chưa đến sinh nhật năm nay
            }

            return age;
        }

        const candidatePrf = @json($candidatePrf);
        let candidateAgeHtml = document.querySelector('.candidatePrf__age');
        if(candidatePrf.birthdate == '') {
            candidateAgeHtml.innerHTML = "Không rõ";
        }
        else {
            const candidateAge = calculateAge(candidatePrf.birthdate);
            candidateAgeHtml.innerHTML = "Tuổi: " + candidateAge;
        }
    </script>
    {{-- js hien thi chuyen nganh quan tam --}}
    <script>
        const categories = @json($categories);
        // goi bien nay o tren roi
        // const candidatePrf = @json($candidatePrf);

        const matchedCategory = categories.find(p => p.id === candidatePrf.id_category);
        const category = matchedCategory ? matchedCategory.name : "Không có";
        let categoryHtml = document.querySelector('.candidatePrf__category');
        categoryHtml.innerHTML = "Chuyên ngành quan tâm: " + category;
    </script>
@endsection