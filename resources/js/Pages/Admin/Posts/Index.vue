<script setup>
import {Head, usePage} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/Admin/AdminNav.vue";
import {computed, ref} from 'vue'
import FiltersPosts from "@/Components/Main/FiltersPosts.vue";
import PaginationBar from "@/Components/Main/PaginationBar.vue";
import IndexPhoto from "@/Components/Main/PostsIndex/IndexPhoto.vue";
import IndexTable from "@/Components/Main/PostsIndex/IndexTable.vue";

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

console.log(props.posts);
</script>

<template>
    <Head title="Админ панель"/>
    <MainLayout>
        <template #adminNav>
            <AdminNav/>
        </template>
        <template #filters>
            <FiltersPosts @checkbox="catalogView"
                          :posts
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
            <div v-if="!posts.data[0]" class="text-center">
                Вы не добавили машины!
            </div>
            <template v-if="!catalog_view">
                <IndexPhoto :posts/>
            </template>
            <template v-if="catalog_view">
                <IndexTable :posts/>
            </template>
            <nav class="flex justify-center">
                <PaginationBar :links="posts.meta.links"/>
            </nav>
        </div>
    </MainLayout>
</template>

