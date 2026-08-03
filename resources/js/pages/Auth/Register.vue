<template>
  <GuestLayout>
    <Head title="Registro" />

    <h1 class="text-xl font-extrabold text-clay-text text-center mb-5">Crear cuenta</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="clay-label">Nombre</label>
        <input v-model="form.name" type="text" class="clay-input" required autofocus />
        <p v-if="form.errors.name" class="text-sm text-rose-500 mt-1">{{ form.errors.name }}</p>
      </div>

      <div>
        <label class="clay-label">Correo</label>
        <input v-model="form.email" type="email" class="clay-input" required />
        <p v-if="form.errors.email" class="text-sm text-rose-500 mt-1">{{ form.errors.email }}</p>
      </div>

      <div>
        <label class="clay-label">Quiero registrarme como</label>
        <select v-model="form.role" class="clay-input">
          <option value="cliente">Cliente (busco servicios)</option>
          <option value="freelancer">Freelancer (ofrezco servicios)</option>
        </select>
        <p v-if="form.errors.role" class="text-sm text-rose-500 mt-1">{{ form.errors.role }}</p>
      </div>

      <div>
        <label class="clay-label">Contrasena</label>
        <input v-model="form.password" type="password" class="clay-input" required />
        <p v-if="form.errors.password" class="text-sm text-rose-500 mt-1">{{ form.errors.password }}</p>
      </div>

      <div>
        <label class="clay-label">Confirmar contrasena</label>
        <input v-model="form.password_confirmation" type="password" class="clay-input" required />
      </div>

      <div class="flex items-center justify-between pt-2">
        <Link href="/login" class="text-sm text-clay-muted hover:text-clay-text">
          Ya tienes cuenta?
        </Link>
        <button type="submit" :disabled="form.processing" class="clay-btn-primary">
          Registrarme
        </button>
      </div>
    </form>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from '../../Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'cliente',
});

function submit() {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
}
</script>
