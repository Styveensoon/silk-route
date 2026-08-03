<template>
  <AppLayout>
    <h1 class="text-2xl font-extrabold text-clay-text mb-4">Servicios disponibles</h1>

    <div v-if="categories.length" class="flex flex-wrap gap-3 mb-6">
      <Link href="/services" :class="!activeCategory ? 'clay-chip-active' : 'clay-chip'">
        Todas
      </Link>
      <Link
        v-for="category in categories"
        :key="category.id"
        :href="`/services?category=${category.id}`"
        :class="activeCategory === category.id ? 'clay-chip-active' : 'clay-chip'"
      >
        {{ category.name }}
      </Link>
    </div>

    <div v-if="services.length === 0" class="clay-surface p-8 text-center text-clay-muted">
      Aun no hay servicios publicados{{ activeCategory ? ' en esta categoria' : '' }}.
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
      <div v-for="service in services" :key="service.id">
        <ServiceCard :service="service" />
        <div v-if="canManage(service)" class="mt-2 flex gap-3 text-sm px-1">
          <Link :href="`/services/${service.id}/edit`" class="text-clay-primary font-semibold hover:underline">
            Editar
          </Link>
          <button @click="remove(service)" class="text-rose-500 font-semibold hover:underline">
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import ServiceCard from '../../Components/ServiceCard.vue';
import { Link, router, usePage } from '@inertiajs/vue3';

defineProps({
  services: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  activeCategory: { type: Number, default: null },
});

const page = usePage();

function canManage(service) {
  const user = page.props.auth?.user;
  return !!user && (user.id === service.user?.id || user.role === 'admin');
}

function remove(service) {
  if (confirm('Eliminar este servicio?')) {
    router.delete(`/services/${service.id}`);
  }
}
</script>
