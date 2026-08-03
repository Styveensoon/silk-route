<template>
  <AppLayout>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Usuarios</h1>

    <table class="w-full text-sm border border-gray-200 rounded-md overflow-hidden">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-3">Nombre</th>
          <th class="p-3">Correo</th>
          <th class="p-3">Rol</th>
          <th class="p-3">Activo</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in localUsers" :key="user.id" class="border-t border-gray-200">
          <td class="p-3">{{ user.name }}</td>
          <td class="p-3">{{ user.email }}</td>
          <td class="p-3">
            <select v-model="user.role" class="border-gray-300 rounded-md text-sm">
              <option value="admin">admin</option>
              <option value="freelancer">freelancer</option>
              <option value="cliente">cliente</option>
            </select>
          </td>
          <td class="p-3">
            <input type="checkbox" v-model="user.is_active" />
          </td>
          <td class="p-3">
            <button @click="save(user)" class="text-indigo-600 hover:underline text-sm">Guardar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  users: { type: Array, default: () => [] },
});

const localUsers = reactive(props.users.map(u => ({ ...u })));

function save(user) {
  router.put(`/admin/users/${user.id}`, {
    role: user.role,
    is_active: user.is_active,
  });
}
</script>
