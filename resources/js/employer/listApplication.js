import '@/base/base.js';
import '@/base/header.js';

// show pass message
const btnShowPass = document.querySelectorAll('.listCv__item--btnPass');
const exitPass = document.querySelector('.formPass__exit--icon');
const formPass = document.querySelector('.formPass');
const idCandidate = document.querySelectorAll('.id_candidate');
const idApply = document.querySelectorAll('.id_apply');
const idCandidateForm = document.querySelector('#id_candidate');
const idApplyForm = document.querySelector('#id_apply');

btnShowPass.forEach((item, index) => {
    item.addEventListener('click', () => {
        formPass.style.display = 'flex';
        // idCandidateForm.value = idCandidate[index];
        idCandidateForm.setAttribute('value', idCandidate[index].getAttribute('value'));
        idApplyForm.setAttribute('value', idApply[index].getAttribute('value'));
    });
});

exitPass.addEventListener('click', () => {
    formPass.style.display = 'none';
});