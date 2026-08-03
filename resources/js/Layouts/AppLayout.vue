<template>
  <div class="min-h-screen bg-clay-bg">
    <div class="max-w-6xl mx-auto px-4 pt-5">
      <nav class="clay-surface px-5 py-3 flex items-center justify-between flex-wrap gap-3">
        <Link href="/" class="font-extrabold text-lg text-clay-text flex items-center gap-2">
          <span class="text-2xl">🐫</span> SilkRoad
        </Link>

        <div class="flex items-center gap-2 text-sm flex-wrap">
          <Link href="/services" class="clay-chip">Servicios</Link>

          <template v-if="user">
            <Link v-if="user.role === 'freelancer'" href="/services/create" class="clay-chip">
              ✨ Publicar
            </Link>
            <Link v-if="user.role === 'admin'" href="/categories" class="clay-chip">
              🏷️ Categorias
            </Link>
            <Link v-if="user.role === 'admin'" href="/admin/users" class="clay-chip">
              👤 Usuarios
            </Link>

            <span class="hidden sm:inline-flex clay-badge bg-clay-bg text-clay-muted shadow-clay-inset ml-1">
              {{ user.name }} · {{ user.role }}
            </span>

            <Link href="/logout" method="post" as="button" class="clay-btn-secondary !px-4 !py-1.5 text-sm">
              Salir
            </Link>
          </template>

          <template v-else>
            <Link href="/login" class="clay-chip">Entrar</Link>
            <Link href="/register" class="clay-btn-primary !px-4 !py-1.5 text-sm">Registrarme</Link>
          </template>
        </div>
      </nav>
    </div>

    <main class="max-w-6xl mx-auto px-4 py-8">
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
