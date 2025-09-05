<script setup>
import { useTaxonomyStore } from '../../../stores/Admin/taxonomy';
const store = useTaxonomyStore();

const emit = defineEmits(['reloadNodes']);
import axios from 'axios';
import { ref } from 'vue';

const closeButton = ref();

function remove(node) {
  axios.delete(store.apiRoute + '/' + node.key).then(() => {
    // We close the modal
    closeButton.value.click();
    if (node.parent) {
      // We delete in the parent children, the child we just deleted via API
      node.parent.children = node.parent.children.filter(
        (child) => child.key !== node.key,
      );
    }
    // We trigger the event that reloads the data
    emit('reloadNodes', node.parent);
  });
}
</script>

<template>
  <div
    class="modal fade"
    id="deleteModalTaxonomy"
    tabindex="-1"
    aria-labelledby="deleteModalTaxonomyLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="deleteModalTaxonomyLabel">
            {{ $t('Remove a taxonomy') }}
          </h1>
          <button
            type="button"
            class="btn-close"
            ref="closeButton"
            data-bs-dismiss="modal"
            :aria-label="$t('Close')"
          ></button>
        </div>
        <div class="modal-body">
          {{
            $t(
              'Are you sure you want to delete this item? This action is irreversible',
            )
          }}
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            {{ $t('Close') }}
          </button>
          <button
            type="button"
            class="btn btn-danger"
            @click="remove(store.selectedNode)"
          >
            {{ $t('Remove') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
