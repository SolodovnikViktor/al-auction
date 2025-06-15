<script setup>
import {Head} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {ref} from 'vue'
import MainPostsFilter from "@/Components/Main/PostsIndex/MainPostsFilter.vue";
import PaginationBar from "@/Components/Main/PaginationBar.vue";
import MainPostsPhoto from "@/Components/Main/PostsIndex/MainPostsPhoto.vue";
import MainPostsTable from "@/Components/Main/PostsIndex/MainPostsTable.vue";
import ObjectNotFound from "@/Components/Main/ObjectNotFound.vue";
import MainNav from "@/Components/Main/MainNav.vue";

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
        search: String,
    }
);
const catalog_view = ref(props.user.catalog_view)
const catalogView = (bool) => catalog_view.value = bool
</script>

<template>
    <Head title="Каталог"/>
    <MainLayout :formSearch>
        <template #navigation>
            <MainNav/>
        </template>
        <template #filters>
            <MainPostsFilter @checkbox="catalogView"
                             :posts
                             :formFilter
                             :formOrdering
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
                <ObjectNotFound v-if="search">
                    Поиск: "{{ search }}" не дал результатов.
                </ObjectNotFound>
                <ObjectNotFound v-else>
                    Автомобили не добавлены.
                </ObjectNotFound>
            </template>
            <template v-else>
                <MainPostsPhoto v-if="!catalog_view"
                                :posts
                                :formFilter
                                :formOrdering
                />
                <MainPostsTable v-if="catalog_view"
                                :posts
                                :formFilter
                                :formOrdering
                />
            </template>
            <nav class="flex justify-center">
                <PaginationBar :links="posts.meta.links"/>
            </nav>
        </div>
    </MainLayout>
</template>

