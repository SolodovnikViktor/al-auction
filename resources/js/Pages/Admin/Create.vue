<script setup>
import {Head, useForm,} from '@inertiajs/vue3';
import IndexLayout from "@/Layouts/IndexLayout.vue";
import vueFilePond from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import FilePondPluginFileRename from 'filepond-plugin-file-rename';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import 'filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css';
import InputError from "@/Components/InputError.vue";
import AdminNav from "@/Components/Admin/AdminNav.vue";
import InputLabel from "@/Components/InputLabel.vue";
import MyInput from "@/Components/Options/MyInput.vue";
import {ref} from "vue";
import axios from "axios";

const props = defineProps({
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
    user: Object,
    // tmpImages: Array,
    // photoPosition: {
    //     type: Object,
    //     default: [],
    // },
});

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview, FilePondPluginFilePoster, FilePondPluginFileRename);

const formInputs = [
    {title: "title", value: 'Заголовок', type: "text", placeholder: 'Краткое описание'},
    {title: "vin", value: 'VIN', type: "text"},
    {title: "brand", value: 'Бренд', type: "text"},
    {title: "model", value: 'Модель', type: "text"},
    {title: "year_release", value: 'Год выпуска', type: "number"},
    {title: "color_id", value: 'Цвет кузова', type: "select"},
    {title: "mileage", value: 'Пробег', type: "number", placeholder: 'км'},
    {title: "fuel", value: 'Топливо', type: "text"},
    {title: "drive_id", value: 'Привод', type: "select"},
    {title: "body_type_id", value: 'Кузов', type: "select"},
    {title: "transmission_id", value: 'Коробка', type: "select"},
    {title: "engine_capacity", value: 'Объём двигателя', type: "number", inputmode: "decimal"},
    {title: "horsepower", value: 'Мощность', type: "number"},
    {title: "price", value: 'Цена', type: "number"},
    {title: "description", value: 'Описание', type: "textarea"},
];

const form = useForm({
    title: '',
    vin: '',
    brand: '',
    model: '',
    year_release: '',
    color_id: '',
    mileage: '',
    fuel: '',
    drive_id: '',
    body_type_id: '',
    transmission_id: '',
    engine_capacity: '',
    horsepower: '',
    price: '',
    description: '',
});

let myFiles = [];
let key = ref(0);
let errorLoadFiles = ref('');
let errorMessage = ref(false);

function getPhotos() {
    axios.get('/admin/tmp-restore')
        .then(response => {
            pondRestore(response.data)
        })
        .catch(error => {
            errorMessage = error.response.data.message;
            console.log(error.response.data.message);
            console.log(error.response.data);
            console.log(error.message);
        });
}

function pondRestore(photos) {
    myFiles = []
    errorLoadFiles.value = '';

    console.log(photos)

    for (const photo of photos) {
        // form.photos_arr.push(photo.id.toString())
        // if (photoPosition === '') {
        //     photoPosition = photoPosition + photo.id
        // } else {
        //     photoPosition = photoPosition + ',' + photo.id
        // }
        myFiles.push({
            source: photo.path,
            options: {
                type: 'limbo',
                // type: 'local',
                file: {
                    name: photo.name,
                    size: photo.size,
                    type: 'webp',
                    id: photo.id,
                },
                metadata: {
                    poster: photo.path
                }
            }
        });
    }
    key.value = 1
    key.value = 0
}

// Перетаскивание
function reorderFiles(files, origin, target) {
    // form.photos_arr = []
    console.log(files)
    let photoPosition = ''
    files.forEach(function (file) {
        // form.photos_arr.push(file.file.id.toString())
        if (photoPosition === '') {
            photoPosition = photoPosition + file.file.id
        } else {
            photoPosition = photoPosition + ',' + file.file.id
        }
    })
    console.log(photoPosition)

    axios.post('/admin/tmp-reorder', photoPosition)
        .catch((error) => {
            console.log(error);
        });
}

