<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
      <h1 class="text-2xl font-extrabold text-clay-text">Categorias</h1>
      <Link href="/categories/create" class="clay-btn-primary">
        + Nueva categoria
      </Link>
    </div>

    <div class="clay-surface overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-clay-muted">
            <th class="p-4 font-semibold">Nombre</th>
            <th class="p-4 font-semibold">Servicios</th>
            <th class="p-4"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in categories" :key="category.id" class="border-t border-white/60">
            <td class="p-4 font-medium text-clay-text">{{ category.name }}</td>
            <td class="p-4">
              <span class="clay-badge bg-clay-primary/15 text-clay-primary-dark">
                {{ category.services_count }}
              </span>
            </td>
            <td class="p-4 text-right space-x-4">
              <Link :href="`/categories/${category.id}/edit`" class="text-clay-primary font-semibold hover:underline">
                Editar
              </Link>
              <button @click="remove(category)" class="text-rose-500 font-semibold hover:underline">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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
