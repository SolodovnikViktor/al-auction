<script setup>
import {Head, Link} from '@inertiajs/vue3';
import IndexLayout from "@/Layouts/IndexLayout.vue";
import AdminNav from "@/Components/Admin/AdminNav.vue";
import {ref} from 'vue'

const props = defineProps(
    ['posts', 'postsPaginate']
);
const posts = props.posts.data
console.log(posts)
console.log(props.posts)
console.log(props.postsPaginate)

const currentKey = ref(0);
console.log(posts[0].photos[0].id)

function onMouseLeave() {
    // currentKey.value = 0;
    console.log(currentKey.value)
}

function onMouseEnter(key) {
    currentKey.value = key;
    console.log(currentKey.value);
}


</script>

<template>
    <Head title="Админ панель"/>

    <IndexLayout>
        <template #adminNav>
            <AdminNav/>
        </template>

        <div class="w-full p-2 lg:p-6 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
            <div v-if="!posts[0]" class="text-center">
                Вы не добавили машины!
            </div>
            <div class="grid grid-cols-12 gap-4 pb-4 mb-4 border-b border-gray-200">
                <div v-for="post in posts"
                     class="overflow-hidden col-span-12 sm:col-span-6 lg:col-span-3 text-gray-900">
                    <div class="flex relative justify-between">
                        <div>
                            <p>{{ post.brand }} {{ post.model }}</p>
                            <p>Vin: {{ post.vin }}</p>
                        </div>
                        <!--class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"-->
                        <div>
                            <Link :href="route('admin-post.edit', post.id)"
                                  class="absolute top-0 right-0 bg-gray-200 rounded-md px-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">
                                Редактировать
                            </Link>
                        </div>
                    </div>
                    <Link :href="route('admin-post.show', post.id)">
                        <div
                            @mouseleave="onMouseLeave"
                            v-if="post.photos.length > 0"
                            class="slider">

                            <img class="slider__element slider__element--main active"
                                 :src="post.photos[0].path" :alt="post.photos[0].name">

                            <div class="slider__elements">
                                <template v-for="photo in post.photos" :key="photo.id">
                                    <img class="slider__element"
                                         :class="{ active: photo.id === currentKey  }"
                                         :src="photo.path" :alt="photo.name">
                                </template>
                            </div>
                            <div class="slider__ghosts">
                                <div
                                    v-for="key in post.photos" :key="key.id"
                                    @mouseenter.prevent="onMouseEnter(key.id)"

                                    class="slider__ghost text-cyan-50"
                                    :class="{active: key.id === currentKey || 0 === currentKey && key.id === post.photos[0].id}"
                                    :style="{width: 100 / post.photos.length + '%'}">
                                    {{ key.id }}{{ currentKey }}
                                </div>
                            </div>
                        </div>

                        <img
                            v-else
                            src="/storage/images/service/not_photo.jpg"
                            alt="Фото отсутствует">
                    </Link>
                </div>
            </div>
            <nav v-if="posts" class="flex justify-end">
                {{ posts.meta }}
                <!--                <Pagination :links="posts.meta.links"></Pagination>-->
            </nav>
        </div>

    </IndexLayout>
</template>

<style>
.hover\:grow {
    transition: all 0.3s;
    transform: scale(1);
}

.hover\:grow:hover {
    transform: scale(1.02);
}

.slider {
    max-width: 50rem;
    height: 300px;
    position: relative;
    display: flex;
    justify-content: center;

}

.slider__elements {
    display: none;
}

.slider:hover .slider__elements {
    display: block;
}

.slider__element {
    color: #fff;
    font-size: 30px;
    height: 100%;
    width: 100%;
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    align-items: center;
}

.slider__element.slider__element--main {
    display: flex;
}


.slider__element.active {
    display: flex;
}

.slider__ghosts {
    display: flex;

    width: 90%;
    gap: 5px;
}

.slider__ghost {
    position: relative;
    border-bottom: 4px solid rgba(255, 255, 255, .5);
    height: calc(100% - 5px);
}

.slider__ghost:hover .slider__element.slider__element--main {
    display: none;
}

.slider__ghost.active {
    border-bottom-color: #00ffff;
}
</style>
