<template>
  <div class="clay-card p-5 flex flex-col h-full">
    <div class="flex items-start justify-between gap-2">
      <h3 class="font-bold text-clay-text leading-snug">{{ service.title }}</h3>
      <span class="text-clay-primary font-extrabold whitespace-nowrap">${{ price }}</span>
    </div>

    <span v-if="service.category" class="clay-badge mt-2 w-fit" :class="categoryClasses">
      {{ service.category.name }}
    </span>

    <p class="text-sm text-clay-muted mt-3 flex-1 line-clamp-3">{{ service.description }}</p>

    <div class="mt-4 pt-3 border-t border-white/60 text-xs text-clay-muted space-y-1">
      <p v-if="service.user" class="flex items-center gap-1">🙋 {{ service.user.name }}</p>
      <p v-if="service.meeting_address" class="flex items-center gap-1">📍 {{ service.meeting_address }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  service: { type: Object, required: true },
});

const price = computed(() =>
  Number(props.service.price).toLocaleString('es-MX', { minimumFractionDigits: 0 })
);

const palette = [
  'bg-clay-peach/40 text-orange-700',
  'bg-clay-mint/40 text-emerald-700',
  'bg-clay-sky/40 text-sky-700',
  'bg-clay-yolk/40 text-amber-700',
  'bg-clay-primary/20 text-clay-primary-dark',
];

const categoryClasses = computed(() => {
  const id = props.service.category?.id ?? 0;
  return palette[id % palette.length];
});
</script>
