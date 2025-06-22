import { en } from '@formkit/i18n';
import { createInput } from '@formkit/vue';
import RichTextEditor from '../vue/components/RichTextEditor.vue';

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
    richTextEditor: createInput(RichTextEditor),
  },
};

export default config;
