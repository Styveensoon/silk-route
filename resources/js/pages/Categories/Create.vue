<template>
  <AppLayout>
    <h1 class="text-2xl font-extrabold text-clay-text mb-6">Nueva categoria</h1>

    <form @submit.prevent="submit" class="clay-surface p-6 space-y-5 max-w-md">
      <div>
        <label class="clay-label">Nombre</label>
        <input v-model="form.name" type="text" class="clay-input" />
        <p v-if="form.errors.name" class="text-sm text-rose-500 mt-1">{{ form.errors.name }}</p>
      </div>

      <button type="submit" :disabled="form.processing" class="clay-btn-primary w-full">
        Crear
      </button>
    </form>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({ name: '' });

function submit() {
  form.post('/categories');
}
</script>
