import './bootstrap.js';

import './styles/front.css';

document.querySelectorAll('input[data-choose-theme]').forEach((input) => {
  input.addEventListener('change', (e) => {
    const theme = e.target.value;
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
  });
});
