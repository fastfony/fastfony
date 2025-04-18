<script setup>
import { useRecordCollectionsStore } from '../../../../stores/Pro/Admin/recordCollections';
const store = useRecordCollectionsStore();

import Drawer from './Record/Drawer.vue';
import Actions from './Record/Actions.vue';
import { ref, watch } from 'vue';
import axios from 'axios';
import {
  ModuleRegistry,
  ClientSideRowModelModule,
  TextFilterModule,
} from 'ag-grid-community';

ModuleRegistry.registerModules([ClientSideRowModelModule, TextFilterModule]);
import { AgGridVue } from 'ag-grid-vue3';
import Uuid from './Record/Uuid.vue';
import { reset } from '@formkit/core';

const columns = ref([]);
const rows = ref([]);
const newRecord = {
  collection: null,
  fields: {},
};

watch(
  () => store.selected,
  () => {
    if (store.selected) {
      getRecords();
    }
    buildColumns(store.selected);
  },
);

watch(
  () => store.records,
  () => {
    buildRows(store.records);
  },
);

function getRecords() {
  // Fetch records from the API
  axios
    .get(`/api/internal/record_collections/${store.selected.id}/records`)
    .then((response) => {
      store.records = response.data['records'];
    })
    .catch((error) => {
      console.error('Error fetching records:', error);
    });
}

function buildColumns() {
  if (store.selected) {
    columns.value = store.selected.fields
      .filter((field) => !field.hidden)
      .map((field) => {
        return {
          field: field.name,
          filter: true,
        };
      });

    columns.value.unshift({
      headerName: 'Uuid',
      field: 'id',
      cellRenderer: Uuid,
      minWidth: 320,
      flex: 2,
    });

    columns.value.push({
      colId: 'actions',
      headerName: 'Actions',
      sortable: false,
      field: '@id',
      cellRenderer: Actions,
      minWidth: 120,
      flex: 1,
    });
  }
}

function buildRows(records) {
  rows.value = records.map((record) => {
    const row = {};
    Object.entries(record.fields).forEach(([key, value]) => {
      row[key] = value;
    });

    row.id = record['id'];
    row['@id'] = record['@id'];

    return row;
  });
}
</script>

<template>
  <main class="flex-grow-1 py-3 px-4">
    <div class="d-flex flex-column py-2">
      <div class="d-flex w-100 mb-4">
        <h2 class="h4">
          {{ $t('record_collections.title') }}
          <span v-if="store.selected">
            {{ store.selected.name }}
            <small class="fs-6 text-muted"> ({{ store.selected.slug }}) </small>
          </span>
        </h2>
        <button
          v-if="store.selected"
          class="btn btn-link ms-3"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#recordCollectionDrawer"
          aria-controls="recordCollectionDrawer"
        >
          <i class="fas fa-gear"></i>
          <span class="sr-only">{{ $t('Edit collection') }}</span>
        </button>
        <button
          v-if="store.selected"
          class="btn btn-link ms-3"
          type="button"
          id="refresh-collection"
          @click="
            buildColumns();
            getRecords();
          "
        >
          <i class="fas fa-refresh"></i>
          <span class="sr-only">{{ $t('Refresh collection') }}</span>
        </button>
        <button
          v-if="store.selected"
          type="button"
          @click="
            store.record = newRecord;
            reset('record-form');
          "
          class="btn btn-primary ms-auto"
          data-bs-toggle="offcanvas"
          data-bs-target="#recordDrawer"
          aria-controls="recordDrawer"
        >
          <i class="fas fa-plus"></i>
          {{ $t('New record') }}
        </button>
      </div>

      <ag-grid-vue
        :rowData="rows"
        :columnDefs="columns"
        style="width: 100%; overflow-x: auto"
        :domLayout="'autoHeight'"
        :pagination="true"
      >
      </ag-grid-vue>

      <Drawer @update:records="getRecords()" />
    </div>
  </main>
</template>
