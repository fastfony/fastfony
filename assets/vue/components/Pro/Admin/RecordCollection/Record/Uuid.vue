<script setup>
import { useClipboard } from '@vueuse/core';
const { copy, copied } = useClipboard();
</script>

<template>
  <div>
    <span class="badge text-bg-primary">
      {{ params.value }}
    </span>
    <button
      type="button"
      class="ms-2 btn btn-sm btn-link"
      :title="$t('Copy')"
      @click="copy(params.value)"
    >
      <span v-if="!copied"><i class="fa fa-copy"></i></span>
      <span v-else>Copied!</span>
    </button>
  </div>
</template>
