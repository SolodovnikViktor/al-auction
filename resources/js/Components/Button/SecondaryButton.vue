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
        class="inline-flex items-center rounded-md border border-gray-200 bg-white p-2  text-xs font-semibold uppercase tracking-widest text-gray-400 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 hover:text-gray-800 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
    >
        <slot/>
    </button>
</template>
