<script setup>
import {onMounted, ref, watch} from 'vue';
import ButtonPlus from "@/Components/MyButton/ButtonPlus.vue";
import ButtonOk from "@/Components/MyButton/ButtonOk.vue";
import ButtonClose from "@/Components/MyButton/ButtonClose.vue";

const model = defineModel(
    {required: true,}
);
const props = defineProps({
    title: String,
    value: String,
    type: String,
    inputmode: String,
    placeholder: String,
    brands: Array,
    models: Array,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
})

const input = ref(null);
const message = ref('')
const select_is = ref(true)
let button_open_is = true
let options
let inputAddModel_is = ref(false);
let inputAddBrand_is = ref(false);

let inputAdd = ref(false);

if (props.title === 'brand') {
    options = props.brands;
}
if (props.title === 'model') {
    if (props.models) {
        options = props.models;
    } else {
        select_is.value = false;
        button_open_is = false;
    }

}
if (props.title === 'color_id') {
    options = props.colors;
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

watch(model, (value) => {
    if (props.title === 'brand') {
        console.log(123)
        button_open_is = true;
        if (model.length > 0) {
            console.log(model);
        }
    }
})


function inputAddOpen(title) {
    console.log(message.value, title);
    inputAdd.value = true;
    // if (title === 'brand') {
    //     inputAddBrand_is.value = true;
    //     inputAdd.value = true;
    // }
    // if (title === 'model') {
    //     inputAddModel_is.value = true;
    //     inputAdd.value = true;
    // }

}

function inputAddClose() {
    inputAdd.value = false;

}

function inputAddSave() {
    inputAdd.value = false;

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
           :inputmode
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
            :class="{'hidden' : inputAdd, 'pointer-events-none' : !select_is}"
            v-model="model"
            :id="title">

        <option v-for="option in options" :value="option.id">{{ option.title }}</option>


        <!--        <option v-if="option.title">{{ option.title }}</option>-->

    </select>
    <input v-if="title === 'brand'|| title === 'model'"
           :class="{'!block' : inputAdd}"
           class="w-full hidden rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
           v-model="message"
           :id="title"
    />
    <div v-if="title === 'brand' || title === 'model'"
         class="absolute flex right-1 top-[3px]"
         :class="{'invisible' : !inputAdd}">
        <ButtonClose
            @click="inputAddClose(title)"
            :value="value"
            class="w-9"
            type="button"
        />
        <ButtonOk
            @click="inputAddSave(title)"
            class="w-9"
            type="button"
        />
    </div>
    <ButtonPlus
        v-if="title === 'brand' || title === 'model'"
        @click="inputAddOpen(title)"
        :value="value"
        class="absolute right-9 top-[3px] w-9"
        :class="{'invisible' : inputAdd, 'pointer-events-none' : !button_open_is}"
        type="button"
    />
</template>
