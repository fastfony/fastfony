/* Symfony ux-vue */
import { registerVueControllerComponents } from '@symfony/ux-vue';
registerVueControllerComponents(
  require.context('./vue/controllers', true, /\.vue$/),
);
