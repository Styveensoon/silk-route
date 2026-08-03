<template>
  <AppLayout>
    <h1 class="text-2xl font-extrabold text-clay-text mb-6">Usuarios</h1>

    <div class="clay-surface overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-clay-muted">
            <th class="p-4 font-semibold">Nombre</th>
            <th class="p-4 font-semibold">Correo</th>
            <th class="p-4 font-semibold">Rol</th>
            <th class="p-4 font-semibold">Activo</th>
            <th class="p-4"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in localUsers" :key="user.id" class="border-t border-white/60">
            <td class="p-4 font-medium text-clay-text">{{ user.name }}</td>
            <td class="p-4 text-clay-muted">{{ user.email }}</td>
            <td class="p-4">
              <select v-model="user.role" class="clay-input !py-1.5 !w-auto">
                <option value="admin">admin</option>
                <option value="freelancer">freelancer</option>
                <option value="cliente">cliente</option>
              </select>
            </td>
            <td class="p-4">
              <input type="checkbox" v-model="user.is_active" class="w-5 h-5 rounded-md text-clay-primary focus:ring-clay-primary" />
            </td>
            <td class="p-4">
              <button @click="save(user)" class="clay-btn-secondary !px-4 !py-1.5 text-sm">Guardar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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
