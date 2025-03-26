<script setup>
import {Head} from '@inertiajs/vue3';
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
            {{ posts }}
            <div class="grid grid-cols-12 gap-4 pb-4 mb-4 border-b border-gray-200">
                <div v-for="post in posts"
                     class="overflow-hidden col-span-12 sm:col-span-6 lg:col-span-3 text-gray-900 ">
                    <swiper
                        :pagination="{clickable: true}"
                        :space-between="50"
                        :modules="modules"
                        class="mySwiper swiper">
                        <swiper-slide v-for="img in post.images" :key="img.id" class="hover:grow">
                            <img :src="img.path" :alt="img.name">
                        </swiper-slide>
                    </swiper>
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
