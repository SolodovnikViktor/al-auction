<script setup>
import {Head, Link, usePage} from '@inertiajs/vue3';
import IndexLayout from "@/Layouts/IndexLayout.vue";
import AdminNav from "@/Components/Admin/AdminNav.vue";
import {computed, onBeforeMount, ref, watch} from 'vue'
import {Swiper, SwiperSlide} from "swiper/vue";
import {Navigation, Pagination} from 'swiper/modules';
import Filters from "@/Components/Main/Filters.vue";

const modules = [Navigation, Pagination];

const page = usePage()
const user = computed(() => page.props.auth.user)
const catalog_view = ref(user.value.catalog_view)
const catalogView = (bool) => catalog_view.value = bool

const props = defineProps(
    {
        posts: Object,
        brands: Array,
        fuels: Array,
        wheels: Array,
        colors: Array,
        drives: Array,
        bodyTypes: Array,
        transmissions: Array,
    }
);

// Просмотр фото мышкой
const postsData = props.posts.data
const currentKey = ref([]);

const updateCurrentKey = () => {
    currentKey.value = []
    props.posts.data.forEach(function (post) {
        if (post.photos.length > 0) {
            currentKey.value.push({postId: post.id, photoId: post.photos[0].id})
        }
    })
}
watch(() => props.posts, () =>{
    updateCurrentKey()
})
onBeforeMount(() => {
    updateCurrentKey()
})

function onMouseEnter(photoId, postId) {
    for (let object of currentKey.value) {
        if (object.postId === postId) {
            object.photoId = photoId;
        }
    }
}

function numberFilter(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
</script>

<template>
    <Head title="Админ панель"/>

    <IndexLayout>
        <template #adminNav>
            <AdminNav/>
        </template>
        <template #filters>
            <Filters @checkbox="catalogView"
                     :postsData
                     :user
                     :brands
                     :fuels
                     :wheels
                     :colors
                     :drives
                     :bodyTypes
                     :transmissions
            />
        </template>

        <div class="p-2 lg:p-4 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
            <div v-if="!postsData[0]" class="text-center">
                Вы не добавили машины!
            </div>
            <div>{{ catalog_view }}</div>
            <div class="grid grid-cols-12 gap-4 pb-4 mb-4 border-b border-gray-200">
                <div v-for="(post, postIndex) in posts.data" :key="post.id"
                     class="overflow-hidden col-span-12 sm:col-span-6 lg:col-span-4 text-gray-900">
                    <Link :href="route('admin-post.show', post.id)">
                        <div
                            v-if="post.photos.length > 0"
                            class="slider hidden sm:flex">
                            <div class="slider__elements">
                                <template v-for="photo in post.photos" :key="photo.id">
                                    <img class="slider__element"
                                         :class="{ active: currentKey[postIndex].photoId === photo.id  }"
                                         :src="photo.path" :alt="photo.name">
                                </template>
                            </div>
                            <div class="slider__ghosts">
                                <div
                                    v-for="photo in post.photos" :key="photo.id"
                                    @mouseenter.prevent="onMouseEnter(photo.id, post.id)"
                                    class="slider__ghost"
                                    :class="{active: currentKey[postIndex].photoId === photo.id}"
                                    :style="{width: 100 / post.photos.length + '%'}">
                                </div>
                            </div>
                        </div>
                        <swiper
                            v-if="post.photos.length > 0"
                            :pagination="{clickable: true}"
                            :space-between="5"
                            :modules="modules"
                            class="swiperIndex sm:hidden">
                            <swiper-slide v-for="photo in post.photos" :key="photo.id" class="hover:grow">
                                <img :src="photo.path" :alt="photo.name">
                            </swiper-slide>
                        </swiper>
                        <img
                            v-else
                            src="/storage/images/service/not_photo.jpg"
                            alt="Фото отсутствует">
                    </Link>
                    <div class="pt-4 px-2 text-gray-500">
                        <div class="flex relative justify-between">
                            <h4 class="text-lg text-black">
                                {{ post.brand }} {{ post.model }} <span class="text-gray-500">
                                {{ post.year_release }}г</span>
                            </h4>
                            <div>
                                <Link :href="route('admin-post.edit', post.id)"
                                      class="bg-gray-200 rounded-md px-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">
                                    Редактировать
                                </Link>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div>
                                <p>{{ post.mileage }}км</p>
                                <p>{{ post.horsepower }}лс</p>
                                <p>{{ post.engine_capacity }}л</p>
                            </div>
                            <div class="text-end">
                                <p>Vin: {{ post.vin }}</p>
                                <p>{{ post.transmission }}</p>
                                <p>{{ post.drive }}</p>
                            </div>
                        </div>
                        <div class="bg-lime-200 rounded-md text-lg mx-[-2px]">
                            <div v-if="post.up_price === null"
                                 class="flex justify-between px-1 bg-orange-200 rounded-md">
                                <span>{{ numberFilter(post.price) }}₽</span>
                                <span class="ml-3">Ставок нет</span>
                            </div>
                            <div v-else>
                                <span class="line-through">{{ numberFilter(post.price) }}₽</span>
                                <span class="ml-3">{{ numberFilter(post.up_price) }}</span>
                                <span class="text-xl font-bold">{{ post.count_bets }}₽</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav v-if="postsData" class="flex justify-end">
                <!--{{ posts.meta }}-->
                <!--<Pagination :links="posts.meta.links"></Pagination>-->
            </nav>
        </div>

    </IndexLayout>
</template>
//.hover\:grow {
//    transition: all 0.3s;
//    transform: scale(1);
//}
//
//.hover\:grow:hover {
//    transform: scale(1.02);
//}
<style>
.slider {
    width: 100%;
    height: 277px;
    position: relative;
    justify-content: center;
}

.slider__element {
    height: 100%;
    width: 100%;
    border-radius: 1rem;
    object-fit: cover;
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    align-items: center;
}

.slider__element.active {
    display: flex;
}

.slider__ghosts {
    display: flex;
    width: 95%;
    gap: 5px;
}

.slider__ghost {
    position: relative;
    height: calc(100% - 5px);
}

.slider__ghost::before {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    content: "";
    height: 0.3125rem;
    background-color: rgba(255, 255, 255, .3);
    transition: all 0.2s;
    border-radius: 2rem;
}

.slider__ghost.active.slider__ghost::before {
    background-color: rgb(100, 200, 200);
}

.slider__ghost.active {
    border-bottom-color: rgb(100, 200, 200);
}

.swiperIndex {
    max-width: 50rem;
    overflow: hidden;
    position: relative;
    padding: 4px;
    border-radius: 1rem;
}

.swiperIndex .swiper-wrapper {
    display: flex;
}

.swiperIndex .swiper-slide {
    flex-shrink: 0;
    padding-bottom: 52.8%;
    position: relative;
}

.swiperIndex .swiper-slide img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    /*object-fit: contain;*/

    border-radius: 1rem;
}

.swiper-pagination {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 0.625rem 15px 0.625rem;
    display: flex;
    gap: 0.3125rem;
}

.swiper-pagination-bullet {
    height: 0.3125rem;
    flex: 1 1 auto;
    background-color: rgba(255, 255, 255, .3);
    transition: all 0.3s;
    border-radius: 2rem;
}

.swiper-pagination-bullet-active {
    background-color: rgb(100, 200, 200);
}

.swiper-pagination-lock {
    display: none;
}

</style>
