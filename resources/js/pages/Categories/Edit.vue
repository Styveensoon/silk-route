<template>
  <AppLayout>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar categoria</h1>

    <form @submit.prevent="submit" class="space-y-4 max-w-md">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nombre</label>
        <input v-model="form.name" type="text" class="mt-1 w-full border-gray-300 rounded-md" />
        <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
      </div>

      <button type="submit" :disabled="form.processing"
        class="bg-indigo-600 text-white px-4 py-2 rounded-md disabled:opacity-50">
        Guardar cambios
      </button>
    </form>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  category: { type: Object, required: true },
});

const form = useForm({ name: props.category.name });

function submit() {
  form.put(`/categories/${props.category.id}`);
}
</script>