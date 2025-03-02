import pluginVue from 'eslint-plugin-vue';
import skipFormatting from '@vue/eslint-config-prettier/skip-formatting';

export default [
  {
    name: 'app/files-to-lint',
    files: ['**/*.{js,mjs,jsx,vue}'],
  },
  {
    name: 'app/files-to-ignore',
    ignores: ['**/dist/**', '**/dist-ssr/**', '**/coverage/**'],
  },
  ...pluginVue.configs['flat/essential'],
  {
    rules: {
      'vue/multi-word-component-names': 0,
      'vue/require-default-prop': 'off',
      'vue/require-v-for-key': 'off',
    },
  },
  skipFormatting,
];
