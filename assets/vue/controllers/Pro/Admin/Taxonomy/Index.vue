<script setup>
import { ref, onMounted, computed } from 'vue';
import { useTaxonomy } from '../../../../composables/Pro/Admin/useTaxonomy';
const { getTreeFromEntities } = useTaxonomy();
import { useTaxonomyStore } from '../../../../stores/Pro/Admin/taxonomy';
const store = useTaxonomyStore();

import Tree from 'primevue/tree';
import CreateModal from '../../../../components/Pro/Admin/Taxonomy/CreateModal.vue';
import ItemWithActions from '../../../../components/Pro/Admin/Taxonomy/ItemWithActions.vue';
import DeleteModal from '../../../../components/Pro/Admin/Taxonomy/DeleteModal.vue';
import axios from 'axios';

const expandedKeys = ref({});
const selectedNodePath = computed(() => {
  return store.selectedNode ? getPath(store.selectedNode) : '';
});

const expandedChildrenKeys = computed(() => {
  const keys = {};
  if (store.selectedNode && store.selectedNode.children) {
    store.selectedNode.children.forEach((child) => {
      keys[child.key] = true;
    });
  }

  return keys;
});

function getPath(node) {
  let path = node.label;
  let parent = node.parent;

  while (parent) {
    path = parent.label + ' > ' + path;
    parent = parent.parent;
  }

  return path;
}

onMounted(() => {
  loadNodes();
  replaceEasyAdminSearchByTreeFilter();
});

function loadNodes(nodeToSelect = null) {
  axios.get(store.apiRoute).then((response) => {
    // Composable useTaxonomy and function getTreeFromEntities is used to convert the data for the PrimeVue Tree component
    getTreeFromEntities(response.data['entities']).then((nodes) => {
      // We update the tree nodes
      nodes.sort((a, b) => a.label.localeCompare(b.label));
      store.nodes = nodes;
      if (nodeToSelect) {
        store.setSelectedNode(nodeToSelect);
      }
    });
  });
}

function replaceEasyAdminSearchByTreeFilter() {
  // We move the filter element of the Tree component in the DOM,
  // to put it in place of the search field of EasyAdmin
  const searchForm = document.querySelector('form.form-action-search');
  searchForm.appendChild(
    document.querySelector('div.p-iconfield.p-tree-filter'),
  );
  document.querySelector('form.form-action-search div.form-group').remove();
}

const collapseAll = () => {
  expandedKeys.value = {};
};

const expandAll = () => {
  for (let node of store.nodes) {
    expandNode(node);
  }

  expandedKeys.value = { ...expandedKeys.value };
};

const expandNode = (node) => {
  if (node.children && node.children.length) {
    expandedKeys.value[node.key] = true;

    for (let child of node.children) {
      expandNode(child);
    }
  }
};

const onNodeSelect = (node) => {
  store.selectedNode = node;
};
const onNodeUnselect = () => {
  store.selectedNode = null;
};
</script>

<template>
  <div class="container-fluid border-top">
    <div class="d-flex align-self-stretch">
      <nav
        class="py-3 px-4 sidebar"
        style="z-index: auto !important; max-inline-size: none"
      >
        <div class="d-flex gap-2 mb-4">
          <button class="btn btn-sm btn-secondary" @click="expandAll">
            <i class="fas fa-plus-circle me-1" style="font-size: 0.75rem"></i>
            {{ $t('Expand All') }}
          </button>
          <button class="btn btn-sm btn-secondary" @click="collapseAll">
            <i class="fas fa-minus-circle me-1" style="font-size: 0.75rem"></i>
            {{ $t('Collapse All') }}
          </button>
        </div>

        <Tree
          v-model:selectionKeys="store.selectedKey"
          v-model:expandedKeys="expandedKeys"
          :value="store.nodes"
          selectionMode="single"
          :filter="true"
          filterMode="lenient"
          :filterPlaceholder="$t('Search')"
          @nodeSelect="onNodeSelect"
          @nodeUnselect="onNodeUnselect"
          class="p-0"
        >
          <template #default="slotProps">
            <ItemWithActions :slotProps="slotProps" @reload-nodes="loadNodes" />
          </template>
        </Tree>
      </nav>

      <main class="flex-grow-1 py-3 px-4">
        <div class="d-flex flex-column py-2">
          <div class="d-flex w-100 mb-4">
            <h2 class="h4">
              {{ $t('taxonomies.title') }}
              <span v-if="store.selectedNode"> > {{ selectedNodePath }} </span>
            </h2>
            <button
              type="button"
              class="btn btn-primary ms-auto"
              data-bs-toggle="modal"
              data-bs-target="#createTaxonomyModal"
            >
              <i class="fas fa-plus"></i>
              {{ $t('taxonomies.button.new') }}
            </button>
          </div>

          <div class="gap-x-6 gap-y-8">
            <div v-if="store.selectedNode && store.selectedNode.children">
              <Tree
                v-model:selectionKeys="store.selectedKey"
                v-model:expandedKeys="expandedChildrenKeys"
                :value="store.selectedNode.children"
                selectionMode="single"
                class="w-full"
              >
                <template #default="slotProps">
                  <ItemWithActions :slotProps="slotProps" />
                </template>
              </Tree>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <CreateModal @reload-nodes="loadNodes" />
  <DeleteModal @reload-nodes="loadNodes" />
</template>
