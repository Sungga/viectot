import '@/base/base.js';
import '@/base/header.js';

// ---------------------<< js search >>---------------------------------
// show or hide location list
const btnToggleLocation = document.querySelector('.search__location--btn');
const locationList = document.querySelector('.search__location--list');
function toggleLocationList() {
    locationList.classList.toggle('show');
}
// toggleLocationList()

btnToggleLocation.addEventListener("click", toggleLocationList);

// make location
const provincesSelectBox = document.querySelector('.search__location--province ul');
const districtSelectBox = document.querySelector('.search__location--district ul');

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

// Tạo selectbox chức năng chọn tỉnh/thành phố
async function createSelectBox() {
    const data = await getData(); // Lấy dữ liệu
    
    let provincesSelectBoxHtml = '';
    for(let i = 0; i < data.length; i++) {
        provincesSelectBoxHtml += `
        <li>
            <input type="checkbox" name="${data[i].code}" id="">
            <p>${data[i].name}</p>
            <i class="fa-solid fa-angle-right search__location--toDistrict" code="${data[i].code}"></i>
        </li>
        `;
    }
    
    provincesSelectBox.innerHTML = provincesSelectBoxHtml;
}

async function createDistrictSelectBox() {
    const data = await getData(); // Lấy dữ liệu

    let districtSelectBoxHtml = '';
    data.forEach((dataItem, index) => {
        districtSelectBoxHtml += `
            <div code="${dataItem.code}" style="display: none;">
                <li>
                    <input type="checkbox" name="all_${dataItem.code}" id="" province_code="${dataItem.code}" class="all-district">
                    <p>Tất cả</p>
                </li>
            `;
        for (var i = 0; i < dataItem.districts.length; i++) {
            districtSelectBoxHtml += `
            <li>
                <input type="checkbox" name="${dataItem.districts[i].code}" id="" province_code="${dataItem.code}">
                <p>${dataItem.districts[i].name}</p>
            </li>
            `;
        }
        districtSelectBoxHtml += `</div>`;
    });
    districtSelectBox.innerHTML = districtSelectBoxHtml;
}
    
// Click vào checkbox tỉnh/thành phố để hiện selectbox quận/huyện
function showDistrictSelectBox (id) {
    const districtSelectBoxItem = document.querySelectorAll('.search__location--district ul>div');
    districtSelectBoxItem.forEach((item, index) => {
        if (item.getAttribute('code') == id) {
            item.style.display = 'block';
        }
        else {
            item.style.display = 'none';
        }
    });
}

function hideDistrictSelectBox(id) {
    const districtSelectBoxItem = document.querySelectorAll('.search__location--district ul>div');
    districtSelectBoxItem.forEach((item, index) => {
        if (item.getAttribute('code') == id) {
            item.style.display = 'none';
        }
    });
}

function allDistrictCheckedToFalse(id) {
    const districtInputItem = document.querySelectorAll(`.search__location--district ul input[province_code="${id}"]`);
    districtInputItem.forEach((item, index) => {
        item.checked = false;
    });
}

function allDistrictCheckedToTrue(id) {
    const districtInputItem = document.querySelectorAll(`.search__location--district ul input[province_code="${id}"]`);
    districtInputItem.forEach((item, index) => {
        item.checked = true;
    });
}

// Khởi tạo province list và district list
createSelectBox();
createDistrictSelectBox();

document.addEventListener("click", function (event) {
    // click vao nut to district thì nó sẽ hiện thị bảng district của province đấy
    if (event.target.classList.contains("search__location--toDistrict")) {
        const codeValue = event.target.getAttribute("code");
        showDistrictSelectBox(codeValue);
    }
    // click vao checkbox quận/huyện thì nó sẽ chỉnh sửa ẩn/hiện bảng district của province đấy
    if (event.target.matches(".search__location--province input[type='checkbox']")) {
        console.log("hehe"); //
        if (event.target.checked) { // Chỉ chạy khi checkbox chuyển từ uncheck → checked
            const codeValue = event.target.getAttribute("name");
            showDistrictSelectBox(codeValue);

            // thay doi trang thai check cua district
            allDistrictCheckedToTrue(codeValue);
        }
        else {
            const codeValue = event.target.getAttribute("name");
            hideDistrictSelectBox(codeValue);
            
            // thay doi trang thai check cua district
            allDistrictCheckedToFalse(codeValue);
        }
    }
    // Click vao input tat ca thi se co thay doi
    if (event.target.matches(".all-district")) {
        if (!event.target.checked) {
            const provinceCode = event.target.getAttribute("province_code");
            allDistrictCheckedToFalse(provinceCode);

            // checked = false khi huy chon tat ca district
            const inputProvinceWithCode = document.querySelector(`.search__location--province input[type='checkbox'][name='${provinceCode}']`);
            // inputProvinceWithCode.checked = false;
            console.log(inputProvinceWithCode);
        }
        else {
            const provinceCode = event.target.getAttribute("province_code");
            allDistrictCheckedToTrue(provinceCode);
        }
    }
    // click vao mot district thi province tuong ung se duoc checked
    if (event.target.matches(".search__location--district input[type='checkbox']")) {
        const provinceCode = event.target.getAttribute("province_code");
        const inputProvinceWithCode = document.querySelector(`.search__location--province input[type='checkbox'][name='${provinceCode}']`);

        // Lấy checkbox "Tất cả" của tỉnh hiện tại (thay vì lấy tất cả)
        const inputAllDistrict = document.querySelector(`.search__location--district input.all-district[province_code='${provinceCode}']`);

        // Lấy tất cả các quận/huyện của tỉnh hiện tại
        const allDistricts = document.querySelectorAll(`.search__location--district input[type='checkbox'][province_code='${provinceCode}']:not(.all-district)`);
        if(event.target.checked) {
            inputProvinceWithCode.checked = true;

            // Nếu tất cả quận/huyện được chọn =>  chọn "Tất cả"
            const isEveryChecked = Array.from(allDistricts).every(district => district.checked);
            if (isEveryChecked) {
                inputAllDistrict.checked = true;
            }
        }
        else {
            // Nếu có ít nhất 1 quận/huyện bị bỏ chọn => bỏ chọn "Tất cả"
            const isEveryChecked = Array.from(allDistricts).every(district => district.checked);
            if (!isEveryChecked && inputAllDistrict) { 
                inputAllDistrict.checked = false;
            }

            // Nếu không có quận/huyện nào được chọn => bỏ chọn tỉnh
            const isAnyChecked = Array.from(allDistricts).some(district => district.checked);
            if (!isAnyChecked) {
                inputProvinceWithCode.checked = isAnyChecked;
            }
        }
    }
});

