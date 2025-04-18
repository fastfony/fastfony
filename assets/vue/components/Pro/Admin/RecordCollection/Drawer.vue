<script setup>
import { useRecordCollectionsStore } from '../../../../stores/Pro/Admin/recordCollections';
const store = useRecordCollectionsStore();

const emit = defineEmits(['update:collections']);

import axios from 'axios';
import Form from './Form.vue';
import { ref } from 'vue';

const closeButton = ref(null);
const closeModalButton = ref(null);

function confirmRemoveRecordCollection() {
  const url = '/api/internal/record_collections/' + store.selected['id'];
  axios
    .delete(url)
    .then(() => {
      store.selected = null;
      closeButton.value?.click();
      closeModalButton.value?.click();
      emit('update:collections');
    })
    .catch((error) => {
      console.error('Error deleting record collection:', error);
    });
}
</script>

<template>
  <div
    class="offcanvas offcanvas-end"
    data-bs-scroll="true"
    tabindex="-1"
    id="recordCollectionDrawer"
    aria-labelledby="recordCollectionDrawerLabel"
  >
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="recordCollectionDrawerLabel">
        {{ store.selected ? $t('Edit collection') : $t('New collection') }}
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
              class="dropdown-item text-danger"
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#removeRecordCollectionModal"
            >
              <i class="fa fa-trash text-danger"></i>
              {{ $t('Remove') }}
            </button>
          </li>
          <li><hr class="dropdown-divider" /></li>
          <li>
            <button
              class="dropdown-item"
              type="button"
              ref="closeButton"
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
      <Form @update:collections="$emit('update:collections')" />
    </div>
  </div>

  <div
    class="modal fade"
    id="removeRecordCollectionModal"
    tabindex="-1"
    aria-labelledby="removeRecordCollectionModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          {{
            $t('Do you really want to delete collection and all its records?')
          }}
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
          <button
            type="button"
            class="btn btn-danger"
            @click="confirmRemoveRecordCollection()"
          >
            {{ $t('Yes') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
