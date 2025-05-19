<script setup>
import {ref, watch} from "vue";
import {useForm} from "@inertiajs/vue3";
import axios from "axios";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import ButtonCyan from "@/Components/Button/ButtonCyan.vue";

const emit = defineEmits(['checkbox'])

const props = defineProps({
    postsData: Object,
    user: Object,
    brands: Array,
    fuels: Array,
    wheels: Array,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
});

const form = useForm({
    brand_id: '',
    model_id: '',
    price_ot: '',
    price_do: '',
    year_ot: '',
    year_do: '',
    mileage_ot: '',
    mileage_do: '',
    color_id: '',
    fuel_id: '',
    wheel_id: '',
    transmission_id: '',
    drive_id: '',
    body_type_id: '',
    engine_capacity: '',
    horsepower_ot: '',
    horsepower_do: '',
});

const models = ref()

let toggle = ref();
toggle.value = props.user.catalog_view;

function updateCatalogView() {
    emit('checkbox', toggle.value);
    axios.patch(`/update-catalog-view/${props.user.id}`, {catalog_view: toggle.value})
        .catch((error) => {
            console.log(error);
        });
    // router.patch(route('admin-post.updateCatalogView', user.value.id), {
    //     catalog_view: toggle.value
    // })
}

function getModel(value) {
    axios.post('/admin/post/crete/get-model', value)
        .then(response => {
            models.value = response.data;
            // console.log(models.value);
        })
        .catch(error => {
            console.log(error)
            console.log(error.message);
        });
}

watch(() => form.brand_id, (value) => {
    if (form.brand_id > 0) {
        getModel(value)
        console.log(form)
    }
})
</script>

<template>
    <div class="p-4 sm:px-5 mb-4 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
        <form :disabled="form.processing" @submit.prevent="form.get(route('admin-post.filter'),{
            preserveScroll: true,
                onError: error => {console.log(error)}})">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.brand_id"
                    id="brand">
                    <option value="">Бренд</option>
                    <option v-for="brand in brands" :value="brand.id">{{ brand.title }}</option>
                </select>
                <select
                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.model_id"
                    id="model">
                    <option value="">Модель</option>
                    <option v-for="model in models" :value="model.id">{{ model.title }}</option>
                </select>

                <div class="flex">
                    <input
                        class="w-full rounded-s-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Цена от"
                        v-model="form.price_ot"
                        type="number"
                    />
                    <input
                        class="w-full rounded-e-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="до"
                        v-model="form.price_do"
                        type="number"
                    />
                </div>
                <div class="flex">
                    <input
                        class="w-full rounded-s-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Год от"
                        v-model="form.year_ot"
                        type="number"
                    />
                    <input
                        class="w-full rounded-e-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="до"
                        v-model="form.year_do"
                        type="number"
                    />
                </div>
                <div class="flex">
                    <input
                        class="w-full rounded-s-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Пробег от"
                        v-model="form.mileage_ot"
                        type="number"
                    />
                    <input
                        class="w-full rounded-e-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="до"
                        v-model="form.mileage_do"
                        type="number"
                    />
                </div>

                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.color_id"
                    id="color">
                    <option value="">Цвет</option>
                    <option v-for="color in colors" :value="color.id">{{ color.title }}</option>
                </select>
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.fuel_id"
                    id="fuel">
                    <option value="">Топливо</option>
                    <option v-for="option in fuels" :value="option.id">{{ option.title }}</option>
                </select>
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.wheel_id"
                    id="wheel">
                    <option value="">Руль</option>
                    <option v-for="option in wheels" :value="option.id">{{ option.title }}</option>
                </select>
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.transmission_id"
                    id="transmission">
                    <option value="">Трансмиссия</option>
                    <option v-for="transmission in transmissions" :value="transmission.id">{{
                            transmission.title
                        }}
                    </option>
                </select>
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.drive_id"
                    id="drive">
                    <option value="">Привод</option>
                    <option v-for="drive in drives" :value="drive.id">{{ drive.title }}</option>
                </select>
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.body_type_id"
                    id="body_type">
                    <option value="">Кузов</option>
                    <option v-for="bodyType in bodyTypes" :value="bodyType.id">{{ bodyType.title }}</option>
                </select>
                <div class="flex">
                    <input
                        class="w-full rounded-s-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Лошадей от"
                        v-model="form.horsepower_ot"
                        type="number"
                    />
                    <input
                        class="w-full rounded-e-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="до"
                        v-model="form.horsepower_do"
                        type="number"
                    />
                </div>
            </div>
            <div class="flex justify-between mt-4">
                <div class="flex items-center">
                    <p v-if="toggle" class="mr-3">Фото</p>
                    <p v-else class="mr-3 font-medium">Фото</p>
                    <label class="relative mr-3 inline-flex items-center cursor-pointer">
                        <input
                            @change="updateCatalogView"
                            type="checkbox"
                            v-model="toggle"
                            :true-value="1"
                            :false-value="0"
                            class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all">
                        </div>
                    </label>
                    <p v-if="toggle" class="font-medium">Список</p>
                    <p v-else>Список</p>
                </div>
                <div class="flex gap-4">
                    <SecondaryButton>Очистить Х</SecondaryButton>
                    <ButtonCyan type="submit">Показать {{ postsData.length }}</ButtonCyan>
                </div>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
