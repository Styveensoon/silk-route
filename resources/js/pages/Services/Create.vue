<template>
  <AppLayout>
    <h1 class="text-2xl font-extrabold text-clay-text mb-6">Publicar un servicio</h1>

    <form @submit.prevent="submit" class="clay-surface p-6 space-y-5 max-w-lg">
      <div>
        <label class="clay-label">Titulo</label>
        <input v-model="form.title" type="text" class="clay-input" />
        <p v-if="form.errors.title" class="text-sm text-rose-500 mt-1">{{ form.errors.title }}</p>
      </div>

      <div>
        <label class="clay-label">Descripcion</label>
        <textarea v-model="form.description" class="clay-input" rows="4"></textarea>
        <p v-if="form.errors.description" class="text-sm text-rose-500 mt-1">{{ form.errors.description }}</p>
      </div>

      <div>
        <label class="clay-label">Categoria</label>
        <select v-model="form.category_id" class="clay-input">
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
        <p v-if="form.errors.category_id" class="text-sm text-rose-500 mt-1">{{ form.errors.category_id }}</p>
      </div>

      <div>
        <label class="clay-label">Precio (MXN)</label>
        <input v-model="form.price" type="number" class="clay-input" />
        <p v-if="form.errors.price" class="text-sm text-rose-500 mt-1">{{ form.errors.price }}</p>
      </div>

      <div>
        <label class="clay-label">Punto de encuentro</label>
        <input v-model="form.meeting_address" type="text" class="clay-input"
          placeholder="Ej. Zocalo de Puebla, Puebla" />
        <p v-if="form.errors.meeting_address" class="text-sm text-rose-500 mt-1">{{ form.errors.meeting_address }}</p>
        <p class="text-xs text-clay-muted mt-1.5">Se usara para ubicar el punto de encuentro en un mapa.</p>
      </div>

      <button type="submit" :disabled="form.processing" class="clay-btn-primary w-full">
        Publicar servicio
      </button>
    </form>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
  categories: { type: Array, default: () => [] },
});

const form = useForm({
  title: '',
  description: '',
  category_id: '',
  price: '',
  meeting_address: '',
});

function submit() {
  form.post('/services');
}
</script>
