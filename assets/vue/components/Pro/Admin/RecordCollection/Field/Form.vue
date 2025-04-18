<script setup>
import { computed } from 'vue';

const props = defineProps({
  node: {
    type: Object,
    required: true,
  },
  index: {
    type: Number,
    required: true,
  },
  value: {
    type: Array,
    required: true,
  },
});

const isNew = computed(() => {
  return (
    props.value[props.index] && props.value[props.index]['@id'] === undefined
  );
});
</script>

<template>
  <div class="card card-body p-0">
    <div class="input-group">
      <FormKit
        type="text"
        name="name"
        id="name"
        validation="required|length:1,60"
        :label="false"
        :placeholder="$t('Field name')"
        :disabled="!isNew"
        outer-class="flex-grow-1"
        input-class="form-control form-control-sm border-0"
      />
      <button
        class="h-auto input-group-text d-flex align-items-center border-0"
        type="button"
        data-bs-toggle="collapse"
        :data-bs-target="'#collapse' + index"
        aria-expanded="false"
        :aria-controls="'collapse' + index"
      >
        <i class="fa fa-gear"></i>
      </button>
    </div>

    <div class="collapse p-1" :class="{ show: isNew }" :id="'collapse' + index">
      <FormKit
        type="select"
        name="type"
        id="type"
        validation="required"
        :label="false"
        :placeholder="$t('Type')"
        :disabled="!isNew"
        outer-class="mb-3"
        input-class="form-control form-control-sm"
        :options="[
          { value: 'plainText', label: $t('Plain Text') },
          { value: 'richEditor', label: $t('Rich Editor') },
          { value: 'number', label: $t('Number') },
          // { value: 'boolean', label: $t('Boolean') },
          { value: 'date', label: $t('Date') },
          { value: 'datetime', label: $t('Datetime') },
          { value: 'email', label: $t('Email') },
          { value: 'url', label: $t('URL') },
          // { value: 'select', label: $t('Select') },
          // { value: 'file', label: $t('File') },
          // { value: 'relation', label: $t('Relation') },
          // { value: 'json', label: $t('JSON') },
        ]"
      />

      <!-- TODO : parameters by type -->

      <div class="d-flex m-1">
        <div class="d-flex">
          <FormKit
            type="checkbox"
            name="hidden"
            id="hidden"
            wrapper-class="form-check form-switch ps-1"
            :label="$t('Hidden')"
            label-class="ms-2 form-check-label"
            input-class="form-check-input"
          />

          <FormKit
            type="checkbox"
            name="nonempty"
            id="nonempty"
            wrapper-class="form-check form-switch ps-1"
            :label="$t('Nonempty')"
            label-class="ms-2 form-check-label"
            input-class="form-check-input"
          />

          <FormKit
            type="checkbox"
            name="presentable"
            id="presentable"
            wrapper-class="form-check form-switch ps-1"
            :label="$t('Presentable')"
            label-class="ms-2 form-check-label"
            input-class="form-check-input"
          />
        </div>

        <div class="ms-auto btn-group">
          <button
            class="btn btn-link"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            ...
          </button>
          <ul class="dropdown-menu">
            <li>
              <button
                class="dropdown-item text-danger"
                type="button"
                @click="() => node.input(value.filter((_, i) => i !== index))"
              >
                <i class="fa fa-trash text-danger"></i>
                {{ $t('Remove') }}
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
