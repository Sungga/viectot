@extends('layouts.baseLayout')

@section('head')
    <title>Việc tốt</title>

    <!-- Load CSS -->
    @vite(['resources/css/pages/addBasicInfo.css'])
    @vite(['resources/css/base/reset.css'])
    @vite(['resources/css/base/base.css'])
    @vite(['resources/css/base/header.css'])
    @vite(['resources/css/base/footer.css'])
@endsection

@section('content')
    <section class="account">
        <form action="{{ route('addBasicInfo') }}" method="POST" enctype="multipart/form-data" id="account-form">
        @csrf
        <div class="account-container">
            <img class="account__background" src="{{ asset('storage/images/bgr_loang.jpg') }}"></img>
            <button>
                <i class="fa-solid fa-floppy-disk account__iconSave"></i>
            </button>

            {{-- avatar --}}
            <label for="avatar-input" class="account__avtLabel">
                @if ($candidate['avatar'] == "")
                    <img id="avatar-preview" src="{{ asset('storage/images/avt.jpg') }}" alt="Avatar" class="account__avt">
                @else
                    <img id="avatar-preview" src="{{ asset('storage/uploads/'.$candidate['avatar']) }}" alt="Avatar" class="account__avt">
                @endif
                <div class="account__avatar--overlay">
                    <i class="fa-solid fa-camera"></i>
                </div>
            </label>
            <input type="file" id="avatar-input" accept="image/*" hidden name="avatar" value="{{ asset('storage/uploads/'.$candidate['avatar']) }}">

            {{-- name --}}
            <input type="text" name="name" value="{{ $candidate['name'] }}" class="account__name">

            <!-- sdt -->
            <label for="phone" class="account__labelItem">Số điện thoại</label>
            <input type="text" name="phone" id="phone" class="account__phone account__input" value="{{ $candidate['phone'] }}">

            <!-- birthdate -->
            <label for="birthdate" class="account__labelItem">Ngày sinh</label>
            <input type="date" name="birthdate" id="birthdate" class="account__birthdate account__input" value="{{ $candidate['birthdate'] }}">

            <!-- địa chỉ -->
            <div class="account__box">
                <div class="account__box--item">
                    <!-- Tỉnh -->
                    <label for="province" class="account__labelItem">Tỉnh</label>
                    <select name="province" id="province" class="account__province account__input"></select>
                </div>
                <div class="account__box--item">
                    <!-- Huyện -->
                    <label for="district" class="account__labelItem">Huyện</label>
                    <select name="district" id="district" class="account__district account__input"></select>
                </div>
            </div>
            {{-- <label for="location" class="account__labelItem">Địa chỉ</label>
            <input type="text" name="location" id="location" class="account__location account__input"> --}}

            <!-- giới tính -->
            <label for="sex" class="account__labelItem">Giới tính</label>
            <select name="sex" id="sex" class="account__sex account__input">
                @if ($candidate['sex'] == 'nam')
                    <option value="nam" selected>Nam</option>
                    <option value="nữ">Nữ</option>
                    @else
                    <option value="nam">Nam</option>
                    <option value="nữ" selected>Nữ</option>
                @endif
            </select>

            <!-- Ngành nghề quan tâm chính -->
            <label for="category" class="account__labelItem">Ngành nghề quan tâm</label>
            <select name="category" id="category" class="account__category account__input">
            </select>

            <button class="account__save">Thay đổi</button>
        </div>
        </form>
    </section>
@endsection

@section('appendage')
    {{-- loading --}}
    <div class="loading__container" style="display: none;">
        <div class="global-loading-spinner" id="globalLoadingSpinner"></div>
    </div>
@endsection

@section('js')
    <!-- Load JS -->
    @vite(['resources/js/pages/addBasicInfo.js'])
    @vite(['resources/js/base/base.js'])
    @vite(['resources/js/base/header.js'])
    {{-- js show location --}}
    <script>
        const accountProvince = document.querySelector('.account__province');
        const accountDistrict = document.querySelector('.account__district');
        const candidate = @json($candidate);
        let cachedData = null;

        document.addEventListener('DOMContentLoaded', async () => {
            const url = "https://provinces.open-api.vn/api/?depth=2";

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Lỗi: ${response.status}`);
                }
                cachedData = await response.json(); // Lưu dữ liệu vào biến toàn cục
                // console.log("Dữ liệu đã được cache:", cachedData);

                // Sau khi cache dữ liệu, tạo select box
                createSelectBox();
                createDistrictSelectBox(candidate.province);
            } catch (error) {
                console.error("Lỗi khi gọi API:", error);
            }
        });

        function createSelectBox() {
            if (!cachedData || !Array.isArray(cachedData)) {
                console.error("Dữ liệu tỉnh/thành phố không hợp lệ.");
                return;
            }

            let provincesSelectBoxHtml = `
                <option value="9999">Chọn tỉnh/thành phố</option>
            `;
            cachedData.forEach((province) => {
                provincesSelectBoxHtml += `
                    <option value="${province.code}" ${candidate.province == province.code ? 'selected' : ''}>
                        ${province.name}
                    </option>
                `;
            });

            accountProvince.innerHTML = provincesSelectBoxHtml;
        }

        function createDistrictSelectBox(idProvince) {
            if (!cachedData || !Array.isArray(cachedData)) {
                console.error("Dữ liệu quận/huyện không hợp lệ.");
                return;
            }

            console.log(cachedData);
            

            const province = cachedData.find((item) => item.code == idProvince);
            if (!province || !province.districts) {
                console.error("Không tìm thấy tỉnh/thành phố hoặc dữ liệu quận/huyện không hợp lệ.");
                accountDistrict.innerHTML = `
                    <option value="9999">Chọn quận/huyện</option>
                `;
                return;
            }

            let districtSelectBoxHtml = `
                <option value="9999">Chọn quận/huyện</option>
            `;
            province.districts.forEach((district) => {
                districtSelectBoxHtml += `
                    <option value="${district.code}" ${candidate.district == district.code ? 'selected' : ''}>
                        ${district.name}
                    </option>
                `;
            });

            accountDistrict.innerHTML = districtSelectBoxHtml;
        }

        // bat su kien doi tinh thanh
        let checkChange = false; // neu bien nay la true thi district phai duoc chon moi thi moi duoc doi noi dung
        accountProvince.addEventListener('change', function () {
            createDistrictSelectBox(this.value);

            checkChange = true;
        });

        accountDistrict.addEventListener('change', function () {
            if(this.value != '9999') {
                checkChange = false;
            } 
        });

        const loading = document.querySelector('.loading__container');
        document.getElementById('account-form').addEventListener('submit', function (event) {
            if (checkChange) {
                event.preventDefault(); // Ngăn form gửi đi
                alert("Bạn không được phép sửa dữ liệu vì có thay đổi chưa xử lý.");
                setTimeout(() => {
                    loading.style.display = 'none';
                }, 1000);
            }
        });
        
    </script>
    {{-- js category --}}
    <script>
        let accountCategory = document.querySelector('.account__category');
        var categories = @json($categories);
        // var candidate = @json($candidate);
        // console.log(categories);

        let accountCategoryHtml = `
            <option value="9999">
                Chọn ngành nghề
            </option>
        `;
        categories.forEach((category) => {
            accountCategoryHtml += `
                <option value="${category.id}" ${candidate.id_category == category.id ? 'selected' : ''}>
                    ${category.name}
                </option>
            `;
        });
        accountCategory.innerHTML = accountCategoryHtml;
    </script>
@endsection