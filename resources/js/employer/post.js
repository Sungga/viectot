import '@/base/base.js';
import '@/base/header.js';

// show salary_type
const salary = document.querySelector('.salary');
document.getElementById('salary_type').addEventListener('change', function () {
    if (this.value === 'negotiable') {
        salary.innerHTML = '';
    }
    else if (this.value === 'range') {
        salary.innerHTML = `<div class="salary_type_range">
                                <input type="number" name="salary_from" id="" placeholder="Nhập mức lương" required>
                                <input type="number" name="salary_to" id="" placeholder="Nhập mức lương" required>
                            </div>`
    }
    else if (this.value === 'upto' || this.value === 'fixed' || this.value === 'starting_from') {
        salary.innerHTML = `<div class="salary_type_upto">
                                <input type="number" name="salary" id="" placeholder="Nhập mức lương" required>
                            </div>`
    }
    else {
        salary.innerHTML = '';
    }
});


// show and hide add post form
const showAddPostFormBtn = document.querySelector('.listJob__add');
const addPostForm = document.querySelector('.formAddPost');
const closeAddPostFormBtn = document.querySelector('.formAddPost__close');

showAddPostFormBtn.addEventListener('click', function () {
    addPostForm.style.display = 'flex';
});

closeAddPostFormBtn.addEventListener('click', function () {
    addPostForm.style.display = 'none';
});


// show desc post
document.querySelectorAll('.listJob__item').forEach(item => {
    const title = item.querySelector('.listJob__item--title');
    const desc = item.querySelector('.listJob__item--desc');
    let hoverTimer;

    title.addEventListener('mouseover', () => {
        // hoverTimer = setTimeout(() => {
            desc.style.display = 'block';
        // }, 1000); // Delay 1 giây
    });

    desc.addEventListener('mouseleave', () => {
        // clearTimeout(hoverTimer); // Hủy bỏ nếu chưa đủ 1 giây
        desc.style.display = 'none';
        // console.log(desc);
    });
});