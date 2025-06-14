<script setup>
import {ref} from 'vue';

const model = defineModel(
    {required: true,}
);
const props = defineProps({
    title: String,
    value: String,
    type: String,
    inputMod: String,
    placeholder: String,
    fuels: Array,
    wheels: Array,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
})

const input = ref(null);
let options

if (props.title === 'color_id') {
    options = props.colors;
}
if (props.title === 'fuel_id') {
    options = props.fuels;
}
if (props.title === 'wheel_id') {
    options = props.wheels;
}
if (props.title === 'drive_id') {
    options = props.drives;
}
if (props.title === 'body_type_id') {
    options = props.bodyTypes;
}
if (props.title === 'transmission_id') {
    options = props.transmissions;
}

// onMounted(() => {
//     if (props.title === 'title') {
//         input.value.setAttribute('autofocus', 'true');
//         if (input.value.hasAttribute('autofocus')) {
//             input.value.focus();
//         }
//     }
// });
// defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <input v-if="type === 'text'"
           class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
           v-model="model"
           :type
           :id="title"
           :autocomplete="title"
           ref="input"
           :placeholder
    />
    <input v-if="type === 'number'"
           class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
           v-model="model"
           :type
           :inputMod
           min='0'
           step="0.01"
           :id="title"
           :autocomplete="title"
           ref="input"
    />
    <textarea v-if="type === 'textarea'"
              class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
              v-model="model"
              rows="5"
              :id="title"
              ref="textarea"
    />

    <select v-if="type === 'select'"
            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
            v-model="model"
            :id="title">
        <option v-for="option in options" :value="option.id">{{ option.title }}</option>
    </select>
</template>
