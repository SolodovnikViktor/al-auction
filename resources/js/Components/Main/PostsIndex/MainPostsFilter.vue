<script setup>
import {reactive, ref, watch} from "vue";
import axios from "axios";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import ButtonCyan from "@/Components/Button/ButtonCyan.vue";
import {router} from "@inertiajs/vue3";

const emit = defineEmits(['checkbox'])

const props = defineProps({
    posts: Object,
    formFilter: Object,
    formOrdering: Object,
    user: Object,
    brands: Array,
    fuels: Array,
    wheels: Array,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
});
let formFilter = reactive({
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
});
if (props.formFilter) {
    formFilter = reactive({
        brand_id: props.formFilter.brand_id || '',
        model_id: props.formFilter.model_id || '',
        price_ot: props.formFilter.price_ot || '',
        price_do: props.formFilter.price_do || '',
        year_ot: props.formFilter.year_ot || '',
        year_do: props.formFilter.year_do || '',
        mileage_ot: props.formFilter.mileage_ot || '',
        mileage_do: props.formFilter.mileage_do || '',
        color_id: props.formFilter.color_id || '',
        fuel_id: props.formFilter.fuel_id || '',
        wheel_id: props.formFilter.wheel_id || '',
        transmission_id: props.formFilter.transmission_id || '',
        drive_id: props.formFilter.drive_id || '',
        body_type_id: props.formFilter.body_type_id || '',
        engine_capacity: props.formFilter.engine_capacity || '',
    });
    getModel(props.formFilter.brand_id)
}
const models = ref()
let toggle = ref(props.user.catalog_view);
let postCount = ref(props.posts.meta.total);
let viewFullFilter = ref(false);
console.log(props.user.id)

function updateCatalogView() {
    axios.patch('/update-catalog-view', {catalog_view: toggle.value})
        .then(res => {
                router.reload({only: ['posts']})
                emit('checkbox', toggle.value)
            }
        )
        .catch((error) => {
            console.log(error);
        });
    // router.patch(route('admin-post.updateCatalogView', user.value.id), {
    //     catalog_view: toggle.value
    // })
}

function getModel(value) {
    axios.post('/post/get-model', value)
        .then(response => {
            models.value = response.data;
        })
        .catch(error => {
            console.log(error)
        });
}

watch(() => formFilter.brand_id, (value) => {
    if (formFilter.brand_id > 0) {
        getModel(value)
        formFilter.model_id = ''
    } else {
        models.value = ''
        formFilter.model_id = ''
    }
})

watch((formFilter), (formFilter) => {
    axios.post('/main/posts/filter', {formFilter: formFilter},)
        .then(response => {
            postCount.value = response.data
            console.log(postCount.value)
        })
        .catch(error => {
            console.log(error)
            console.log(error.message);
        });
});

const filterIndex = () => {
    router.get(route('main-posts.filter'),
        {formFilter: formFilter, formOrdering: props.formOrdering},
        {
            preserveState: true,
        }
    );
}

const cleanForm = () => {
    formFilter.brand_id = ''
    formFilter.model_id = ''
    formFilter.price_ot = ''
    formFilter.price_do = ''
    formFilter.year_ot = ''
    formFilter.year_do = ''
    formFilter.mileage_ot = ''
    formFilter.mileage_do = ''
    formFilter.color_id = ''
    formFilter.fuel_id = ''
    formFilter.wheel_id = ''
    formFilter.transmission_id = ''
    formFilter.drive_id = ''
    formFilter.body_type_id = ''
    formFilter.engine_capacity = ''
}
</script>

<template>
    <div class="p-4 sm:px-5 mb-4 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
        <form @submit.prevent="filterIndex">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <select
                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
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
                <div class="hidden sm:flex md:hidden lg:flex">
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

            </div>

            <transition name="filter">
                <div v-show="viewFullFilter"
                     class="grid grid-cols-1 mt-4 transition-all transform sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="flex sm:hidden md:flex lg:hidden">
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
                    <div class="flex">
                        <input
                            class="w-full rounded-s-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Пробег от"
                            v-model="formFilter.mileage_ot"
                            type="number"
                        />
                        <input
                            class="w-full rounded-e-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="до"
                            v-model="formFilter.mileage_do"
                            type="number"
                        />
                    </div>

                    <select
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        v-model="formFilter.color_id"
                        id="color">
                        <option value="">Цвет</option>
                        <option v-for="color in colors" :value="color.id">{{ color.title }}</option>
                    </select>
                    <select
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        v-model="formFilter.fuel_id"
                        id="fuel">
                        <option value="">Топливо</option>
                        <option v-for="option in fuels" :value="option.id">{{ option.title }}</option>
                    </select>
                    <select
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        v-model="formFilter.wheel_id"
                        id="wheel">
                        <option value="">Руль</option>
                        <option v-for="option in wheels" :value="option.id">{{ option.title }}</option>
                    </select>
                    <select
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        v-model="formFilter.transmission_id"
                        id="transmission">
                        <option value="">Трансмиссия</option>
                        <option v-for="transmission in transmissions" :value="transmission.id">{{
                                transmission.title
                            }}
                        </option>
                    </select>
                    <select
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        v-model="formFilter.drive_id"
                        id="drive">
                        <option value="">Привод</option>
                        <option v-for="drive in drives" :value="drive.id">{{ drive.title }}</option>
                    </select>
                    <select
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        v-model="formFilter.body_type_id"
                        id="body_type">
                        <option value="">Кузов</option>
                        <option v-for="bodyType in bodyTypes" :value="bodyType.id">{{ bodyType.title }}</option>
                    </select>
                    <div class="flex">
                        <input
                            class="w-full rounded-s-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Лошадей от"
                            v-model="formFilter.horsepower_ot"
                            type="number"
                        />
                        <input
                            class="w-full rounded-e-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="до"
                            v-model="formFilter.horsepower_do"
                            type="number"
                        />
                    </div>
                </div>
            </transition>

            <div class="flex w-full sm:hidden mt-4">
                <SecondaryButton class="w-1/2 py-3 justify-center mr-2">
                    Сбросить Х
                </SecondaryButton>
                <ButtonCyan :disabled="postCount === 0" type="submit" class="w-1/2 justify-center">
                    Показать {{ postCount }} авто
                </ButtonCyan>
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
                <div @click="viewFullFilter =!viewFullFilter" class="text-cyan-600 flex items-center">
                    <a v-show="!viewFullFilter" role="button">Развернуть ↓</a>
                    <a v-show="viewFullFilter" role="button">Свернуть ↑</a>
                </div>
                <div class="hidden sm:flex gap-4">
                    <SecondaryButton @click="cleanForm">Сбросить Х</SecondaryButton>
                    <ButtonCyan :disabled="postCount === 0" type="submit">Показать {{ postCount }} авто</ButtonCyan>
                </div>
            </div>
        </form>
    </div>
</template>

<style scoped>
.filter-enter-active,
.filter-leave-active {
    transition: all 0.5s ease-in-out;
}

.filter-enter-from,
.filter-leave-to {
    transform: translateX(50px);
    opacity: 0;
}
</style>
