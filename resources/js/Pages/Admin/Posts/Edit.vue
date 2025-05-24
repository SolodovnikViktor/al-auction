<script setup>
import {Head, router, useForm,} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import vueFilePond from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import FilePondPluginFileRename from 'filepond-plugin-file-rename';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import 'filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css';
import InputError from "@/Components/InputError.vue";
import AdminNav from "@/Components/Main/Admin/AdminNav.vue";
import {ref} from "vue";
import axios from "axios";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import DangerButton from "@/Components/Button/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import FormPost from "@/Components/Main/Admin/Form/FormPost.vue";

const props = defineProps({
    post: Object,
    brands: Array,
    fuels: Array,
    wheels: Array,
    colors: Array,
    drives: Array,
    bodyTypes: Array,
    transmissions: Array,
    user: Object,
});

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview, FilePondPluginFilePoster, FilePondPluginFileRename);

const form = useForm({
    brand_id: props.post.brand_id,
    model_id: props.post.model_id,
    vin: props.post.vin,
    year_release: props.post.year_release,
    color_id: props.post.color_id,
    mileage: props.post.mileage,
    fuel_id: props.post.fuel_id,
    wheel_id: props.post.wheel_id,
    drive_id: props.post.drive_id,
    body_type_id: props.post.body_type_id,
    transmission_id: props.post.transmission_id,
    engine_capacity: props.post.engine_capacity,
    horsepower: props.post.horsepower,
    price: props.post.price,
    description: props.post.description,
    is_published: props.post.is_published,
});

let myFiles = []
let key = ref(0);
let errorLoadFiles = ref('');
let errorMessage = ref('');

function getPhotos() {
    axios.get(`/admin/post/tmp-restore/${props.post.id}`)
        .then(response => {
            pondRestore(response.data)
        })
        .catch(error => {
            errorMessage.value = error.response.data.message;
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

// Загружено 1 фото
function handleFilePondLoad(id) {
    // console.log('load')
    // router.visit('create', {only: ['tmpImages'],})
    // router.reload({only: ['tmpImages']})
    return id;
}

// Удалить фото
function handleFilePondRevert(id, load, error) {
    errorLoadFiles.value = '';

    // filePositionId = filePositionId.replace(',' + id, '');
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

// Перетаскивание
function reorderFiles(files, origin, target) {

    let filePositionId = ''
    files.forEach(function (file) {

        if (filePositionId === '') {
            filePositionId = filePositionId + file.file.id
        } else {
            filePositionId = filePositionId + ',' + file.file.id
        }

    })
    console.log(filePositionId)

    axios.post(`/admin/post/tmp-reorder/${props.post.id}`, filePositionId)
        .then((response) => {
        })
        .catch((error) => {
            console.log(error);
        });
}

// Вызывается по готовности FilePond
function handleFilePondInit() {
    getPhotos()
}

// Вызывается, когда все файлы в списке были обработаны
function handleFilePondSuccess() {
    getPhotos()
}

//Ошибка  при загрузки
function FilePondErrorLoad(error, files) {
    // errorLoadFiles.value = error
    console.log(error)
    console.log(files)
}

const confirmingPostDeletion = ref(false);

const confirmPostDeletion = () => {
    confirmingPostDeletion.value = true;
};

const deletePost = (id) => {
    router.delete(route('admin-post.destroy', id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingPostDeletion.value = false;
    form.clearErrors();
    form.reset();
};

let toggle = ref();
toggle.value = props.post.is_published;

function updatePublished() {
    form.is_published = toggle.value
    router.patch(route('admin-post.updatePublished', props.post.id), {
        is_published: toggle.value
    })
}
</script>

<template>
    <Head title="Редактирование авто"/>

    <MainLayout>
        <template #adminNav>
            <AdminNav :post/>
        </template>

        <div class="p-2 lg:p-4 max-w-2xl mx-auto shadow sm:rounded-2xl bg-white">
            <div v-if="errorLoadFiles">
                <p class="text-red-500">Ошибка загрузки файла {{ errorLoadFiles }}</p>
            </div>
            <div v-if="errorMessage">
                <p class="text-red-500">Ошибка загрузки {{ errorMessage }}</p>
            </div>
            <div class="flex justify-between pb-2">
                <div class="flex items-center">
                    <p class="mr-3">Post id: {{ post.id }}</p>
                    <label class="relative mr-3 inline-flex items-center cursor-pointer">
                        <input
                            @change="updatePublished"
                            type="checkbox"
                            v-model="toggle"
                            :true-value="1"
                            :false-value="0"
                            class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600 hover:peer-checked:bg-indigo-700">
                        </div>
                    </label>
                    <p v-if="toggle">Опубликовано</p>
                    <p v-else>Скрыто</p>
                </div>
                <div>
                    <SecondaryButton class="mr-5">Скрыть</SecondaryButton>
                    <DangerButton @click="confirmPostDeletion">
                        Удалить
                    </DangerButton>
                </div>
            </div>

            <form :disabled="form.processing"
                  @submit.prevent="form.patch(route('admin-post.update', post.id))">
                <div class="overflow-hidden max-w-2xl pb-4 mb-4 border-b border-gray-200">
                    <div class="h-full mb-8 text-gray-900">
                        <!-- dropOnPage - FilePond будет перехватывать все файлы, размещенные на веб-странице
                             allow-reorder - Разрешить пользователям изменять порядок файлов с помощью перетаскивания.                    Обратите внимание, что это работает только в режиме одного столбца.
                              Это также работает только в браузерах, поддерживающих события указателя.
                             itemInsertLocation - Задайте 'after' для добавления файлов в конец списка (при перетаскивании в начало списка или добавлении с помощью поиска или вставки), задайте 'before' для добавления файлов в начало списка.

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
                                    url: `/admin/post/tmp-upload/${props.post.id}`,
                                    method: 'POST',
                                    withCredentials: false,
                                    onerror: FilePondErrorLoad,
                                    onload: handleFilePondLoad,
                                    },
                                revert:{
                                    url: `/admin/post/tmp-revert/${props.post.id}`,
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
                <nav class="flex justify-end">
                    <button type="submit"
                            class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Сохранить
                    </button>
                </nav>
            </form>
        </div>
        <Modal :show="confirmingPostDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900"
                >
                    Хотите удалить карточку автомобиля?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Все данные и фотографии будут удалены.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Закрыть
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        @click="deletePost(post.id)"
                    >
                        Удалить
                    </DangerButton>
                </div>
            </div>
        </Modal>

    </MainLayout>
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

<!--.hover\:grow {-->
<!--transition: all 0.3s;-->
<!--transform: scale(1);-->
<!--}-->

<!--.hover\:grow:hover {-->
<!--transform: scale(1.04);-->
<!--}-->
