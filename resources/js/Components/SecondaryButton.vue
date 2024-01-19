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
        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-sm text-gray-700"
    >
        <slot />
    </button>
</template>
