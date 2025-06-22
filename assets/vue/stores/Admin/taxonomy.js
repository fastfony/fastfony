import { defineStore } from 'pinia';

export const useTaxonomyStore = defineStore('taxonomy', {
  state: () => ({
    nodes: [],
    selectedNode: null,
    selectedKey: null,
    selectedParent: null,
    apiRoute: '/api/internal/taxonomies',
  }),
  actions: {
    setSelectedNode(node) {
      this.selectedNode = node;
      this.selectedKey = node.key;
    },
  },
});
