<template>
  <AppLayout>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar servicio</h1>

    <form @submit.prevent="submit" class="space-y-4 max-w-lg">
      <div>
        <label class="block text-sm font-medium text-gray-700">Titulo</label>
        <input v-model="form.title" type="text" class="mt-1 w-full border-gray-300 rounded-md" />
        <p v-if="form.errors.title" class="text-sm text-red-600 mt-1">{{ form.errors.title }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Descripcion</label>
        <textarea v-model="form.description" class="mt-1 w-full border-gray-300 rounded-md" rows="4"></textarea>
        <p v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Categoria</label>
        <select v-model="form.category_id" class="mt-1 w-full border-gray-300 rounded-md">
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
        <p v-if="form.errors.category_id" class="text-sm text-red-600 mt-1">{{ form.errors.category_id }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Precio (MXN)</label>
        <input v-model="form.price" type="number" class="mt-1 w-full border-gray-300 rounded-md" />
        <p v-if="form.errors.price" class="text-sm text-red-600 mt-1">{{ form.errors.price }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Punto de encuentro</label>
        <input v-model="form.meeting_address" type="text" class="mt-1 w-full border-gray-300 rounded-md"
          placeholder="Ej. Zocalo de Puebla, Puebla" />
        <p v-if="form.errors.meeting_address" class="text-sm text-red-600 mt-1">{{ form.errors.meeting_address }}</p>
      </div>

      <button type="submit" :disabled="form.processing"
        class="bg-indigo-600 text-white px-4 py-2 rounded-md disabled:opacity-50">
        Guardar cambios
      </button>
    </form>

    <div class="max-w-lg mt-8">
      <h2 class="text-sm font-medium text-gray-700 mb-2">Ubicacion actual guardada</h2>
      <MapPoint :lat="service.meeting_lat" :lng="service.meeting_lng" :label="service.title" />
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import MapPoint from '../../Components/MapPoint.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  service: { type: Object, required: true },
  categories: { type: Array, default: () => [] },
});

const form = useForm({
  title: props.service.title,
  description: props.service.description,
  category_id: props.service.category_id,
  price: props.service.price,
  meeting_address: props.service.meeting_address,
});

function submit() {
  form.put(`/services/${props.service.id}`);
}
</script>