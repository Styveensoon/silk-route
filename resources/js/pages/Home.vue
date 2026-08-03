<template>
  <AppLayout>
    <section class="clay-surface text-center py-14 px-6">
      <div class="text-6xl mb-2">🐫</div>
      <h1 class="text-4xl font-extrabold text-clay-text">SilkRoad</h1>
      <p class="mt-3 text-lg text-clay-muted max-w-xl mx-auto">
        El marketplace de servicios freelance entre estudiantes universitarios.
        Encuentra ayuda o ofrece tus habilidades a la comunidad.
      </p>

      <div class="mt-7 flex justify-center gap-3 flex-wrap">
        <Link href="/services" class="clay-btn-primary">
          🔎 Ver servicios
        </Link>

        <Link v-if="!user" href="/register" class="clay-btn-secondary">
          Crear cuenta
        </Link>
        <Link v-else-if="user.role === 'freelancer'" href="/services/create" class="clay-btn-secondary">
          ✨ Publicar un servicio
        </Link>
      </div>
    </section>

    <section v-if="categories.length" class="mt-10">
      <h2 class="text-lg font-bold text-clay-text mb-3">Explora por categoria</h2>
      <div class="flex flex-wrap gap-3">
        <Link
          v-for="category in categories"
          :key="category.id"
          :href="`/services?category=${category.id}`"
          class="clay-chip"
        >
          {{ categoryEmoji(category.name) }} {{ category.name }}
        </Link>
      </div>
    </section>

    <section class="mt-10">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-clay-text">Servicios recientes</h2>
        <Link href="/services" class="text-sm font-semibold text-clay-primary hover:underline">Ver todos →</Link>
      </div>

      <div v-if="services.length === 0" class="clay-surface p-8 text-center text-clay-muted">
        Aun no hay servicios publicados.
        <Link href="/register" class="text-clay-primary font-semibold hover:underline">Registrate</Link>
        y se el primero en ofrecer uno.
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
        <ServiceCard v-for="service in services" :key="service.id" :service="service" @open="selectedServiceId = $event.id" />
      </div>
    </section>

    <ServiceDetailModal
      :show="!!selectedServiceId"
      :service-id="selectedServiceId"
      @close="selectedServiceId = null"
    />
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import ServiceCard from '../Components/ServiceCard.vue';
import ServiceDetailModal from '../Components/ServiceDetailModal.vue';

defineProps({
  services: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
});

const selectedServiceId = ref(null);

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);

const emojiMap = {
  tutorias: '📚',
  programacion: '💻',
  'diseno grafico': '🎨',
  'redaccion y traduccion': '✍️',
  'fotografia y video': '📷',
  musica: '🎵',
};

function categoryEmoji(name) {
  return emojiMap[name.toLowerCase()] ?? '🏷️';
}
</script>
