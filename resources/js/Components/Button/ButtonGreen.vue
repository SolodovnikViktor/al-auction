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
        class="text-white w-full bg-gradient-to-r from-green-400 via-green-500 to-green-600 font-semibold uppercase tracking-widest hover:bg-gradient-to-br focus:outline-none focus:ring-2 focus:ring-green-300 rounded-md p-2 text-xs text-center"
    >
        <slot/>
    </button>
</template>
