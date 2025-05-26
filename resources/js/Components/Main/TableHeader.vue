<script setup>
const props = defineProps({
    tableHeaders: Array,
    orderingDesc: Boolean,
    orderingAsc: Boolean,
    indexHeader: Number,
})
defineEmits(['filterOn'])
</script>

<template>
    <th
        v-for="tableHeader in tableHeaders" :key="tableHeader.id"
        scope="col"
        class="text-start font-medium uppercase">
        <div
            @click="$emit('filterOn', tableHeader.id,tableHeader.value)"
            class="py-1 px-3 mb-0.5 lg:mb-1.5 cursor-pointer select-none inline-flex items-center border border-transparent text-sm text-gray-500 rounded-md hover:border-gray-200">
            {{ tableHeader.title }}
            <svg class="size-3.5 ms-1 -me-0.5 text-gray-400"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path :class="[orderingDesc && tableHeader.id === indexHeader ? 'text-blue-600' : '']"
                      d="m7 15 5 5 5-5">
                </path>
                <path :class="[orderingAsc && tableHeader.id === indexHeader ? 'text-blue-600' : '']"
                      d="m7 9 5-5 5 5"></path>
            </svg>
        </div>
    </th>
</template>

