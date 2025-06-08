<script setup>
import {ref} from "vue";

defineProps({
    formOrdering: Object,
    keyOrdering: String,
})

</script>

<template>
    <svg
        v-if="keyOrdering === formOrdering.ordering_value"
        class="size-3.5 ms-1 -me-0.5 text-blue-600"
        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path
            v-if="formOrdering.ordering_desc === 'true' && keyOrdering === formOrdering.ordering_value"
            d="m7 9 8 8 8-8">
        </path>
        <path
            v-if="formOrdering.ordering_asc === 'true' && keyOrdering === formOrdering.ordering_value"
            d="m7 15 8-8 8 8">
        </path>
    </svg>
    <svg
        v-else
        class="size-3.5 ms-1 -me-0.5 text-gray-400"
        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path
            d="m7 15 5 5 5-5">
        </path>
        <path
            d="m7 9 5-5 5 5">
        </path>
    </svg>
</template>