// Lắng nghe sự kiện nhập trên ô tìm kiếm tỉnh/thành phố
document.querySelector(".search__location--provinceSearch input[type='text']").addEventListener("input", function () {
    console.log("hehe");
    const searchText = this.value.toLowerCase();
    const listItems = document.querySelectorAll(".search__location--province li");

    listItems.forEach((item) => {
        const provinceName = item.querySelector("p").textContent.toLowerCase();
        item.style.display = provinceName.includes(searchText) ? "flex" : "none";
    });
});

// ---------------------------------------------<< js category >>------------------------------------------------
const categoryList = document.querySelectorAll('.slider__category--list');
const sliderPaginationNumber = document.querySelector('.slider__pagination .slider__pagination--left p');
const prevButton = document.querySelector('.slider__pagination .slider__pagination--right .prevButton');
const nextButton = document.querySelector('.slider__pagination .slider__pagination--right .nextButton');

function createSliderPaginationNumber() {
    const categoryListCount = categoryList.length;
    sliderPaginationNumber.innerHTML = `
        <span class="current-page">1</span>
        /
        <span class="total-pages">${categoryListCount}</span>
    `;
}
createSliderPaginationNumber();

function updatePageButtons() {
    const currentPage = document.querySelector('.slider__pagination .slider__pagination--left .current-page');
    const totalPages = document.querySelector('.slider__pagination .slider__pagination--left .total-pages');

    if(currentPage.innerHTML == '1') {
        document.querySelector('.slider__pagination .slider__pagination--right .prevButton').classList.add('disabled');
        document.querySelector('.slider__pagination .slider__pagination--right .nextButton').classList.remove('disabled');
    }
    else if(currentPage.innerHTML == totalPages.innerHTML) {
        document.querySelector('.slider__pagination .slider__pagination--right .prevButton').classList.remove('disabled');
        document.querySelector('.slider__pagination .slider__pagination--right .nextButton').classList.add('disabled');
    }
    else {
        document.querySelector('.slider__pagination .slider__pagination--right .prevButton').classList.remove('disabled');
        document.querySelector('.slider__pagination .slider__pagination--right .nextButton').classList.remove('disabled');
    }
}
updatePageButtons();

function updatePage(indexShow) {
    indexShow--;
    categoryList.forEach((item, index) => {
        if(indexShow == index) {
            item.style.display = 'block';
        }
        else {
            item.style.display = 'none';
        }
    })
}
updatePage(1);

function goToNextPage() {
   
}

// Chuyển đến trang tiếp theo
function clickGoToNextPage() {
    const currentPageEl = document.querySelector('.slider__pagination .slider__pagination--left .current-page');
    const totalPagesEl = document.querySelector('.slider__pagination .slider__pagination--left .total-pages');
    
    let currentPage = parseInt(currentPageEl.innerHTML);
    let totalPages = parseInt(totalPagesEl.innerHTML);

    if (currentPage < totalPages) {
        const value = currentPage + 1;
        currentPageEl.innerHTML = value;
        updatePageButtons();
        updatePage(value);
    }
}

// Quay lại trang trước
function clickGoToPrevPage() {
    const currentPageEl = document.querySelector('.slider__pagination .slider__pagination--left .current-page');
    
    let currentPage = parseInt(currentPageEl.innerHTML);
    
    if (currentPage > 1) {
        const value = currentPage - 1;
        currentPageEl.innerHTML = value;
        updatePageButtons();
        updatePage(value);
    }
}

