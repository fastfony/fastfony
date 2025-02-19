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

const patchParameter = (parameter) => {
    axios.patch(
        parameter['@id'],
        { value: parameter.value },
        { headers: { 'Content-Type': 'application/merge-patch+json' } }
    );
};
</script>

<template>
    <div class="grid xl:grid-cols-3 gap-4">
        <fieldset v-for="(parameters, category) in parameters" :key="category"
                  class="fieldset bg-base-200 border border-base-300 p-4 rounded-box" >
            <legend class="fieldset-legend">{{ category }}</legend>

            <div v-for="parameter in parameters" :key="parameters.id" class="form-control flex flex-col gap-1">
                <label class="fieldset-label">{{ parameter.label ?? parameter.key }}</label>
                <input type="text"
                       class="input w-full validator"
                       v-model="parameter.value"
                       @change="patchParameter(parameter)"
                />
                <p class="fieldset-label" v-if="parameter.help">{{ parameter.help }}</p>
            </div>
        </fieldset>
    </div>
</template>
