<script setup>
import {ref} from "vue";

defineProps({title:{type:String},
})

</script>

<template>
    <h2
        class="text-xl font-semibold leading-tight text-gray-800">
         {{title}}
    </h2>
</template>

<style scoped>

</style>
