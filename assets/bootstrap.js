import './js/loader.js';
import './js/theme.js';

/** Symfony ux-stimulus */
import { startStimulusApp } from '@symfony/stimulus-bridge';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

/** Symfony ux-vue */
import { createI18n } from 'vue-i18n';
import en from './locales/app.en.json';

import { registerVueControllerComponents } from '@symfony/ux-vue';
registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));

document.addEventListener('vue:before-mount', (event) => {
  const {
    componentName, // The Vue component's name
    component, // The resolved Vue component
    props, // The props that will be injected to the component
    app, // The Vue application instance
  } = event.detail;

  const i18n = createI18n({
    legacy: false,
    locale: 'en',
    fallbackLocale: 'en',
    messages: { en },
  });

  app.use(i18n);
});
