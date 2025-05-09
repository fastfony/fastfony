import './js/loader.js';

/* Symfony ux-stimulus */
import { startStimulusApp } from '@symfony/stimulus-bridge';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(
  require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/,
  ),
);

/* DaisyUI theme chooser */
document.querySelectorAll('input[data-choose-theme]').forEach((input) => {
  input.addEventListener('change', (e) => {
    const theme = e.target.value;
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
  });
});

/* Symfony ux-vue */
import { registerVueControllerComponents } from '@symfony/ux-vue';
registerVueControllerComponents(
  require.context('./vue/controllers', true, /\.vue$/),
);

/* Internationalisation */
import { createI18n } from 'vue-i18n';
import en from './locales/app.en.json';

/* Toast */
import Toast from 'vue-toastification';
import { useToast } from 'vue-toastification';
import 'vue-toastification/dist/index.css';
const toast = useToast();

/* FormKit */
import { plugin, defaultConfig } from '@formkit/vue';
import formkitConfig from './js/formkit.config.js';

/* PrimeVue */
import { createApp } from 'vue';
import PrimeVue from 'primevue/config';

import { createPinia } from 'pinia';
import axios from 'axios';

document.addEventListener('vue:before-mount', (event) => {
  const {
    app, // The main Vue application instance
  } = event.detail;

  const i18n = createI18n({
    legacy: false,
    locale: 'en',
    fallbackLocale: 'en',
    messages: { en },
  });

  app.use(i18n);
  app.use(Toast, {});
  app.use(plugin, defaultConfig(formkitConfig));
  app.use(PrimeVue, {
    theme: 'none',
  });

  // Capture and toast all axios errors
  axios.interceptors.response.use(
    (response) => {
      return response;
    },
    function (error) {
      if (error.response) {
        const ignoreErrors = [];
        // Si ce n'est pas dans les erreurs à ignorer
        if (
          !ignoreErrors.find(
            (errorCodeIgnore) => errorCodeIgnore === error.response.status,
          )
        ) {
          if (error.response.data && error.response.data.detail) {
            toast.error(error.response.data.detail, {
              timeout: 15000,
            });
            return;
          }

          toast.error(
            '' !== error.response.statusText
              ? error.response.statusText
              : error.message,
            {
              timeout: 15000,
            },
          );
        }
      }

      return Promise.reject(error);
    },
  );

  const pinia = createPinia();
  app.use(pinia);
});
