<script setup>
import SvgOrdering from "@/Components/Main/SvgOrdering.vue";

const props = defineProps({
    tableHeaders: Array,
    formOrdering: Object,
    orderingDesc: String,
    orderingAsc: String,
    orderingValue: String,
})
defineEmits(['filterOn'])
</script>

<template>
    <th
        v-for="(tableHeader, i) in tableHeaders" :key="i"
        scope="col"
        class="text-start font-medium uppercase">
        <div v-if="!tableHeader.exception"
             @click="$emit('filterOn', tableHeader.value)"
             class="py-1 mb-1 px-3 lg:mb-1.5 cursor-pointer select-none inline-flex items-center border border-transparent text-sm text-gray-500 rounded-md transition hover:text-gray-800 hover:border-gray-200"
             :class="[tableHeader.value === formOrdering.ordering_value ? '!text-black' : '']"
        >
            {{ tableHeader.title }}
            <SvgOrdering
                :formOrdering
                :keyOrdering="tableHeader.value"
            />
        </div>
        <div v-else
             class="py-1 mb-1 px-3 lg:mb-1.5 select-none inline-flex items-center border border-transparent text-sm text-gray-500 rounded-md">
            {{ tableHeader.title }}
        </div>

    </th>
</template>

