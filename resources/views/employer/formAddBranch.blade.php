

{{-- add branch --}}
<section class="post-addBranch">
    <div class="post-addCompany__cont">
        <form action="{{ route('employer.addBranch') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="post-addCompany__container">
                <input type="text" name="id_company" id="" hidden value="{{ $companyId }}">
                <h3 class="post--addCompany__title">Thêm chi nhánh công ty của bạn</h3>
                <div class="post-addBranch__close"><i class="fa-solid fa-left-long"></i></div>
                <button class="post-addBranch__save"><i class="fa-solid fa-floppy-disk"></i></button>
                {{-- name --}}
                <input type="text" class="post-addCompany__name" placeholder="Nhập tên chi nhánh của bạn" name="name" id="name" required>
                
                {{-- address --}}
                <p class="post-addCompany__miniTitle">Địa chỉ chi nhánh</p>
                <div class="post-addCompany__address">
                    <select name="province" id="" class="post-addCompany__province"></select>
                    <select name="district" id="" class="post-addCompany__district"></select>
                </div>
                
                {{-- desc --}}
                <p class="post-addCompany__miniTitle">Mô tả chi nhánh</p>
                <textarea class="post-addCompany__desc" placeholder="Nhập mô tả chi nhánh của bạn" name="desc" id="ckeditorAddBranch" required></textarea>
                
                {{-- image --}}
                <p class="post-addCompany__miniTitle post-addCompany__miniTitle--document">Hình ảnh thực tế của chi nhánh</p>
                <label class="post-addBranch__img" for="post-addBranch__img"><i class="fa-solid fa-cloud-arrow-up"></i></label>
                <input type="file" name="img[]" accept=".jpg,.jpeg,.png" required multiple id="post-addBranch__img" style="opacity: 0; position: absolute; z-index: -1;">  
                
                {{-- map --}}
                <div id="map"></div>
                <label>Latitude:</label>
                <input type="text" id="latitude" name="latitude" readonly><br><br>

                <label>Longitude:</label>
                <input type="text" id="longitude" name="longitude" readonly><br><br>
            </div>
        </form>
    </div>
</section>

{{-- js show prince and district --}}
{{-- <script>
    const accountProvince = document.querySelector('.post-addCompany__province');
    const accountDistrict = document.querySelector('.post-addCompany__district');
    let cachedData = null;

    // document.addEventListener('DOMContentLoaded', async () => {
    // });
    const url = "https://provinces.open-api.vn/api/?depth=2";

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Lỗi: ${response.status}`);
        }
        cachedData = await response.json(); // Lưu dữ liệu vào biến toàn cục
        // console.log("Dữ liệu đã được cache:", cachedData);

        // Sau khi cache dữ liệu, tạo select box
        // createDistrictSelectBox(candidate.province);
    } catch (error) {
        console.error("Lỗi khi gọi API:", error);
    }
    createSelectBox();
    
    function createSelectBox() {
        if (!cachedData || !Array.isArray(cachedData)) {
            console.error("Dữ liệu tỉnh/thành phố không hợp lệ.");
            return;
        }

        let provincesSelectBoxHtml = '';
        cachedData.forEach((province) => {
            provincesSelectBoxHtml += `
                <option value="${province.code}">
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

        let districtSelectBoxHtml = '';
        province.districts.forEach((district) => {
            districtSelectBoxHtml += `
                <option value="${district.code}">
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
    // document.getElementById('account-form').addEventListener('submit', function (event) {
    //     if (checkChange) {
    //         event.preventDefault(); // Ngăn form gửi đi
    //         alert("Bạn không được phép sửa dữ liệu vì có thay đổi chưa xử lý.");
    //         setTimeout(() => {
    //             loading.style.display = 'none';
    //         }, 1000);
    //     }
    // });
</script> --}}
<script>
    function initProvinceDistrict(formElement) {
        const provinceSelect = formElement.querySelector('.post-addCompany__province');
        const districtSelect = formElement.querySelector('.post-addCompany__district');
        let cachedData = null;

        fetch("https://provinces.open-api.vn/api/?depth=2")
            .then(res => res.json())
            .then(data => {
                cachedData = data;

                // render provinces
                provinceSelect.innerHTML = data.map(p =>
                    `<option value="${p.code}">${p.name}</option>`
                ).join('');

                // handle change province
                provinceSelect.addEventListener('change', function () {
                    const selected = data.find(p => p.code == this.value);
                    if (selected) {
                        districtSelect.innerHTML = selected.districts.map(d =>
                            `<option value="${d.code}">${d.name}</option>`
                        ).join('');
                    }
                });
            })
            .catch(error => {
                console.error("Lỗi khi lấy danh sách tỉnh thành:", error);
            });
    }
</script>

{{-- js map --}}
<script>
    function initMap() {
        // Nếu đã có map cũ thì remove
        if (window.map) {
            window.map.remove();
        }

        window.map = L.map('map').setView([10.762622, 106.660172], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(window.map);

        let marker;

        window.map.on('click', function (e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (marker) {
                window.map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(window.map)
                .bindPopup("Vị trí bạn chọn").openPopup();
        });

        // Quan trọng: Cập nhật lại kích thước nếu map nằm trong modal, tab, ajax, v.v.
        setTimeout(() => {
            window.map.invalidateSize();
        }, 100);
    }

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('map')) {
            initMap();
        }
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('ckeditorAddBranch');
        }
    });
</script>

{{-- js ckEditor --}}
{{-- <script src="//cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script> --}}
{{-- <script>
    CKEDITOR.replace('ckeditorAddBranch', {
    //     // tuỳ chọn config
    });
</script> --}}