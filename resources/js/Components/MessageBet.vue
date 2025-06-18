<script setup>
import {onMounted, onUnmounted, ref} from 'vue';
import SvgClose from "@/Components/Main/SVG/SvgClose.vue";

const props = defineProps({
    message: String,
});
const open = ref(false);

if (props.message) {
    open.value = true;
}

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};
onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));
</script>

<template>
    <div class="relative">
        <!-- Full Screen Dropdown Overlay -->
        <div
            v-show="open"
            class="fixed bg-gray-200 opacity-75 inset-0 overflow-y-auto overflow-x-hidden top-0 right-0 left-0 z-40 justify-center items-center w-full h-full"
            @click="open = false">
        </div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="absolute z-50 w-full sm:w-1/2 lg:w-1/3 top-52 left-1/2 -translate-x-1/2 rounded-md shadow-lg">

                <div
                    class="rounded-md text-center ring-1 bg-white ring-red-500 ">
                    <div class="flex items-center p-2 px-4 justify-between  border-b rounded-t border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-700">
                            Предупреждение
                        </h3>
                        <SvgClose @click="open = false"/>
                    </div>
                    <div class="p-4 md:p-5 space-y-3">
                        <p class="text-base leading-relaxed text-gray-900">
                            {{ message }}
                        </p>
                        <p class="text-base leading-relaxed text-gray-600">
                            Александр тел. +7(999)-999-99-99
                        </p>
                        <p class="text-base leading-relaxed text-gray-600">
                            WhatsApp тел. +7(777)-666-33-22
                        </p>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
