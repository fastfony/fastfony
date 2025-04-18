<script setup>
import { onMounted, ref, watch } from 'vue';
import EditorJS from '@editorjs/editorjs';
import { editorjsTools } from '../../../js/editorjs';

const props = defineProps({
  context: Object,
});

const wrapper = ref(null);
const input = ref(null);
let editor = null;

onMounted(() => {
  editor = createRichEditor();
});

watch(
  () => props.context._value,
  (newValue) => {
    if (editor) {
      editor.save().then((outputData) => {
        if (
          undefined === newValue ||
          JSON.stringify(JSON.parse(newValue).blocks) !==
            JSON.stringify(outputData.blocks)
        ) {
          editor.destroy();
          editor = createRichEditor();
        }
      });
    }
  },
  { immediate: true },
);

function createRichEditor() {
  const editor = new EditorJS({
    holder: wrapper.value.id,
    tools: editorjsTools,
    onReady: () => {
      if (input.value.value.length > 0) {
        editor.render(JSON.parse(input.value.value));
      }
    },
    onChange: () => {
      editor.save().then((outputData) => {
        props.context.node.input(JSON.stringify(outputData));
      });
    },
  });
  return editor;
}
</script>

<template>
  <div class="card card-body">
    <div ref="wrapper" :id="'wrapper' + props.context.id"></div>
    <textarea
      ref="input"
      :value="props.context._value"
      :id="props.context.id"
      class="d-none"
    ></textarea>
  </div>
</template>