// Gắn sự kiện click cho nút chuyển trang
prevButton.addEventListener('click', clickGoToPrevPage);
nextButton.addEventListener('click', clickGoToNextPage);

// ---------------------------------------------<< js slider job ad >>------------------------------------------------
const sliderAdImgs = document.querySelectorAll('.slider__jobAd img');
let indexValue = 0;
let statusIndexValueIsNext = true;

function setSlidePosition(indexShow) {
    indexValue = indexShow;
    for (let i = 0; i < sliderAdImgs.length; i++) {
        sliderAdImgs[i].style.left = `${(i - indexShow ) * 100}%`;
    }
}
setSlidePosition(0);

function autoPlay() {
    if (indexValue >= sliderAdImgs.length - 1) {  
        statusIndexValueIsNext = false;  // Khi đến ảnh cuối, quay lại
    } 
    else if (indexValue <= 0) {  
        statusIndexValueIsNext = true;   // Khi về ảnh đầu, tiến tới
    }

    indexValue += statusIndexValueIsNext ? 1 : -1;
    setSlidePosition(indexValue);

    setTimeout(autoPlay, 5000);
}
setTimeout(autoPlay, 5000);

// --------------------------------------<< DASHBOARD >>------------------------------------------
const btnCloseDashboard = document.querySelector('.dashboard__close i');
const dashboard = document.querySelector('.dashboard');
const btnShowDashboard = document.querySelector('.slider__workMarket--seeMore');
const dashboardContainer = document.querySelector('.dashboard__container');

// close dashboard
btnCloseDashboard.addEventListener('click', function() {
    dashboard.style.display = 'none';
});

// show dashboard
btnShowDashboard.addEventListener('click', function() {
    dashboard.style.display = 'flex';
});


// Khi click vào dashboard mà không phải dashboard__container thì đóng dashboard
dashboard.addEventListener('click', function(event) {
    if (!dashboardContainer.contains(event.target)) {
        dashboard.style.display = 'none';
    }
});

// --------------------------------------<< filter cv >>------------------------------------------
const listTypeJob = document.querySelector('.listCv__filter--typeListType');
const filterJob = document.querySelector('.listCv__filter--typeSelect');
const btnFilterCities = document.querySelector('.listCv__filter--cities');
const btnFilterSalary = document.querySelector('.listCv__filter--salary');
const btnFilterExperience = document.querySelector('.listCv__filter--experience');
const btnFilterCategories = document.querySelector('.listCv__filter--categories');
const filterCities = document.querySelector('.listCv__filter--filterCities');
const filterSalary = document.querySelector('.listCv__filter--filterSalary');
const filterExperience = document.querySelector('.listCv__filter--filterExperience');
const filterCategory = document.querySelector('.listCv__filter--filterCategories');
const moveLeftFilter = document.querySelector('.listCv__filter--moveLeft');
const moveRightFilter = document.querySelector('.listCv__filter--moveRight');
const listFilter = document.querySelectorAll('.listCv__filter--filterList');
const listTypeItem = document.querySelectorAll('.listCv__filter--typeItemType');
var value = 0;

filterJob.addEventListener('click', () => {
    if(listTypeJob.style.display == 'block') {
        listTypeJob.style.display = 'none';
        console.log('1');
    }
    else {
        console.log('2');
        listTypeJob.style.display = 'block';
    }
});

function changeTypeFilter() {   
    btnFilterCities.addEventListener('click', () => {
        filterCities.style.display = 'flex';
        filterSalary.style.display = 'none';
        filterExperience.style.display = 'none';
        filterCategory.style.display = 'none';
        listTypeItem[value].classList.remove('selected');
        value = 0;
        listTypeItem[value].classList.add('selected');
    });

    btnFilterSalary.addEventListener('click', () => {
        filterCities.style.display = 'none';
        filterSalary.style.display = 'flex';
        filterExperience.style.display = 'none';
        filterCategory.style.display = 'none';
        listTypeItem[value].classList.remove('selected');
        value = 1;
        listTypeItem[value].classList.add('selected');
    });

    btnFilterExperience.addEventListener('click', () => {
        filterCities.style.display = 'none';
        filterSalary.style.display = 'none';
        filterExperience.style.display = 'flex';
        filterCategory.style.display = 'none';
        listTypeItem[value].classList.remove('selected');
        value = 2;
        listTypeItem[value].classList.add('selected');
    });

    btnFilterCategories.addEventListener('click', () => {
        filterCities.style.display = 'none';
        filterSalary.style.display = 'none';
        filterExperience.style.display = 'none';
        filterCategory.style.display = 'flex';
        listTypeItem[value].classList.remove('selected');
        value = 3;
        listTypeItem[value].classList.add('selected');
    });
}
changeTypeFilter();

moveLeftFilter.addEventListener('click', () => {
    listFilter[value].style.left = 0;
    listFilter[value].style.right = 'unset';
});

moveRightFilter.addEventListener('click', () => {
    listFilter[value].style.right = 0;
    listFilter[value].style.left = 'unset';
});



