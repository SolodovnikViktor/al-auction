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
import {ref} from "vue";
import axios from "axios";
import FormPost from "@/Components/Admin/Form/FormPost.vue";
import ButtonCyan from "@/Components/Button/ButtonCyan.vue";
import FormInput from "@/Components/Admin/Form/FormInput.vue";

const props = defineProps({
    brands: Array,
    fuels: Array,
    wheels: Array,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
});

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview, FilePondPluginFilePoster, FilePondPluginFileRename);

const form = useForm({
    brand_id: '',
    model_id: '',
    vin: '',
    year_release: '',
    color_id: '',
    mileage: '',
    fuel_id: '',
    wheel_id: '',
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
    for (const photo of photos) {
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
    let photoPosition = ''
    files.forEach(function (file) {
        // form.photos_arr.push(file.file.id.toString())
        if (photoPosition === '') {
            photoPosition = photoPosition + file.file.id
        } else {
            photoPosition = photoPosition + ',' + file.file.id
        }
    })
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

            <form :disabled="form.processing" @submit.prevent="form.post(route('admin-post.store'),{
                onError: error => {console.log(error)}})">
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
                                    onload: handleFilePondLoad
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
                    <FormPost
                        :brands
                        :colors
                        :fuels
                        :wheels
                        :drives
                        :bodyTypes
                        :transmissions
                        :form="form"
                    />
                </div>
                <div class="flex justify-end">
                    <ButtonCyan
                        type="submit"
                        :disabled="form.processing"
                        class="px-5 py-2.5">
                        Отправить
                    </ButtonCyan>
                </div>
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
</style>
