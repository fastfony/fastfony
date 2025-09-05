<script setup>
import { useRecordCollectionsStore } from '../../../../stores/Admin/recordCollections';
const store = useRecordCollectionsStore();

import Form from './Form.vue';
import axios from 'axios';
import { ref } from 'vue';

const closeModalButton = ref(null);

const emit = defineEmits(['update:records']);

function remove() {
  axios
    .delete('/api/internal/records/' + store.record['id'])
    .then((response) => {
      closeModalButton.value?.click();
      emit('update:records');
    });
}
</script>

<template>
  <div
    class="offcanvas offcanvas-end"
    style="width: 600px"
    data-bs-scroll="true"
    tabindex="-1"
    id="recordDrawer"
    aria-labelledby="recordDrawerLabel"
  >
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="recordDrawerLabel">
        {{ store.record ? $t('Edit record') : $t('New record') }}
      </h5>

      <div class="ms-auto btn-group">
        <button
          class="btn btn-link"
          type="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          ...
        </button>
        <ul class="dropdown-menu">
          <li>
            <button
              class="dropdown-item"
              type="button"
              data-bs-dismiss="offcanvas"
              :aria-label="$t('Close')"
            >
              <i class="fa fa-close"></i>
              {{ $t('Close') }}
            </button>
          </li>
        </ul>
      </div>
    </div>
    <div class="offcanvas-body">
      <Form @update:records="$emit('update:records')" />
    </div>
  </div>

  <div
    class="modal fade"
    id="removeRecordModal"
    tabindex="-1"
    aria-labelledby="removeRecordModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          {{ $t('Do you really want to delete this record?') }}
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            ref="closeModalButton"
          >
            {{ $t('No') }}
          </button>
          <button type="button" class="btn btn-danger" @click="remove()">
            {{ $t('Yes') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
