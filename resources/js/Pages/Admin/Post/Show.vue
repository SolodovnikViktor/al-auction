<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/AdminNav.vue";
import {Head, Link} from "@inertiajs/vue3";
import {Swiper, SwiperSlide} from "swiper/vue";
import {FreeMode, Navigation, Thumbs} from 'swiper/modules';
import 'swiper/css/navigation';
import {ref} from "vue";

const props = defineProps({
    post: Object
})
const modules = [FreeMode, Navigation, Thumbs];
const thumbsSwiper = ref(null);
const setThumbsSwiper = (swiper) => {
    thumbsSwiper.value = swiper;
};

let isSlidesPerView = ref(4);

if (props.post.photos.length > 4) {
    isSlidesPerView = ref(4.2)
}

function numberFilter(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

</script>

<template>
    <Head title="Карточка авто"/>

    <MainLayout>
        <template #navigation>
            <AdminNav :post/>
        </template>
        <div class="max-w-screen-xl mx-auto">
            <div class="grid grid-cols-12 gap-4">
                <div class="overflow-hidden col-span-12 lg:col-span-8">
                    <swiper
                        v-if="post.photos.length > 0"
                        :style="{'--swiper-navigation-color': '#fff','--swiper-pagination-color': '#fff'}"
                        :loop="true"
                        :spaceBetween="5"
                        :navigation="true"
                        :thumbs="{ swiper: thumbsSwiper }"
                        :modules="modules"
                        class="mySwiperUp">
                        <swiper-slide v-for="photo in post.photos" :key="photo.id" class="hover:grow">
                            <img :src="photo.path" :alt="photo.name">
                        </swiper-slide>
                    </swiper>
                    <swiper
                        v-if="post.photos.length > 0"
                        @swiper="setThumbsSwiper"
                        :loop="false"
                        :spaceBetween="10"
                        :slidesPerView="isSlidesPerView"
                        :freeMode="true"
                        :watchSlidesProgress="true"
                        :modules="modules"
                        class="mySwiperDown p-2 sm:p-0"
                    >
                        <swiper-slide v-for="photo in post.photos" :key="photo.id" class="hover:grow">
                            <img :src="photo.pathMin" :alt="photo.name">
                        </swiper-slide>
                    </swiper>
                    <img
                        v-else
                        class="rounded-2xl"
                        src="/storage/images/service/not_photo.jpg"
                        alt="Фото отсутствует">


                    <div
                        class="px-4 py-3 block lg:hidden mt-5 mx-auto shadow sm:rounded-2xl bg-white text-gray-500">
                        <div class="      ">
                            <div class="mb-3 flex justify-between">
                                <h4 class="text-xl overflow-auto div-no-scrollbar font-medium text-black">
                                    {{ post.brand }} {{ post.model }}
                                    <span class="text-gray-500">
                                    {{ post.year_release }}г
                                </span>
                                </h4>
                                <div>
                                    <Link :href="route('admin-post.edit', post.id)"
                                          class="bg-gray-200 rounded-md p-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">
                                        Редактировать
                                    </Link>
                                </div>
                            </div>
                            <div class="bg-lime-200 rounded-md text-xl mx-[-2px] mb-5">
                                <div v-if="post.up_price === null"
                                     class="flex justify-between px-1 bg-orange-200 rounded-md">
                                    <span>{{ numberFilter(post.price) }}₽</span>
                                    <span class="ml-3">Ставок нет</span>
                                </div>
                                <div v-else>
                                    <span class="line-through">{{ numberFilter(post.price) }}₽</span>
                                    <span class="ml-3">{{ numberFilter(post.up_price) }}₽</span>
                                    <span class="text-xl font-bold">{{ post.count_bets }}</span>
                                </div>
                            </div>
                            <div>
                                <h5 class="text-lg font-bold mb-2">
                                    Характеристики
                                </h5>
                                <div class="*:text-base">
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>VIN</p>
                                        <p class="font-bold overflow-auto div-no-scrollbar">{{ post.vin }}</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Год выпуска</p>
                                        <p class="font-bold">{{ post.year_release }}</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Пробег</p>
                                        <p class="font-bold">{{ numberFilter(post.mileage) }} км</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Тип кузова</p>
                                        <p class="font-bold">{{ post.body_type }}</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Руль</p>
                                        <p class="font-bold">{{ post.wheel }}</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Привод</p>
                                        <p class="font-bold">{{ post.drive }}</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Трансмиссия</p>
                                        <p class="font-bold">{{ post.transmission }}</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Цвет кузова</p>
                                        <p class="font-bold">{{ post.color }}</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Мощность</p>
                                        <p class="font-bold">{{ post.horsepower }} лс</p>
                                    </div>
                                    <div class="flex mb-4 *:w-1/2">
                                        <p>Топливо</p>
                                        <p class="font-bold">{{ post.fuel }}</p>
                                    </div>
                                    <div class="flex *:w-1/2">
                                        <p>Обьём</p>
                                        <p class="font-bold scrollb">{{ post.engine_capacity }} л</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <!--Описание-->
                    <div class="mt-10 px-2">
                        <h2 class="text-3xl font-bold mb-4">Описание</h2>
                        <div class="whitespace-pre-wrap">{{ post.description }}</div>
                    </div>
                </div>
                <!--Боковые хар-ки-->
                <div class="hidden lg:block col-span-4">
                    <div class="px-4 py-3 sticky top-28 rounded-2xl shadow bg-white text-gray-500">
                        <div class="mb-3 flex justify-between">
                            <h4 class="text-xl overflow-auto div-no-scrollbar font-medium text-black">
                                {{ post.brand }} {{ post.model }}
                                <span class="text-gray-500">
                                    {{ post.year_release }}г
                                </span>
                            </h4>
                            <div>
                                <Link :href="route('admin-post.edit', post.id)"
                                      class="bg-gray-200 rounded-md p-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">
                                    Редактировать
                                </Link>
                            </div>
                        </div>
                        <div class="bg-lime-200 rounded-md text-xl mx-[-2px] mb-5">
                            <div v-if="post.up_price === null"
                                 class="flex justify-between px-1 bg-orange-200 rounded-md">
                                <span>{{ numberFilter(post.price) }}₽</span>
                                <span class="ml-3">Ставок нет</span>
                            </div>
                            <div v-else>
                                <span class="line-through">{{ numberFilter(post.price) }}₽</span>
                                <span class="ml-3">{{ numberFilter(post.up_price) }}₽</span>
                                <span class="text-xl font-bold">{{ post.count_bets }}</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="text-lg font-bold mb-2">
                                Характеристики
                            </h5>
                            <div class="*:text-base">
                                <div class="flex mb-4 *:w-1/2">
                                    <p>VIN</p>
                                    <p class="font-bold overflow-auto div-no-scrollbar">{{ post.vin }}</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Год выпуска</p>
                                    <p class="font-bold">{{ post.year_release }}</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Пробег</p>
                                    <p class="font-bold">{{ numberFilter(post.mileage) }} км</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Тип кузова</p>
                                    <p class="font-bold">{{ post.body_type }}</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Руль</p>
                                    <p class="font-bold">{{ post.wheel }}</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Привод</p>
                                    <p class="font-bold">{{ post.drive }}</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Трансмиссия</p>
                                    <p class="font-bold">{{ post.transmission }}</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Цвет кузова</p>
                                    <p class="font-bold">{{ post.color }}</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Мощность</p>
                                    <p class="font-bold">{{ post.horsepower }} лс</p>
                                </div>
                                <div class="flex mb-4 *:w-1/2">
                                    <p>Топливо</p>
                                    <p class="font-bold">{{ post.fuel }}</p>
                                </div>
                                <div class="flex *:w-1/2">
                                    <p>Обьём</p>
                                    <p class="font-bold scrollb">{{ post.engine_capacity }} л</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style>
.div-no-scrollbar::-webkit-scrollbar {
    display: none;
}

.div-no-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}

