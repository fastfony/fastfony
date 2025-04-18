<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import Drawer from '../../../../components/Pro/Admin/RecordCollection/Drawer.vue';
import Datatable from '../../../../components/Pro/Admin/RecordCollection/Datatable.vue';

import { useRecordCollectionsStore } from '../../../../stores/Pro/Admin/recordCollections';
import { reset } from '@formkit/core';
const store = useRecordCollectionsStore();

const collections = ref();

onMounted(() => {
  getCollections();
});

function getCollections() {
  // Fetch collections from the API
  axios
    .get('/api/internal/record_collections')
    .then((response) => {
      collections.value = response.data['member'];
      // on regarde dans l'URL s'il y a une ancre
      const hash = window.location.hash;
      if (hash) {
        const slug = hash.replace('#', '');
        const collection = collections.value.find(
          (collection) => collection['slug'] === slug,
        );
        if (collection) {
          store.selected = collection;
          return;
        }
      }

      store.selected = collections.value[0];
    })
    .catch((error) => {
      console.error('Error fetching collections:', error);
    });
}

function reloadWithNewHash(newHash) {
  // Change the URL hash
  window.history.pushState(null, '', '#' + newHash);
  // We force the reload of the page because formkit schema is not reactive
  // and we need to reload the form when we change the collection
  window.location.reload();
}
</script>

<template>
  <div class="container-fluid border-top">
    <div class="d-flex align-self-stretch">
      <nav class="py-3 px-4 sidebar" style="z-index: auto !important">
        <ul class="nav flex-column">
          <li class="nav-item my-1" v-for="collection in collections">
            <a class="btn btn-link" @click="reloadWithNewHash(collection.slug)">
              <i class="fas fa-folder"></i> {{ collection.name }}
            </a>
          </li>
          <li class="nav-item py-4">
            <button
              class="btn btn-secondary"
              type="button"
              @click="
                store.selected = null;
                reset('record-collection-form');
              "
              data-bs-toggle="offcanvas"
              data-bs-target="#recordCollectionDrawer"
              aria-controls="recordCollectionDrawer"
            >
              <i class="fas fa-plus"></i>
              {{ $t('New collection') }}
            </button>
          </li>
        </ul>
      </nav>

      <Datatable />
    </div>
  </div>

  <Drawer @update:collections="getCollections()" />
</template>
