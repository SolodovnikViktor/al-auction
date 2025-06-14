<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import ButtonPlus from "@/Components/Button/ButtonPlus.vue";
import ButtonClose from "@/Components/Button/ButtonClose.vue";
import ButtonOk from "@/Components/Button/ButtonOk.vue";
import {ref, watch, nextTick, onMounted} from "vue";
import {useForm} from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps({
    form: Object,
    brands: Array,
})

const arrBrands = ref(props.brands)

const formBrand = useForm({
    title: ''
})
const formModel = useForm({
    title: '',
    brand_id: '',
})

let modelOptions = ref();

let inputBrand_is = ref(false);
let inputModel_is = ref(false);
let blockModel_is = ref(false);

const inputBrandOpen = () => {
    inputBrand_is.value = true;
    nextTick(() => {
        document.getElementById('inputBrand').focus();
    });
}
const inputModelOpen = () => {
    inputModel_is.value = true
    nextTick(() => {
        document.getElementById('inputModel').focus();
    });
}
const inputBrandClose = () => {
    inputBrand_is.value = false;
}
const inputModelClose = () => {
    inputModel_is.value = false;
}

const closeOnEscape = (e) => {
    if (e.key === 'Escape') {
        inputBrand_is.value = false;
        inputModel_is.value = false;
    }
    if (e.key === 'Enter' && document.getElementById('inputBrand') === focus()) {
        console.log(123)
    }
};

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape)
    if (props.form.brand_id > 0) {
        getModel(props.form.brand_id)
    }
});

watch(() => props.form.brand_id, (value) => {
    if (props.form.brand_id > 0) {
        props.form.clearErrors('brand')
        props.form.model_id = ''
        getModel(value)
    }
})

function getModel(value, bool) {
    axios.post('/post/get-model', value)
        .then(response => {
            showModel(response.data, bool)
        })
        .catch(error => {
            console.log(error)
            console.log(error.message);
        });
}

function showModel(option, bool) {
    if (option.length >= 1) {
        modelOptions.value = option;
        if (bool === true) {
            props.form.model_id = modelOptions.value.slice(-1)[0].id;
        }
        inputModel_is.value = false;
    } else {
        modelOptions.value = false;
        inputModelOpen();
    }
    blockModel_is.value = true
}

const storeBrand = () => {
    formBrand.post(route('admin-post.storeBrand', formBrand), {
        preserveScroll: true,
        onSuccess: (res) => {
            let lastElem = res.props.brands.slice(-1);
            arrBrands.value.push(lastElem[0]);
            props.form.brand_id = lastElem[0].id
            inputBrand_is.value = false;
        },
        onFinish: () => {
            formBrand.reset()
            console.log('formBrand reset')
        },
    });
}

const storeModel = () => {
    formModel.brand_id = props.form.brand_id;
    formModel.post(route('admin-post.storeModel', formModel), {
        preserveScroll: true,
        onSuccess: () => {
            getModel(props.form.brand_id, true);
        },
        onFinish: () => {
            formModel.reset()
            console.log('formModel reset')
        },
    });
}

</script>

<template>
    <div class="p-1 sm:col-span-3 lg:col-span-2 text-gray-900">
        <div class="flex justify-between relative">
            <InputLabel class="content-center" for="brand" value="Бренд"/>
            <div class="flex w-3/4">
                <select
                    v-show="!inputBrand_is"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.brand_id"
                    id="brand">
                    <option disabled value="">Выберите бренд</option>
                    <option v-for="option in arrBrands" :value="option.id">{{ option.title }}</option>
                </select>
                <ButtonPlus
                    v-show="!inputBrand_is"
                    @click="inputBrandOpen"
                    :value="'Бренд'"
                    class="absolute right-[40px] top-[3px] w-9"
                    type="button"
                />
                <form @submit.prevent="storeBrand" v-show="inputBrand_is" class="w-full">
                    <input
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        v-model="formBrand.title"
                        placeholder="Введите название бренда"
                        id="inputBrand"
                    />
                    <InputError class="mt-2" :message="formBrand.errors.title"/>
                    <div
                        class="absolute flex right-1 top-[3px]">
                        <ButtonClose
                            @click.prevent="inputBrandClose"
                            class="w-9"
                            type="button"
                        />
                        <ButtonOk
                            class="w-9"
                            type="submit"
                        />
                    </div>
                </form>
            </div>
        </div>
        <InputError class="mt-2" :message="form.errors.brand_id"/>
    </div>
    <transition>
        <div v-show="blockModel_is" class="p-1 sm:col-span-3 lg:col-span-2 text-gray-900">
            <div class="flex justify-between relative">
                <InputLabel class="content-center" for="model" value="Модель"/>
                <div class="flex w-3/4">
                    <select
                        v-show="!inputModel_is"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        :class="{'hidden' : inputModel_is}"
                        v-model="form.model_id"
                        id="model">
                        <template v-if="modelOptions">
                            <option disabled value="">Выберите модель</option>
                            <option v-for="option in modelOptions" :value="option.id">{{ option.title }}</option>
                        </template>
                        <template v-else>
                            <option disabled value="">Добавьте модель</option>
                        </template>
                    </select>
                    <ButtonPlus
                        v-show="!inputModel_is"
                        @click="inputModelOpen"
                        :value="'Модель'"
                        class="absolute right-[40px] top-[3px] w-9"
                        type="button"
                    />
                    <form @submit.prevent="storeModel" v-show="inputModel_is" class="w-full">
                        <input
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            v-model="formModel.title"
                            placeholder="Введите название модели"
                            id="inputModel"
                        />
                        <InputError class="mt-2" :message="formModel.errors.title"/>
                        <div
                            class="absolute flex right-1 top-[3px]">
                            <ButtonClose
                                @click="inputModelClose"
                                class="w-9"
                                type="button"
                            />
                            <ButtonOk
                                class="w-9"
                                type="submit"
                            />
                        </div>
                    </form>
                </div>
            </div>
            <InputError class="mt-2" :message="form.errors.model_id"/>
        </div>
    </transition>
</template>
'pointer-events-none' : !select_is
'pointer-events-none' : !button_open_is

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;

}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
