<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const parameters = ref([]);

onMounted(() => {
    axios.get('/api/parameters').then(response => {
        // On tri les paramètres par leur catégorie
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
        axios.patch(
            parameter['@id'],
            { value: parameter.value },
            { headers: { 'Content-Type': 'application/merge-patch+json' } }
        );
    }
};
</script>

<template>
    <div class="grid xl:grid-cols-3 gap-4">
        <fieldset v-for="(parameters, category) in parameters" :key="category"
                  class="fieldset bg-base-200 border border-base-300 p-5 rounded-box" >
            <legend class="fieldset-legend">{{ $t(category) }}</legend>

            <div v-for="parameter in parameters" :key="parameters.id" class="form-control flex flex-col gap-1 my-2">
                <label class="fieldset-label text-sm font-semibold mb-2">{{ $t(parameter.label) ?? $t(parameter.key) }}</label>
                <select v-if="'bool' === parameter.type"
                        class="select select-bordered w-full validator"
                        v-model="parameter.value"
                        @change="patchParameter(parameter, $event)">
                    <option value="1">{{ $t('Yes') }}</option>
                    <option value="0">{{ $t('No') }}</option>
                </select>
                <input v-else :type="parameter.type"
                       class="input w-full validator"
                       v-model="parameter.value"
                       required
                       @change="patchParameter(parameter, $event)"
                />
                <p class="text-info ms-3" v-if="parameter.help">{{ $t(parameter.help) }}</p>
            </div>
        </fieldset>
    </div>
</template>
