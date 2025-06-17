<script setup>

import {onMounted, onUnmounted, ref, watchEffect} from "vue";
import SvgClose from "@/Components/Main/SVG/SvgClose.vue";
import ButtonGreen from "@/Components/Button/ButtonGreen.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";

const emit = defineEmits(['toggleModalPlaceBet'])
const props = defineProps({
    post: Object,
    openModalPlaceBet: Boolean,
});

let open = ref();
watchEffect(() => {
    console.log("watch")
    open.value = props.openModalPlaceBet;
    console.log(open.value)
})

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        toggleModalPlaceBet();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const toggleModalPlaceBet = () => {
    open.value = false;
    emit('toggleModalPlaceBet', open.value);
}
</script>

<template>
    <div
        class="relative">
        <!-- Full Screen Dropdown Overlay -->
        <div
            class="fixed bg-gray-200 opacity-75 inset-0 overflow-y-auto overflow-x-hidden top-0 right-0 left-0 z-40 justify-center items-center w-full h-full"
            @click="toggleModalPlaceBet">
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
                    class="rounded-md text-center ring-1 bg-white ring-black ring-opacity-5">
                    <div class="flex items-center p-2 px-4 justify-between  border-b rounded-t border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-700">
                            Выберите ставку
                        </h3>
                        <SvgClose @click="toggleModalPlaceBet"/>
                    </div>
                    <div class="p-4 md:p-5 space-y-3">
                        <p class="text-base leading-relaxed text-gray-600">
                            Ставка должна быть минимум на 5000руб больше предыдущей или начальной цены.
                        </p>

                        <input
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Произвольная"
                            type="number"
                        />

                        <select
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"

                            id="color">
                            <option class="text-gray-500" value="">Ставка</option>
                            <option>12</option>
                            <option>12</option>
                            <option>12</option>
                            <option>12</option>
                        </select>

                        <div class="flex items-center mt-6 space-x-8 rtl:space-x-reverse">
                            <SecondaryButton @click="toggleModalPlaceBet">Закрыть</SecondaryButton>

                            <ButtonGreen>
                                Отправить ставку
                            </ButtonGreen>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
