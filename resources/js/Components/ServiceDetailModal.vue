<template>
  <Modal :show="show" max-width="lg" @close="$emit('close')">
    <div class="p-6">
      <div class="flex items-start justify-between gap-4 mb-4">
        <h2 class="text-xl font-extrabold text-clay-text pr-4">
          {{ service?.title ?? 'Detalle del servicio' }}
        </h2>
        <button
          @click="$emit('close')"
          class="clay-btn-secondary !p-0 !w-9 !h-9 shrink-0"
          aria-label="Cerrar"
        >
          ✕
        </button>
      </div>

      <div v-if="loading" class="py-14 text-center text-clay-muted">
        Cargando detalles...
      </div>

      <div v-else-if="service" class="space-y-5">
        <div class="flex items-center gap-3 flex-wrap">
          <span class="text-clay-primary font-extrabold text-lg">${{ price }}</span>
          <span v-if="service.category" class="clay-badge" :class="categoryClasses">
            {{ service.category.name }}
          </span>
        </div>

        <p class="text-clay-text/90 whitespace-pre-line leading-relaxed">{{ service.description }}</p>

        <div class="text-sm text-clay-muted space-y-1">
          <p v-if="service.user">🙋 Publicado por {{ service.user.name }}</p>
          <p v-if="service.meeting_point?.address">📍 {{ service.meeting_point.address }}</p>
        </div>

        <div>
          <h3 class="text-sm font-semibold text-clay-text mb-2">Punto de encuentro</h3>
          <MapPoint
            :lat="service.meeting_point?.lat"
            :lng="service.meeting_point?.lng"
            :label="service.title"
          />
        </div>
      </div>

      <div v-else class="py-14 text-center text-rose-500">
        No se pudo cargar este servicio.
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import axios from 'axios';
import Modal from './Modal.vue';
import MapPoint from './MapPoint.vue';

const props = defineProps({
  show: { type: Boolean, default: false },
  serviceId: { type: [Number, String], default: null },
});

defineEmits(['close']);

const service = ref(null);
const loading = ref(false);

watch(
  () => [props.show, props.serviceId],
  async ([show, id]) => {
    if (!show || !id) {
      return;
    }

    loading.value = true;
    service.value = null;

    try {
      const { data } = await axios.get(`/api/services/${id}`);
      service.value = data.data;
    } catch (error) {
      service.value = null;
    } finally {
      loading.value = false;
    }
  },
);

const price = computed(() =>
  service.value ? Number(service.value.price).toLocaleString('es-MX', { minimumFractionDigits: 0 }) : ''
);

const palette = [
  'bg-clay-peach/40 text-orange-700',
  'bg-clay-mint/40 text-emerald-700',
  'bg-clay-sky/40 text-sky-700',
  'bg-clay-yolk/40 text-amber-700',
  'bg-clay-primary/20 text-clay-primary-dark',
];

const categoryClasses = computed(() => {
  const id = service.value?.category?.id ?? 0;
  return palette[id % palette.length];
});
</script>
