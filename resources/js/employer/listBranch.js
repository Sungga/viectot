import '@/base/base.js';
import '@/base/header.js';

// js change logo when upload new logo
const input = document.getElementById('avatar-input');
    const preview = document.getElementById('avatar-preview');

input.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
        }

        reader.readAsDataURL(file);
    }
});

// js show form add company when click add company button
const btnShowFormAddCompany = document.querySelector('.post-top__addCompany');
const formAddCompany = document.querySelector('.post-addCompany');
const btnCloseFormAddCompany = document.querySelector('.post-addCompany__close');

btnShowFormAddCompany.addEventListener('click', function () {
    formAddCompany.style.display = 'flex';
});

btnCloseFormAddCompany.addEventListener('click', function () {
    formAddCompany.style.display = 'none';
});

// js confirm added company documents
const companyDocumentsInput = document.getElementById('post-addCompany__document');
const companyDocumentsLabel = document.querySelector('.post-addCompany__document');

const branchImgsInput = document.getElementById('post-addBranch__img');
const branchImgsLabel = document.querySelector('.post-addBranch__img');

if(branchImgsInput != null && branchImgsLabel != null) {
    companyDocumentsInput.addEventListener('change', function () {
        companyDocumentsLabel.innerHTML = '<i class="fa-solid fa-check" style="color: var(--color-base);"></i>';
    }); 
    
    branchImgsInput.addEventListener('change', function () {
        branchImgsLabel.innerHTML = '<i class="fa-solid fa-check" style="color: var(--color-base);"></i>';
    }); 
}

// js show form add branch when click add branch button
const btnShowFormAddBranch = document.querySelectorAll('.post-yourCompany__addBranch');
const formAddBranch = document.querySelector('.post-addBranch');
const btnCloseFormAddBranch = document.querySelector('.post-addBranch__close');
const inputIdCompany = document.querySelector('.post-addBranch__idCompany');

btnShowFormAddBranch.forEach((btn, index) => {
    btn.addEventListener('click', function () {
        const idCompany = btn.getAttribute('id_company');
        inputIdCompany.value = idCompany;
        formAddBranch.style.display = 'flex';
    });
});

btnCloseFormAddBranch.addEventListener('click', function () {
    formAddBranch.style.display = 'none';
});

