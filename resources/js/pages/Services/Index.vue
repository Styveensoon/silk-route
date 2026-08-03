<template>
  <AppLayout>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Servicios disponibles</h1>

    <div v-if="services.length === 0" class="text-gray-500">
      Aun no hay servicios publicados.
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
      <div v-for="service in services" :key="service.id">
        <ServiceCard :service="service" />
        <div v-if="canManage(service)" class="mt-2 flex gap-3 text-sm">
          <Link :href="`/services/${service.id}/edit`" class="text-indigo-600 hover:underline">Editar</Link>
          <button @click="remove(service)" class="text-red-600 hover:underline">Eliminar</button>
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