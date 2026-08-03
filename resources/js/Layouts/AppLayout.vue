<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between flex-wrap gap-2">
        <Link href="/" class="font-bold text-lg text-gray-900">SilkRoad 🐫</Link>
        <div class="flex items-center gap-4 text-sm">
          <Link href="/services" class="text-gray-600 hover:text-gray-900">Servicios</Link>

          <template v-if="user">
            <Link v-if="user.role === 'freelancer'" href="/services/create" class="text-gray-600 hover:text-gray-900">
              Publicar
            </Link>
            <Link v-if="user.role === 'admin'" href="/categories" class="text-gray-600 hover:text-gray-900">
              Categorias
            </Link>
            <Link v-if="user.role === 'admin'" href="/admin/users" class="text-gray-600 hover:text-gray-900">
              Usuarios
            </Link>
            <span class="text-gray-300">|</span>
            <span class="text-gray-500">{{ user.name }} ({{ user.role }})</span>
            <Link href="/logout" method="post" as="button" class="text-gray-600 hover:text-gray-900">
              Salir
            </Link>
          </template>

          <template v-else>
            <Link href="/login" class="text-gray-600 hover:text-gray-900">Entrar</Link>
            <Link href="/register" class="text-gray-600 hover:text-gray-900">Registrarme</Link>
          </template>
        </div>
      </div>
    </nav>
    <main class="max-w-5xl mx-auto px-4 py-8">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
</script>