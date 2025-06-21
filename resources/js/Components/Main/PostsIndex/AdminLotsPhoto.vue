<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import {onBeforeMount, reactive, ref, watch} from 'vue'
import {Link, router} from "@inertiajs/vue3";
import {Navigation, Pagination} from 'swiper/modules';
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import SvgOrdering from "@/Components/Main/SVG/SvgOrdering.vue";

const modules = [Navigation, Pagination];
const props = defineProps({
    lots: Object,
    formFilter: Object,
    formOrdering: Object,
    formSearch: Object,
})

let formOrdering = reactive({
    ordering_direction: '',
    ordering_value: '',
    ordering_desc: '',
    ordering_asc: '',
})
if (props.formOrdering) {
    formOrdering = reactive({
        ordering_direction: props.formOrdering.ordering_direction,
        ordering_value: props.formOrdering.ordering_value,
        ordering_desc: props.formOrdering.ordering_desc,
        ordering_asc: props.formOrdering.ordering_asc,
    })
}

// Просмотр фото мышкой
const currentKey = ref([]);

const updateCurrentKey = () => {
    currentKey.value = []
    props.lots.data.forEach(function (lot) {
        if (lot.photos.length > 0) {
            currentKey.value.push({lotId: lot.id, photoId: lot.photos[0].id})
        } else {
            currentKey.value.push({lotId: null, photoId: null})
        }
    })
}
watch(() => props.lots, () => {
    updateCurrentKey()
})
onBeforeMount(() => {
    updateCurrentKey()
})

function onMouseEnter(photoId, lotId) {
    for (let object of currentKey.value) {
        if (object.lotId === lotId) {
            object.photoId = photoId;
        }
    }
}

function numberFilter(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

const filterOn = (value) => {
    formOrdering.ordering_value = value
    if (formOrdering.ordering_desc === 'false') {
        formOrdering.ordering_direction = 'desc';
        formOrdering.ordering_desc = 'true';
        formOrdering.ordering_asc = 'false';
    } else {
        formOrdering.ordering_direction = 'asc';
        formOrdering.ordering_desc = 'false';
        formOrdering.ordering_asc = 'true';
    }
    if (route().current('admin-lots.filter')) {
        router.get(route('admin-lots.filter'),
            {formOrdering: formOrdering, formFilter: props.formFilter, formSearch: props.formSearch},
            {
                preserveState: true,
                preserveScroll: true
            }
        );
    }
    if (route().current('admin-lots.search')) {
        router.get(route('admin-lots.search'),
            {formOrdering: formOrdering, formFilter: props.formFilter, formSearch: props.formSearch},
            {
                preserveState: true,
                preserveScroll: true
            }
        )
    }
    if (route().current('admin-lots.index')) {
        router.get(route('admin-lots.index'),
            {formOrdering: formOrdering, formFilter: props.formFilter, formSearch: props.formSearch},
            {
                preserveState: true,
                preserveScroll: true
            }
        )
    }
}
</script>

<template>
    <div class="flex justify-end mb-4">
        <SecondaryButton
            @click="filterOn( 'price')"
            class="justify-center w-1/3 sm:w-min mr-2"
            :class="['price' === formOrdering.ordering_value ? '!text-black' : '']"
        >
            Цена
            <SvgOrdering
                :formOrdering
                :keyOrdering="'price'"
            />
        </SecondaryButton>
        <SecondaryButton
            @click="filterOn( 'year_release')"
            class="justify-center w-1/3 sm:w-min mr-2"
            :class="['year_release' === formOrdering.ordering_value ? '!text-black' : '']"
        >
            Год
            <SvgOrdering
                :formOrdering
                :keyOrdering="'year_release'"
            />
        </SecondaryButton>
        <SecondaryButton
            @click="filterOn( 'mileage')"
            class="justify-center w-1/3 sm:w-min mr-2"
            :class="['mileage' === formOrdering.ordering_value ? '!text-black' : '']"
        >
            Пробег
            <SvgOrdering
                :formOrdering
                :keyOrdering="'mileage'"
            />
        </SecondaryButton>
    </div>

    <div class="grid grid-cols-12 gap-4 pb-4 mb-4 border-b border-gray-200">
        <div v-for="(lot, lotIndex) in lots.data" :key="lot.id"
             class="overflow-hidden col-span-12 sm:col-span-6 lg:col-span-4 text-gray-900">
            <Link :href="route('admin-lot.show', lot.id)">
                <div
                    v-if="lot.photos.length > 0"
                    class="slider hidden sm:flex">
                    <div class="slider__elements">
                        <template v-for="photo in lot.photos" :key="photo.id">
                            <img class="slider__element"
                                 :class="{ active: currentKey[lotIndex].photoId === photo.id }"
                                 :src="photo.path" :alt="photo.name">
                        </template>
                    </div>
                    <div class="slider__ghosts">
                        <div
                            v-for="photo in lot.photos" :key="photo.id"
                            @mouseenter.prevent="onMouseEnter(photo.id, lot.id)"
                            class="slider__ghost"
                            :class="{ active: currentKey[lotIndex].photoId === photo.id }"
                            :style="{width: 100 / lot.photos.length + '%'}">
                        </div>
                    </div>
                </div>
                <swiper
                    v-if="lot.photos.length > 0"
                    :pagination="{clickable: true}"
                    :space-between="5"
                    :modules="modules"
                    class="swiperIndex sm:hidden">
                    <swiper-slide v-for="photo in lot.photos" :key="photo.id" class="hover:grow">
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
                        {{ lot.brand }} {{ lot.model }} <span class="text-gray-500">
                                {{ lot.year_release }}г</span>
                    </h4>
                    <div>
                        <Link :href="route('admin-lot.edit', lot.id)"
                              class="bg-gray-200 rounded-md px-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">
                            Редактировать
                        </Link>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div>
                        <p>{{ lot.mileage }}км</p>
                        <p>{{ lot.horsepower }}лс</p>
                        <p>{{ lot.engine_capacity }}л</p>
                    </div>
                    <div class="text-end">
                        <p>Vin: {{ lot.vin }}</p>
                        <p>{{ lot.transmission }}</p>
                        <p>{{ lot.drive }}</p>
                    </div>
                </div>
                <div class="bg-lime-200 rounded-md text-lg mx-[-2px]">
                    <div v-if="!lot.bets.length"
                         class="flex justify-between px-1 bg-orange-200 rounded-md">
                        <span>{{ numberFilter(lot.price) }}₽</span>
                        <span class="ml-3">Ставок нет</span>
                    </div>
                    <div v-else class="flex justify-between px-2 rounded-md">
                        <span class="line-through">{{ numberFilter(lot.price) }}₽</span>
                        <span class="ml-3">{{ numberFilter(lot.bets.at(-1).up_bet) }}₽</span>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

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
