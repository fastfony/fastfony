<script setup>
import { useTaxonomyStore } from '../../../../stores/Pro/Admin/taxonomy';
const store = useTaxonomyStore();
import { useTaxonomy } from '../../../../composables/Pro/Admin/useTaxonomy';
const { getTreeNodeData } = useTaxonomy();

const emit = defineEmits(['reloadNodes']);
import { onMounted, ref } from 'vue';
import axios from 'axios';

const key = ref();
const createAnotherImmediately = ref(false);
const submitInProgress = ref(false);
const createForm = ref();
const closeButton = ref();

onMounted(() => {
  const modal = document.getElementById('createTaxonomyModal');
  modal.addEventListener('shown.bs.modal', () => {
    document.getElementById('key').focus();
  });
});

function submit() {
  // We submit the form and check validity
  if (!createForm.value.checkValidity()) {
    createForm.value.reportValidity();
    return;
  }

  submitInProgress.value = true;

  axios
    .post(store.apiRoute, {
      key: document.getElementById('key').value,
      parent: store.selectedNode
        ? store.apiRoute + '/' + store.selectedNode.key
        : null,
    })
    .then((response) => {
      // We reset the button and input to their initial states
      submitInProgress.value = false;
      key.value = '';

      const newNode = getTreeNodeData(response.data);
      if (createAnotherImmediately.value) {
        if (store.selectedNode) {
          // If selected, we add the new child to the parent that will remain selected
          store.selectedNode.children.push(newNode);
          store.selectedNode.children.sort((a, b) =>
            a.label.localeCompare(b.label),
          );
        }
        // We trigger the event that reloads the other data
        emit('reloadNodes', store.selectedNode);
      } else {
        // We close the modal
        closeButton.value.click();
        // We trigger the event that reloads the data and select the new element
        emit('reloadNodes', newNode);
      }
    });
}
</script>

<template>
  <div
    class="modal fade"
    id="createTaxonomyModal"
    tabindex="-1"
    aria-labelledby="createTaxonomyModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="createTaxonomyModalLabel">
            <span v-if="store.selectedNode">
              {{ $t('Create new taxonomy in') }} {{ store.selectedNode.label }}
            </span>
            <span v-else>{{ $t('Create new taxonomy in root') }}</span>
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
          <form
            ref="createForm"
            action="javascript:void(0)"
            @submit.stop="submit()"
          >
            <div class="form-inline">
              <label for="key" class="form-label">{{ $t('Key') }}</label>
              <input
                type="text"
                name="key"
                id="key"
                v-model="key"
                class="form-control"
                required
              />
            </div>
          </form>
          <div class="my-2 p-0 form-check">
            <input
              type="checkbox"
              id="create-another"
              name="create-another"
              class="form-check-input"
              v-model="createAnotherImmediately"
            />
            <label for="create-another" class="form-check-label">
              {{ $t('Create immediately an other') }}
            </label>
          </div>
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
            :disabled="submitInProgress"
            type="submit"
            @click="submit()"
            class="btn btn-primary"
          >
            <i
              :class="
                submitInProgress
                  ? 'spinner-border spinner-border-sm'
                  : 'fa fa-check'
              "
            ></i>
            {{ $t('Create') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
