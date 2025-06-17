<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <button>
        <svg viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink">
            <title>Добавить {{ value }}</title>
            <rect fill="#d7effa" height="104" rx="17.33" width="104" x="12" y="12"/>
            <path fill="#38b1e7"
                  d="M83.36,69H44.64C41.53,69,39,66.76,39,64s2.53-5,5.64-5H83.36C86.47,59,89,61.24,89,64S86.47,69,83.36,69Z"/>
            <path fill="#38b1e7"
                  d="M64,89c-2.76,0-5-2.53-5-5.64V44.64C59,41.53,61.24,39,64,39s5,2.53,5,5.64V83.36C69,86.47,66.76,89,64,89Z"/>
        </svg>
    </button>
</template>
