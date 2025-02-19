const theme = localStorage.getItem('theme');
if (theme) {
  document.documentElement.setAttribute('data-theme', theme);
}

document.querySelectorAll('input[data-choose-theme]').forEach((input) => {
  input.addEventListener('change', (e) => {
    const theme = e.target.value;
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
  });
});
