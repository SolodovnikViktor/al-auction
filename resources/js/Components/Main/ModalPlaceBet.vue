<script setup>

import {onMounted, onUnmounted, reactive, ref, watch, watchEffect} from "vue";
import SvgClose from "@/Components/Main/SVG/SvgClose.vue";
import ButtonGreen from "@/Components/Button/ButtonGreen.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import {router} from "@inertiajs/vue3";

const emit = defineEmits(['toggleModalPlaceBet'])
const props = defineProps({
    lot: Object,
    openModalPlaceBet: Boolean,
});

let formBet = reactive({
    up_bet: '',
    down_bet: '',
})

let arbitrary = ref('')
let choose = ref('')
let open = ref()
let chooseArr = []
let priceDown
let disabledButton = true

function numberFilter(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

const pushArr = (priceDown) => {
    let price = priceDown
    for (let i = 0; i < 10; i++) {
        price = price + 5000
        let priceString = numberFilter(price);
        chooseArr.push({value: price, title: priceString.toString() + ' ₽'});
    }
}

if (props.lot.bets.length === 0) {
    priceDown = props.lot.price
    pushArr(priceDown)
} else {
    priceDown = props.lot.bets.at(-1)
    pushArr(priceDown)
}

watch((choose), () => {
    disabledButton = choose.value < priceDown;
    formBet.up_bet = choose.value;
});
watch((arbitrary), () => {
    disabledButton = arbitrary.value === '';
    formBet.up_bet = arbitrary.value;
});

watchEffect(() => {
    open.value = props.openModalPlaceBet;

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

const storeBet = () => {
    toggleModalPlaceBet()
    formBet.down_bet = priceDown
    router.post(route('main-bets.store', props.lot.id), formBet)
}
</script>

<template>
    <div
        class="relative max-w-screen-xl mx-auto">
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
                    <form @submit.prevent="storeBet" class="p-4 md:p-5 space-y-3">
                        <p class="text-base leading-relaxed text-gray-600">
                            Ставка должна быть минимум на 5000&nbsp;₽ <span
                            class="text-nowrap">больше {{ numberFilter(priceDown) }} ₽.</span>
                        </p>

                        <input
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:opacity-25"
                            placeholder="Произвольная"
                            type="number"
                            id="choose"
                            v-model="choose"
                            :disabled="arbitrary !== ''"
                        />
                        <select
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:opacity-25"
                            v-model="arbitrary"
                            :disabled="choose !== ''"
                            id="arbitrary">
                            <option class="text-gray-500" value="">Выбрать</option>
                            <option v-for="choose in chooseArr" :value="choose.value">{{ choose.title }}</option>
                        </select>
                        <div class="flex items-center mt-6 space-x-8 rtl:space-x-reverse">
                            <SecondaryButton @click="toggleModalPlaceBet">
                                Закрыть
                            </SecondaryButton>
                            <ButtonGreen
                                :disabled="disabledButton"
                                :type="'submit'">
                                Отправить ставку
                            </ButtonGreen>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </div>
</template>
