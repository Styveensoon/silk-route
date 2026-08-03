<template>
  <div class="border border-gray-200 rounded-lg p-4 flex flex-col h-full bg-white">
    <div class="flex items-start justify-between gap-2">
      <h3 class="font-semibold text-gray-900">{{ service.title }}</h3>
      <span class="text-indigo-600 font-semibold whitespace-nowrap">${{ price }}</span>
    </div>

    <span
      v-if="service.category"
      class="mt-1 inline-block w-fit text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full"
    >
      {{ service.category.name }}
    </span>

    <p class="text-sm text-gray-600 mt-2 flex-1">{{ service.description }}</p>

    <div class="mt-3 text-xs text-gray-500 space-y-1">
      <p v-if="service.user">Por {{ service.user.name }}</p>
      <p v-if="service.meeting_address">📍 {{ service.meeting_address }}</p>
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
</script>
