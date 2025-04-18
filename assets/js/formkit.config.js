import { en } from '@formkit/i18n';
import { createInput } from '@formkit/vue';
import EditorJs from '../vue/components/Pro/EditorJs.vue';

const config = {
  locales: { en },
  locale: 'en',
  config: {
    classes: {
      label: 'form-label',
      messages: 'd-block invalid-feedback mx-1',
    },
  },
  inputs: {
    editorJs: createInput(EditorJs),
  },
};

export default config;
