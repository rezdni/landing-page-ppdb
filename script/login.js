const container = document.querySelector('.container');
const resgisterBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.');

resgisterBtn.addEventListener('click', () => {
    container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});