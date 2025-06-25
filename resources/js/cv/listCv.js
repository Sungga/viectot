// ------------------------------------------<< js show menu user >>------------------------------------
const userBtn = document.querySelector('.header__item--account');
const menuUser = document.querySelector('.header__menu--user');

// Toggle hiển thị khi click vào nút user
userBtn.addEventListener('click', (event) => {
    event.stopPropagation(); // Ngăn chặn sự kiện click lan ra ngoài
    menuUser.style.visibility = menuUser.style.visibility === 'visible' ? 'hidden' : 'visible';
    menuUser.style.opacity = menuUser.style.visibility === 'hidden' ? '0' : '1';
    menuUser.style.top = menuUser.style.visibility === 'hidden' ? '0' : '100%';
});

// Ẩn menu khi click ra ngoài
document.addEventListener('click', (event) => {
    if (!menuUser.contains(event.target) && !userBtn.contains(event.target)) {
        menuUser.style.visibility = 'hidden';
        menuUser.style.opacity = '0';
        menuUser.style.top = '0';
    }
});

// --------------------------------------<< js show form list buy CV >>-----------------------------------------
const showFormBuyCvBtn = document.querySelector('#btn-add-limit');
const hiddenFormBuyCvBtn = document.querySelector('.buyCv__exit');
const formBuyCv = document.querySelector('.buyCv');
const formBuyCvCont = document.querySelector('.buyCv__container');

const showFormAddCvBtn = document.querySelector('.listCv__top--addBtn');
const hiddenFormAddCvBtn = document.querySelector('.addCv__exit');
const formAddCv = document.querySelector('.addCv');
const formAddCvCont = document.querySelector('.addCv__container');

function toggleContainer(showFormBtn, hiddenFormBtn, form, formCont) {
    showFormBtn.addEventListener('click', function() {
        form.style.display = 'flex';
    })
    hiddenFormBtn.addEventListener('click', function() {
        form.style.display = 'none';
    })
    document.addEventListener('click', (event) => {
        if (!formCont.contains(event.target) && !showFormBtn.contains(event.target)) {
            form.style.display = 'none';
        }
    });
}

const cvLimit = document.querySelector('.listCv__listLeft--limit');
const cvQuantity = document.querySelector('.listCv__listLeft--quantity');
const intCvLimit = parseInt(cvLimit.textContent);
const intCvQuantity = parseInt(cvQuantity.textContent);
if(intCvLimit > intCvQuantity) {
    toggleContainer(showFormAddCvBtn, hiddenFormAddCvBtn, formAddCv, formAddCvCont);
}
else {
    showFormAddCvBtn.classList.add('listCv__top--notAllowed');
}

toggleContainer(showFormBuyCvBtn, hiddenFormBuyCvBtn, formBuyCv, formBuyCvCont);

// -----------------<< JS FOR INPUT FILE CV >>---------------------
const fileInputCv = document.getElementById('cv');
const labelCv = document.querySelector('label[for="cv"]');

// Hàm xử lý khi chọn hoặc thả file
function handleFileChange(event) {
    const file = event.target.files ? event.target.files[0] : event.dataTransfer.files[0]; // Lấy file từ sự kiện

    if (file) {
        const maxSizeMB = 2;
        const maxSizeBytes = maxSizeMB * 1024 * 1024;

        if (file.size > maxSizeBytes) {
            alert(`File quá lớn! Dung lượng tối đa cho phép là ${maxSizeMB}MB.`);
            fileInputCv.value = ''; // Reset input
            labelCv.textContent = 'Tải lên CV của bạn'; // Reset label
        } else {
            labelCv.textContent = file.name;
        }
    }
}

// Kiểm tra khi chọn file (input)
fileInputCv.addEventListener('change', handleFileChange);

// Kiểm tra khi kéo thả file vào label
labelCv.addEventListener('dragover', (e) => {
    e.preventDefault(); // Cho phép kéo thả vào
    labelCv.classList.add('dragover'); // Hiệu ứng kéo thả
});

labelCv.addEventListener('dragleave', () => {
    labelCv.classList.remove('dragover'); // Loại bỏ hiệu ứng khi kéo ra ngoài
});

labelCv.addEventListener('drop', (e) => {
    e.preventDefault();
    labelCv.classList.remove('dragover'); // Loại bỏ hiệu ứng kéo thả

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(files[0]);
        fileInputCv.files = dataTransfer.files; // Gán file vào input

        // Gọi hàm kiểm tra dung lượng và thay đổi label
        handleFileChange({ target: fileInputCv });
    }
});


// ----------------<< loading >>--------------------
const form = document.querySelector('form');