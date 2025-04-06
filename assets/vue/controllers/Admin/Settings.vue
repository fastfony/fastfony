<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

import { useToast } from 'vue-toastification';
const toast = useToast();

import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const parameters = ref([]);

onMounted(() => {
  axios.get('/api/internal/parameters').then((response) => {
    // We sort the parameters by their category
    parameters.value = response.data.member.reduce((data, parameter) => {
      if (!data[parameter.category]) {
        data[parameter.category] = [];
      }
      data[parameter.category].push(parameter);
      return data;
    }, {});
  });
});

const patchParameter = (parameter, event) => {
  if (event.target.checkValidity()) {
    axios
      .patch(
        '/api/internal/parameters/' + parameter['id'],
        { value: parameter.value },
        { headers: { 'Content-Type': 'application/merge-patch+json' } },
      )
      .then((response) => {
        toast.success(t('toast.parameter.updated'), {
          timeout: 2000,
        });
      });
  }
};
</script>

<template>
  <div class="row">
    <div
      class="col-12 col-xxl-3"
      v-for="(parameters, category) in parameters"
      :key="category"
    >
      <div class="card mb-4">
        <div class="card-header">
          {{ $t(category) }}
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-for="parameter in parameters">
            <div class="field-text form-group">
              <label class="form-control-label">{{
                $t(parameter.label) ?? $t(parameter.key)
              }}</label>
              <div class="form-widget">
                <select
                  v-if="'bool' === parameter.type"
                  v-model="parameter.value"
                  class="form-select"
                  @change="patchParameter(parameter, $event)"
                >
                  <option value="1">{{ $t('Yes') }}</option>
                  <option value="0">{{ $t('No') }}</option>
                </select>
                <input
                  v-else
                  v-model="parameter.value"
                  :type="parameter.type"
                  class="form-control"
                  @change="patchParameter(parameter, $event)"
                />
                <small
                  class="form-text form-help"
                  v-if="parameter.help"
                  v-html="$t(parameter.help)"
                ></small>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
