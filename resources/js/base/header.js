// ------------------------------------------<< js show menu user >>------------------------------------
const userBtn = document.querySelector('.header__item--account');
const menuUser = document.querySelector('.header__menu--user');

if(userBtn != null) {
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
}

// ------------------------------------------<< js show menu alert >>------------------------------------
const alertBtn = document.querySelector('.header__menu--iconAlert');
const menuAlert = document.querySelector('.header__menu--alert');

if(alertBtn != null) {
    // Toggle hiển thị khi click vào nút user
    alertBtn.addEventListener('click', (event) => {
        event.stopPropagation(); // Ngăn chặn sự kiện click lan ra ngoài
        menuAlert.style.visibility = menuAlert.style.visibility === 'visible' ? 'hidden' : 'visible';
        menuAlert.style.opacity = menuAlert.style.visibility === 'hidden' ? '0' : '1';
        menuAlert.style.top = menuAlert.style.visibility === 'hidden' ? '0' : 'calc(100% + 12px)';
    });
    
    // Ẩn menu khi click ra ngoài
    document.addEventListener('click', (event) => {
        if (!menuAlert.contains(event.target) && !alertBtn.contains(event.target)) {
            menuAlert.style.visibility = 'hidden';
            menuAlert.style.opacity = '0';
            menuAlert.style.top = '0';
        }
    });
}
