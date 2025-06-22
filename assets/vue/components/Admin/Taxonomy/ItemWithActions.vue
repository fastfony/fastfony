<script setup>
import { useTaxonomyStore } from '../../../stores/Admin/taxonomy';

const store = useTaxonomyStore();

defineProps({
  slotProps: Object,
});
</script>

<template>
  <div class="d-flex align-items-center w-100 justify-content-between">
    <span class="btn btn-link px-0">
      {{ slotProps.node.label }}
    </span>

    <div class="dropdown">
      <button
        class="btn btn-link"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        ...
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <button
            class="dropdown-item text-danger"
            data-bs-toggle="modal"
            data-bs-target="#deleteModalTaxonomy"
            @click="store.setSelectedNode(slotProps.node)"
          >
            {{ $t('Remove') }}
          </button>
        </li>
      </ul>
    </div>
  </div>
</template>