// Загружено 1 фото
function handleFilePondLoad(id) {
    // form.photos_arr.push(id)
    // console.log('load')
    // router.visit('create', {only: ['tmpImages'],})
    // router.reload({only: ['tmpImages']})
    return id;
}

// Удалить фото
function handleFilePondRevert(id, load, error) {
    // form.photos_arr = form.photos_arr.filter((photo) => photo !== id);
    errorLoadFiles.value = '';
    console.log(id)
    // console.log(photoPosition)
    // photoPosition = photoPosition.replace(id, '');
    //
    // photoPosition = photoPosition.replace(/(^[,\s]+)|([,\s]+$)/g, '');
    // photoPosition = photoPosition.replace(',,', ',');
    // console.log(photoPosition)

    // photoPosition = photoPosition.replace(',' + id, '');
}

// Переименовать
function fileRenameFunction(file) {
    const random = Math.random().toString(36).substring(2).toUpperCase();
    return random + file.extension;
}

// Клик по фото
function activateFile(i) {
    console.log(i.file)
}

// Вызывается по готовности FilePond
function handleFilePondInit() {
    getPhotos()
}

// Вызывается, когда все файлы в списке были обработаны
function handleFilePondSuccess() {
    getPhotos()
}

//Ошибка  при загрузке
function FilePondErrorLoad(error, files) {
    // errorLoadFiles.value = error
    console.log(error)
    console.log(files)
}
</script>

<template>
    <Head title="Админ панель"/>

    <IndexLayout>
        <template #adminNav>
            <AdminNav/>
        </template>

        <div class="p-2 lg:p-4 max-w-2xl mx-auto shadow sm:rounded-2xl bg-white">

            <form :disabled="form.processing" @submit.prevent="$event => form.post(route('admin-post.store'),{
                onError: error => {console.log(error)},
            })">
                <div class="overflow-hidden max-w-2xl pb-4 mb-4 border-b border-gray-200">
                    <div class="h-full text-gray-900">
                        <!-- dropOnPage - FilePond будет перехватывать все файлы, размещенные на веб-странице
                             allow-reorder - Разрешить пользователям изменять порядок файлов с помощью перетаскивания.                    Обратите внимание, что это работает только в режиме одного столбца.
                              Это также работает только в браузерах, поддерживающих события указателя.
                             itemInsertLocation - Задайте 'after' для добавления файлов в конец списка (при перетаскивании в
                              начало списка или добавлении с помощью поиска или вставки), задайте 'before' для добавления файлов в
                              начало списка.
                             forceRevert - Установите значение true, чтобы потребовать успешного возврата файла перед продолжением
                              -->
                        <file-pond
                            name="photoFilePond"
                            ref="pond"
                            :key
                            class-name="my-pond"
                            labelFileProcessing="Загрузка"
                            labelFileProcessingComplete="Загружен"
                            labelTapToCancel="Остановить"
                            labelTapToUndo=""
                            labelFileTypeNotAllowed='Только фотографии'
                            fileValidateTypeLabelExpectedTypes='{allButLastType} и {lastType}'
                            dropOnPage="true"
                            allow-reorder="true"
                            itemInsertLocation="after"
                            force-revert="true"
                            credits=""
                            label-idle='<span class="filepond--label-action"> Нажать </span> или перетащить фото
                                <svg class="w-15 h-8 inline text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>'
                            accepted-file-types="image/jpeg, image/png"
                            allow-multiple="true"
                            checkValidity="true"
                            imagePreviewMaxHeight="230"
                            filePosterMaxHeight="230"
                            maxFiles="40"
                            allowFileRename="true"
                            :fileRenameFunction="fileRenameFunction"
                            :server="{
                                url:'',
                                timeout: 120000,
                                process:{
                                    url: '/admin/tmp-upload',
                                    method: 'POST',
                                    withCredentials: false,
                                    onerror: FilePondErrorLoad,
                                    onload: handleFilePondLoad,
                                    },
                                revert:{
                                    url: '/admin/tmp-revert',
                                    method: 'DELETE',
                                    onload: handleFilePondRevert
                                },
                                headers: {'X-CSRF-TOKEN': $page.props.csrf_token}
                            }"
                            v-bind:files=myFiles
                            v-on:init="handleFilePondInit"
                            v-on:activatefile="activateFile"
                            v-on:reorderfiles="reorderFiles"
                            v-on:processfiles="handleFilePondSuccess"
                        />
                        <InputError class="mt-2" :message="errorLoadFiles"/>
                    </div>
                    <div v-for="(input, index) in formInputs" :key=index
                         class=" p-1 sm:col-span-3 lg:col-span-2 text-gray-900">
                        <div>
                            <InputLabel :for="input.title" :value="input.value"/>
                            <MyInput
                                :id="input.title"
                                :type="input.type"
                                :inputmode="input.inputmode"
                                :placeholder="input.placeholder"
                                :colors
                                :drives
                                :bodyTypes
                                :transmissions
                                v-model="form[input.title]"
                            />
                            <InputError class="mt-2" :message="form.errors[input.title]"/>
                        </div>
                    </div>

                </div>
                <nav class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                            class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Отправить
                    </button>
                </nav>
            </form>
        </div>

    </IndexLayout>
