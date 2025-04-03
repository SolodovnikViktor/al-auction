<script setup>
import {Head, Link} from '@inertiajs/vue3';
import IndexLayout from "@/Layouts/IndexLayout.vue";
import AdminNav from "@/Components/Admin/AdminNav.vue";

import {Navigation, Pagination} from 'swiper/modules';
import {Swiper, SwiperSlide} from 'swiper/vue';

const modules = [Navigation, Pagination];
const props = defineProps(
    ['posts', 'postsPaginate']
);
const posts = props.posts.data
console.log(posts)
console.log(props.posts)
console.log(props.postsPaginate)

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
                            <Link :href="route('admin-post.show', post.id)">
                                <p>Марка: {{ post.brand }} {{ post.model }}</p>
                                <p>Vin: {{ post.vin }}</p>
                            </Link>
                        </div>
                        <div>
                            <Link :href="route('admin-post.edit', post.id)" class="absolute top-0 right-0">
                                Редактировать
                            </Link>
                        </div>
                    </div>
                    <Link :href="route('admin-post.show', post.id)">
                        <swiper
                            :pagination="{clickable: true}"
                            :space-between="50"
                            :modules="modules"
                            class="mySwiper swiper">
                            <swiper-slide v-for="img in post.images" :key="img.id" class="hover:grow">
                                <img :src="img.path" :alt="img.name">
                            </swiper-slide>
                        </swiper>
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
</style>
