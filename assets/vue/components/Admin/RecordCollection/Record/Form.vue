<script setup>
import { useRecordCollectionsStore } from '../../../../stores/Admin/recordCollections';
const store = useRecordCollectionsStore();

import { computed, ref, watch } from 'vue';
import { FormKitSchema } from '@formkit/vue';
import { reset } from '@formkit/core';
import axios from 'axios';

const emit = defineEmits(['update:records']);

const closeButton = ref(null);
const formSchema = ref([]);
const data = ref({});
const isNew = computed(() => {
  return undefined === store.record['@id'];
});

watch(
  () => store.record,
  () => {
    if (store.record) {
      data.value = store.record.fields;
    }
  },
);

watch(
  () => store.selected,
  () => {
    if (store.selected) {
      buildFormSchema(store.selected);
    }
  },
);

function buildFormSchema(recordCollection) {
  if (recordCollection) {
    formSchema.value = recordCollection.fields.map((field) => {
      const id = 'field' + field.id;

      return {
        name: field.name,
        label: field.name,
        id: id,
        $formkit: getFormKitType(field.type),
        validation: field.nonempty ? 'required' : null,
        outerClass: 'mb-3',
        inputClass:
          'form-control' + (field.type === 'richEditor' ? ' rich-editor' : ''),
        labelClass: 'form-label text-capitalize',
      };
    });
  }
}

function getFormKitType(type) {
  switch (type) {
    case 'plainText':
      return 'text';
    case 'richEditor':
      return 'richTextEditor';
    case 'number':
      return 'number';
    case 'boolean':
      return 'checkbox';
    case 'date':
      return 'date';
    case 'datetime':
      return 'datetime-local';
    case 'email':
      return 'email';
    case 'url':
      return 'url';
    case 'select':
      return 'select';
    case 'file':
      return 'file';
    case 'json':
      return 'textarea';
    default:
      return 'text';
  }
}

function handleSubmit() {
  const method = !isNew.value ? 'put' : 'post';
  const url = !isNew.value
    ? `/api/internal/records/${store.record['id']}`
    : '/api/internal/records';

  store.record.collection = store.selected['@id'];
  store.record.fields = data.value;
  delete store.record.fields['slots'];

  axios({
    method: method,
    url: url,
    data: store.record,
  })
    .then((response) => {
      if (
        undefined !== response &&
        (response.status === 201 || response.status === 200)
      ) {
        closeButton.value?.click();
        if (response.status === 201) {
          reset('record-form');
        }

        emit('update:records');
      }
    })
    .catch((error) => {
      console.error('Error saving record:', error);
    });
}
</script>

<template>
  <FormKit
    type="form"
    ref="form"
    id="record-form"
    v-model="data"
    @submit="handleSubmit"
    :actions="false"
    class="d-flex flex-column flex-grow-1"
    :config="{ validationVisibility: 'submit' }"
  >
    <FormKitSchema :schema="formSchema" :data="data" />
    <hr class="my-4" />
    <div class="mt-auto d-flex justify-content-end gap-2">
      <button
        type="button"
        class="btn btn-secondary"
        data-bs-dismiss="offcanvas"
        aria-label="Close"
        ref="closeButton"
      >
        {{ $t('Cancel') }}
      </button>
      <FormKit
        type="submit"
        outer-class="$reset"
        wrapper-class="$reset"
        input-class="$reset btn btn-primary"
      >
        {{ store.record ? $t('Update') : $t('Create') }}
      </FormKit>
    </div>
  </FormKit>
</template>
