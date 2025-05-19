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
        class="inline-flex items-center rounded-md  bg-gradient-to-r from-cyan-400 via-cyan-500 px-4 py-2 to-cyan-600 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition duration-150 ease-in-out hover:bg-gradient-to-br focus:outline-none focus:ring-2 focus:ring-cyan-300 disabled:opacity-25"
    >
        <slot/>
    </button>
</template>
