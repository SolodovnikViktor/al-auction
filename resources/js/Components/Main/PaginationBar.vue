<script setup>
import { Link } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";

const props = defineProps({
    links: Array,
});

const buttons = ref([]);
const button_prev_url = ref(null);
const button_next_url = ref(null);

const init = (limit) => {
    let links = props.links;
    let selected_index = null;

    // remove prev & next
    links.shift();
    links.pop();

    // select index
    links.map((link, i) => {
        if (link.active)selected_index = i;
    });

    button_prev_url.value = links[selected_index - 1]?.url;
    button_next_url.value = links[selected_index + 1]?.url;
    if (button_prev_url.value === undefined)button_prev_url.value=''
    if (button_next_url.value === undefined)button_next_url.value=''

    buttons.value = links;
};

watch(
    () => props.links,
    () => init(),
);

onMounted(() => init());
</script>

<template>
    <div v-if="buttons.length > 1">
        <div class="flex flex-wrap justify-center">
            <Link
                class="px-4 py-2 text-lg rounded-lg flex items-center"
                :class="
                    button_prev_url
                        ? 'text-slate-500 focus:text-cyan-500 hover:bg-slate-100 font-bold'
                        : 'text-slate-300 pointer-events-none'
                "
                :href="button_prev_url"
            >
                <svg class="w-3.5 h-3.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
            </Link>
            <template v-for="(btn, i) in buttons" v-bind:key="i">
                <Link
                    class="px-4 py-2 mx-0.5 text-bm font-bold rounded-lg hover:bg-slate-100 hover:text-black
                     focus:text-cyan-500"
                    :class="btn.active ? 'bg-cyan-100 text-black' : 'text-slate-500'"
                    :href="btn.url"
                >
                    {{ btn.label }}
                </Link>
            </template>
            <Link
                class="px-4 py-2 text-lg rounded-lg flex items-center"
                :class="
                    button_next_url
                        ? 'text-slate-500 focus:text-cyan-500 hover:bg-slate-100 font-bold'
                        : 'text-slate-300 pointer-events-none'
                "
                :href="button_next_url"
            >
                <span class="sr-only">Next</span>
                <svg class="w-3.5 h-3.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
            </Link>
        </div>
    </div>
</template>