</template>

<style>

.filepond--item {
    width: calc(50% - 0.5em);
}

.filepond--wrapper {
    margin-bottom: -10px;
}

.filepond--credits {
    display: none;
}

.hover\:grow {
    transition: all 0.3s;
    transform: scale(1);
}

.hover\:grow:hover {
    transform: scale(1.04);
}
</style>

<!--<div class="relative w-full shadow sm:rounded-2xl bg-white">-->
<!--<div-->
<!--    class="overflow-hidden grid grid-cols-12 gap-4 pb-4 mb-4 border-b border-gray-600">-->
<!--    <div class="col-span-12 p-4 lg:p-6 sm:col-span-3 text-gray-900 ">-->
<!--        <input v-model="form.title" type="text" class="mb-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Имя" required>-->

<!--        <div class="flex items-center justify-center w-full">-->
<!--            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">-->
<!--                <div class="flex flex-col items-center justify-center pt-5 pb-6">-->
<!--                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">-->
<!--                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>-->
<!--                    </svg>-->
<!--                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>-->
<!--                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>-->
<!--                </div>-->
<!--                <input id="dropzone-file" type="file" class="hidden" />-->
<!--            </label>-->
<!--        </div>-->

<!--        <progress v-if="form.progress" :value="form.progress.percentage" max="100">-->
<!--            {{ form.progress.percentage }}%-->
<!--        </progress>-->

<!--        Фото машин-->
<!--    </div>-->

<!--</div>-->
<!--<nav class="flex justify-end pb-4 px-4" >-->
<!--    <button @click="store" class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5">-->
<!--        Отправить-->
<!--    </button>-->
<!--</nav>-->
<!--</div>-->


// const photo = new FormData();
// files.forEach(file => {
//     photo.append('photo[]', file)
// })
// form.title = 'form.title';

<!--<div class="col-span-12 p-4 lg:p-6 sm:col-span-3 text-gray-900 ">-->
<!--<input v-model="form.title" type="text" class="mb-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Имя" required>-->
<!--<div ref="dropzone" class="min-h-[300px]  rounded-2xl text-center  hover:grow hover:shadow-lg bg-gray-200">-->
<!--    Перетащить фото или нажать-->
<!--</div>-->
<!--<progress v-if="form.progress" :value="form.progress.percentage" max="100">-->
<!--    {{ form.progress.percentage }}%-->
<!--</progress>-->

<!--Фото машин-->
<!--</div>-->

<!--<progress v-if="form.progress" :value="form.progress.percentage" max="100">-->
<!--{{ form.progress.percentage }}%-->
<!--</progress>-->
