<script setup>
import {Head} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/AdminNav.vue";
import {ref} from 'vue'
import PaginationBar from "@/Components/Main/PaginationBar.vue";
import ObjectNotFound from "@/Components/Main/ObjectNotFound.vue";
import AdminPostsFilter from "@/Components/Main/PostsIndex/AdminPostsFilter.vue";
import AdminPostsPhoto from "@/Components/Main/PostsIndex/AdminPostsPhoto.vue";
import AdminPostsTable from "@/Components/Main/PostsIndex/AdminPostsTable.vue";

const props = defineProps(
    {
        posts: Object,
        user: Object,
        formSearch: Object,
        formFilter: Object,
        formOrdering: Object,
        brands: Array,
        fuels: Array,
        wheels: Array,
        colors: Array,
        drives: Array,
        bodyTypes: Array,
        transmissions: Array,

    }
);
const catalog_view = ref(props.user.catalog_view)
const catalogView = (bool) => catalog_view.value = bool
</script>

<template>
    <Head title="Админ панель"/>
    <MainLayout :formSearch>
        <template #navigation>
            <AdminNav/>
        </template>
        <template #filters>
            <AdminPostsFilter @checkbox="catalogView"
                              :posts
                              :formFilter
                              :formOrdering
                              :formSearch
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
            <template v-if="!posts.data.length">
                <ObjectNotFound v-if="formSearch">
                    Поиск: "{{ formSearch.search }}" не дал результатов.
                </ObjectNotFound>
                <ObjectNotFound v-else>
                    Автомобили не добавлены.
                </ObjectNotFound>
            </template>
            <template v-else>
                <AdminPostsPhoto v-if="!catalog_view"
                                 :posts
                                 :formFilter
                                 :formOrdering
                                 :formSearch
                />
                <AdminPostsTable v-if="catalog_view"
                                 :posts
                                 :formFilter
                                 :formOrdering
                                 :formSearch
                />
            </template>
            <nav class="flex justify-center">
                <PaginationBar :links="posts.meta.links"/>
            </nav>
        </div>
    </MainLayout>
</template>