.mySwiperUp {
    height: 450px;
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    position: relative;
    list-style: none;
    padding: 0;
    z-index: 1;
    display: block;
    user-select: none;
}

.swiper-wrapper {
    display: flex;
    height: 100%;
}

.mySwiperUp .swiper-slide {
    flex-shrink: 0;
    position: relative;
    height: 100%;
}

.swiper-slide img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 1rem;
}

@media (max-width: 640px) {
    .mySwiperUp .swiper-slide img {
        border-radius: 0;
    }
}

.mySwiperDown {
    height: 9rem;
    box-sizing: border-box;
    padding-top: 10px;
    user-select: none;
}

.mySwiperDown .swiper-slide {
    flex-shrink: 0;
    position: relative;
    height: 100%;
    width: 100%;
}

.mySwiperDown .swiper-slide :hover {
    border: 1px solid #ff2d20;
    border-radius: 1rem;
}

.mySwiperDown .swiper-slide-thumb-active {
    border: 1px solid #ff2d20;
    border-radius: 1rem;
}

.mySwiperDown .hover\:grow:hover {
    transition: all 999999s !important;
}

@font-face {
    font-family: 'swiper-icons';
    src: url('data:application/font-woff;charset=utf-8;base64, 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');
    font-weight: 400;
    font-style: normal;
}
</style>
