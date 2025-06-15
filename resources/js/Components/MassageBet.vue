<script setup>
import {computed, onMounted, onUnmounted, ref} from 'vue';

const props = defineProps({
    massage: String,
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },

});
const open = ref(false);
console.log(props.massage);
if (props.massage) {
    open.value = true;
    console.log(open.value);
}

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        48: 'w-48',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'ltr:origin-top-left rtl:origin-top-right start-0';
    } else if (props.align === 'right') {
        return 'ltr:origin-top-right rtl:origin-top-left end-0';
    } else {
        return 'origin-top';
    }
});


</script>

<template>
    <div class="relative">
        <!-- Full Screen Dropdown Overlay -->
        <div
            v-show="open"
            class="fixed bg-gray-200 opacity-75 inset-0 z-40"
            @click="open = false"
        ></div>

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
                class="absolute z-50 top-52 left-1/2 -translate-x-1/2 rounded-md shadow-lg">
                <div
                    class="rounded-md ring-1 sm:p-6 lg:p-12  bg-white ring-black ring-opacity-5">
                    <p>{{ massage }}</p>
                    <p class="text-center">тел 8 999 999 99 99</p>
                </div>
            </div>
        </Transition>
    </div>
</template>
