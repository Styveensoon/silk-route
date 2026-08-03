<template>
  <AppLayout>
    <section class="text-center py-12">
      <h1 class="text-4xl font-bold text-gray-900">SilkRoad 🐫</h1>
      <p class="mt-3 text-lg text-gray-600 max-w-xl mx-auto">
        El marketplace de servicios freelance entre estudiantes universitarios.
        Encuentra ayuda o ofrece tus habilidades a la comunidad.
      </p>

      <div class="mt-6 flex justify-center gap-3 flex-wrap">
        <Link href="/services" class="bg-indigo-600 text-white px-5 py-2.5 rounded-md font-medium">
          Ver servicios
        </Link>

        <Link
          v-if="!user"
          href="/register"
          class="border border-gray-300 text-gray-700 px-5 py-2.5 rounded-md font-medium"
        >
          Crear cuenta
        </Link>
        <Link
          v-else-if="user.role === 'freelancer'"
          href="/services/create"
          class="border border-gray-300 text-gray-700 px-5 py-2.5 rounded-md font-medium"
        >
          Publicar un servicio
        </Link>
      </div>
    </section>

    <section class="mt-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900">Servicios recientes</h2>
        <Link href="/services" class="text-sm text-indigo-600 hover:underline">Ver todos</Link>
      </div>

      <div v-if="services.length === 0" class="text-gray-500">
        Aun no hay servicios publicados.
        <Link href="/register" class="text-indigo-600 hover:underline">Registrate</Link>
        y se el primero en ofrecer uno.
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
        <ServiceCard v-for="service in services" :key="service.id" :service="service" />
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import ServiceCard from '../Components/ServiceCard.vue';

defineProps({
  services: { type: Array, default: () => [] },
});

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
</script>
