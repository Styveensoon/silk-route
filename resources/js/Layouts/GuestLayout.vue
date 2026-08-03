<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-clay-bg pt-10 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/" class="text-4xl">🐫</Link>
        </div>

        <div
            class="clay-surface mt-6 w-full px-6 py-6 sm:max-w-md"
        >
            <slot />
        </div>
    </div>
</template>
