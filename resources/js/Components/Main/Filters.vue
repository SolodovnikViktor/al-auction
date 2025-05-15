<script setup>
import {ref, watch} from "vue";
import {useForm} from "@inertiajs/vue3";
import axios from "axios";

const emit = defineEmits(['checkbox'])

const props = defineProps({
    user: Object,
    brands: Array,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
});

const formFilter = useForm({
    brand_id: '',
    model_id: '',
    year_ot: '',
    year_do: '',
    color_id: '',
    mileage_ot: '',
    mileage_do: '',
    fuel: '',
    drive_id: '',
    body_type_id: '',
    transmission_id: '',
    engine_capacity: '',
    horsepower: '',
    price_ot: '',
    price_do: '',

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

function getModel(value, bool) {
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

watch(() => formFilter.brand_id, (value) => {
    if (formFilter.brand_id > 0) {
        getModel(value)
        console.log(formFilter)
    }
})
</script>

<template>
    <div class="py-2 px-4 sm:px-5 mb-4 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
        <form action="">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="formFilter.brand_id"
                    id="brand">
                    <option value="">Бренд</option>
                    <option v-for="brand in brands" :value="brand.id">{{ brand.title }}</option>
                </select>
                <select
                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="formFilter.model_id"
                    id="model">
                    <option value="">Модель</option>
                    <option v-for="model in models" :value="model.id">{{ model.title }}</option>
                </select>

                <div class="flex">
                    <input
                        class="w-full rounded-s-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Цена от"
                        v-model="formFilter.price_ot"
                        type="number"
                    />
                    <input
                        class="w-full rounded-e-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="до"
                        v-model="formFilter.price_do"
                        type="number"
                    />
                </div>
                <div class="flex">
                    <input
                        class="w-full rounded-s-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Год от"
                        v-model="formFilter.year_ot"
                        type="number"
                    />
                    <input
                        class="w-full rounded-e-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="до"
                        v-model="formFilter.year_do"
                        type="number"
                    />
                </div>

                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="formFilter.color_id"
                    id="color">
                    <option value="">Цвет</option>
                    <option v-for="color in colors" :value="color.id">{{ color.title }}</option>
                </select>
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="formFilter.transmission_id"
                    id="transmission">
                    <option value="">Трансмиссия</option>
                    <option v-for="transmission in transmissions" :value="transmission.id">{{
                            transmission.title
                        }}
                    </option>
                </select>
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="formFilter.drive_id"
                    id="drive">
                    <option value="">Привод</option>
                    <option v-for="drive in drives" :value="drive.id">{{ drive.title }}</option>
                </select>
                <select
                    class="w-full  rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="formFilter.body_type_id"
                    id="body_type">
                    <option value="">Кузов</option>
                    <option v-for="bodyType in bodyTypes" :value="bodyType.id">{{ bodyType.title }}</option>
                </select>
            </div>
        </form>

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

    </div>
</template>

<style scoped>

</style>
