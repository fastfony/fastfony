<template>
  <table class="table table-sm">
    <thead>
      <tr>
        <th scope="col">{{ $t('table.group-role-matrix') }}</th>
        <th v-for="group in groups" :key="group.id" scope="col">
          {{ group.name }}
        </th>
      </tr>
    </thead>
    <tbody>
      <template v-for="(roles, category) in rolesByCategory">
        <tr v-if="'null' !== category" class="table-light">
          <th :colspan="groups.length + 1" scope="colgroup">{{ category }}</th>
        </tr>
        <tr v-for="role in roles">
          <td>{{ role.description }}</td>
          <td v-for="group in groups" :key="group.id">
            <input
              type="checkbox"
              class="form-check-input"
              :checked="role.groups.includes(group.id)"
              @change="toggle(role, group, $event)"
            />
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</template>

<script setup>
import axios from 'axios';

import { useToast } from 'vue-toastification';
const toast = useToast();

import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const props = defineProps({
  groups: Array,
  roles: Array,
});

const rolesByCategory = props.roles.reduce((acc, role) => {
  if (!acc[role.category]) {
    acc[role.category] = [];
  }

  acc[role.category].push(role);

  return acc;
}, {});

function toggle(role, group, $event) {
  axios
    .post('/admin/group_role/toggle', { group_id: group.id, role_id: role.id })
    .then((response) => {
      if ($event.target.checked !== response.data['checked']) {
        $event.target.checked = response.data['checked'];
      }

      toast.success(t('toast.group-role-matrix.toggle.success'), {
        timeout: 2000,
      });
    });
}
</script>
