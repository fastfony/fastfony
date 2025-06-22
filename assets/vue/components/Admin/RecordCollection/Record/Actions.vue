<script setup>
import { useRecordCollectionsStore } from '../../../../stores/Admin/recordCollections';
import { onMounted, ref } from 'vue';
import axios from 'axios';
const store = useRecordCollectionsStore();
const props = defineProps({});

const publishedValue = ref(false);

onMounted(() => {
  publishedValue.value = store.records.find(
    (record) => record['@id'] === props.params.value,
  ).published;
});

function togglePublished() {
  // we need to find the record in the store
  const record = store.records.find(
    (record) => record['@id'] === props.params.value,
  );
  if (record) {
    record.published = !record.published;
    axios
      .patch(
        `/api/internal/records/${record['id']}`,
        { published: record.published },
        {
          headers: {
            'Content-Type': 'application/merge-patch+json',
          },
        },
      )
      .catch((error) => {
        console.error('Error updating published status:', error);
      });
  }
}

function select(iri) {
  // we need to find the record in the store
  store.record = store.records.find((record) => record['@id'] === iri);
}
</script>

<template>
  <div class="d-flex flex-row align-items-center">
    <div
      class="form-switch mx-auto d-flex justify-content-center align-items-center"
    >
      <input
        class="form-check-input"
        type="checkbox"
        role="switch"
        id="flexSwitchCheckDefault"
        @change="togglePublished()"
        v-model="publishedValue"
      />
    </div>

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
