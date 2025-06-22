<script setup>
import { onMounted, ref, watch } from 'vue';
import Quill from 'quill';
import toolbarOptions from '../../js/richTextEditor';

const props = defineProps({
  context: Object,
});

const wrapper = ref(null);
const input = ref(null);
let editor = null;

onMounted(() => {
  editor = createRichTextEditor();
});

watch(
  () => props.context._value,
  (newValue) => {
    if (editor) {
      if (undefined === newValue || newValue !== editor.root.innerHTML) {
        editor.root.innerHTML = newValue;
      }
    }
  },
  { immediate: true },
);

function createRichTextEditor() {
  const editor = new Quill('#' + wrapper.value.id, {
    theme: 'snow',
    modules: {
      toolbar: toolbarOptions,
    },
  });

  editor.on('text-change', function () {
    props.context.node.input(editor.root.innerHTML);
  });

  return editor;
}
</script>

<template>
  <div ref="wrapper" :id="'wrapper' + props.context.id"></div>
  <textarea
    ref="input"
    :value="props.context._value"
    :id="props.context.id"
    class="d-none"
  ></textarea>
</template>
