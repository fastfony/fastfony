<script setup>
import { useRecordCollectionsStore } from '../../../../../stores/Pro/Admin/recordCollections';
const store = useRecordCollectionsStore();

import axios from 'axios';

function select(iri) {
  // we need to find the record in the store
  store.record = store.records.find((record) => record['@id'] === iri);
}
</script>

<template>
  <div>
    <button
      type="button"
      class="btn btn-link action-edit action-label"
      data-bs-toggle="offcanvas"
      data-bs-target="#recordDrawer"
      aria-controls="recordDrawer"
      @click="select(params.value)"
    >
      <i class="fa fa-pen"></i>
      {{ $t('Edit') }}
    </button>
    <button
      type="button"
      class="btn btn-link action-delete text-danger action-label"
      data-bs-toggle="modal"
      data-bs-target="#removeRecordModal"
      @click="select(params.value)"
    >
      <i class="fa fa-trash"></i>
      {{ $t('Remove') }}
    </button>
  </div>
</template>

<!-- params.value -->
