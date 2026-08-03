<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="clay-btn-secondary text-sm normal-case"
    >
        <slot />
    </button>
</template>
