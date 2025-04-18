<script setup>
import { useRecordCollectionsStore } from '../../../../stores/Pro/Admin/recordCollections';
const store = useRecordCollectionsStore();

import FieldForm from './Field/Form.vue';

import axios from 'axios';
import { ref, watch } from 'vue';

import { vAutoAnimate } from '@formkit/auto-animate';
import { FormKitMessages } from '@formkit/vue';
import { reset } from '@formkit/core';

const initialValues = {
  name: '',
  fields: [
    {
      type: 'text',
    },
  ],
};
const form = ref(null);
const closeButton = ref(null);
const values = ref(initialValues);

const emit = defineEmits(['update:collections']);

watch(
  () => store.selected,
  () => {
    values.value = store.selected ? store.selected : initialValues;
  },
);

function handleSubmit() {
  const method = store.selected ? 'put' : 'post';
  const url = store.selected
    ? `/api/internal/record_collections/${store.selected['id']}`
    : '/api/internal/record_collections';

  axios({
    method: method,
    url: url,
    data: values.value,
  })
    .then((response) => {
      if (
        undefined !== response &&
        (response.status === 201 || response.status === 200)
      ) {
        closeButton.value?.click();
        if (response.status === 201) {
          reset('record-collection-form');
          emit('update:collections');
        }

        // click on refresh button
        document.getElementById('refresh-collection')?.click();
      }
    })
    .catch((error) => {
      console.error('Error saving record collection:', error);
    });
}
</script>

<template>
  <FormKit
    type="form"
    ref="form"
    id="record-collection-form"
    v-model="values"
    :actions="false"
    @submit="handleSubmit"
    class="d-flex flex-column flex-grow-1"
    :config="{ validationVisibility: 'submit' }"
  >
    <FormKitMessages :node="form?.node" />
    <FormKit
      type="text"
      name="name"
      id="name"
      validation="required|length:1,60"
      :label="$t('Name')"
      outer-class="mb-3"
      input-class="form-control"
    />
    <div v-auto-animate>
      <FormKit
        type="list"
        name="fields"
        dynamic
        #default="{ items, node, value }"
      >
        <FormKit
          type="group"
          v-for="(item, index) in items"
          :key="item"
          :index="index"
        >
          <FieldForm :node="node" :value="value" :index="index" />
        </FormKit>

        <button
          type="button"
          @click="() => node.input(value.concat({}))"
          class="btn btn-secondary btn-sm w-100 mt-3"
        >
          <i class="fa fa-plus"></i>
          {{ $t('New field') }}
        </button>
      </FormKit>
    </div>

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
        {{ store.selected ? $t('Update') : $t('Create') }}
      </FormKit>
    </div>
  </FormKit>
</template>
