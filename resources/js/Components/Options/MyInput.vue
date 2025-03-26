<script setup>
import {onMounted, ref} from 'vue';

const model = defineModel(
    {required: true,}
);
const props = defineProps({
    id: String,
    type: String,
    inputmode: String,
    placeholder: String,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
})

const input = ref(null);
let options

if (props.id === 'color_id') {
    options = props.colors;
}
if (props.id === 'drive_id') {
    options = props.drives;
}
if (props.id === 'body_type_id') {
    options = props.bodyTypes;
}
if (props.id === 'transmission_id') {
    options = props.transmissions;
}


onMounted(() => {
    if (props.id === 'title') {
        input.value.setAttribute('autofocus', 'true');
        if (input.value.hasAttribute('autofocus')) {
            input.value.focus();
        }
    }
});
defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <div>
        <input v-if="type === 'text'"
               class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
               v-model="model"
               :type
               :id
               :autocomplete="id"
               ref="input"
               :placeholder
        />
        <input v-if="type === 'number'"
               class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
               v-model="model"
               :type
               :inputmode
               min='0'
               step="0.01"
               :id
               :autocomplete="id"
               ref="input"
        />
        <textarea v-if="type === 'textarea'"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                  v-model="model"
                  rows="5"
                  :id
                  ref="textarea"
        />
        <select v-else-if="type === 'select'"
                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                v-model="model"
                :id>
            <option v-for="option in options" :value="option.id">{{ option.title }}</option>
        </select>
    </div>
</template>
