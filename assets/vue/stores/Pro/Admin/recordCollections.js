import { defineStore } from 'pinia';

export const useRecordCollectionsStore = defineStore('recordCollections', {
  state: () => ({
    selected: null,
    records: [],
    record: {
      collection: null,
      fields: {},
    },
  }),
  getters: {},
  actions: {},
});
