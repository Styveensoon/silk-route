<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Categorias</h1>
      <Link href="/categories/create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm">
        Nueva categoria
      </Link>
    </div>

    <table class="w-full text-sm border border-gray-200 rounded-md overflow-hidden">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-3">Nombre</th>
          <th class="p-3">Servicios</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="category in categories" :key="category.id" class="border-t border-gray-200">
          <td class="p-3">{{ category.name }}</td>
          <td class="p-3">{{ category.services_count }}</td>
          <td class="p-3 text-right space-x-3">
            <Link :href="`/categories/${category.id}/edit`" class="text-indigo-600 hover:underline">Editar</Link>
            <button @click="remove(category)" class="text-red-600 hover:underline">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
  categories: { type: Array, default: () => [] },
});

function remove(category) {
  if (confirm('Eliminar esta categoria?')) {
    router.delete(`/categories/${category.id}`);
  }
}
</script>

