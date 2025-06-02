<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import FormInput from "@/Components/Main/Admin/Form/FormInput.vue";
import InputError from "@/Components/InputError.vue";
import FormBrandModel from "@/Components/Main/Admin/Form/FormBrandModel.vue";

const props = defineProps({
    form: Object,
    brands: Array,
    fuels: Array,
    wheels: Array,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
})

const formInputs = [
    {title: "vin", value: 'VIN', type: "text"},
    {title: "year_release", value: 'Год выпуска', type: "number"},
    {title: "color_id", value: 'Цвет кузова', type: "select"},
    {title: "mileage", value: 'Пробег', type: "number", placeholder: 'км'},
    {title: "fuel_id", value: 'Топливо', type: "select"},
    {title: "wheel_id", value: 'Руль', type: "select"},
    {title: "drive_id", value: 'Привод', type: "select"},
    {title: "body_type_id", value: 'Кузов', type: "select"},
    {title: "transmission_id", value: 'Коробка', type: "select"},
    {title: "engine_capacity", value: 'Объём двигателя', type: "number", inputMod: "decimal"},
    {title: "horsepower", value: 'Мощность', type: "number"},
    {title: "price", value: 'Цена', type: "number"},
    {title: "description", value: 'Описание', type: "textarea"},
];

</script>

<template>
    <FormBrandModel
        :form="form"
        :brands="brands"
    />
    <template v-for="(input, index) in formInputs" :key=index>
        <div class="p-1 sm:col-span-3 lg:col-span-2 text-gray-900">
            <div class="flex justify-between relative">
                <InputLabel class="content-center" :for="input.title" :value="input.value"/>
                <div class="flex w-3/4">
                    <FormInput
                        :title="input.title"
                        :type="input.type"
                        :value="input.value"
                        :inputMod="input.inputMod"
                        :placeholder="input.placeholder"
                        :fuels
                        :wheels
                        :colors
                        :drives
                        :bodyTypes
                        :transmissions
                        v-model="form[input.title]"
                    />
                </div>
            </div>
            <InputError class="mt-2" :message="form.errors[input.title]"/>
        </div>
    </template>
</template>

