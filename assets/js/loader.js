import axios from 'axios';

const loader = document.getElementById('loader');
if (loader) {
  axios.interceptors.request.use((config) => {
    loader.classList.remove('hidden');
    return config;
  });

  axios.interceptors.response.use((response) => {
    loader.classList.add('hidden');
    return response;
  }, function (error) {
    loader.classList.add('hidden');
    return Promise.reject(error);
  });
}
