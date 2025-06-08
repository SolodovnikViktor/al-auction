<script setup>
import {Head, usePage} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/Admin/AdminNav.vue";
import {computed, ref} from 'vue'
import PostsFilter from "@/Components/Main/PostsIndex/PostsFilter.vue";
import PaginationBar from "@/Components/Main/PaginationBar.vue";
import IndexPhoto from "@/Components/Main/PostsIndex/IndexPhoto.vue";
import IndexTable from "@/Components/Main/PostsIndex/IndexTable.vue";
import ObjectNotFound from "@/Components/Main/ObjectNotFound.vue";

const page = usePage()
const user = computed(() => page.props.auth.user)
const catalog_view = ref(user.value.catalog_view)
const catalogView = (bool) => catalog_view.value = bool

const props = defineProps(
    {
        posts: Object,
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
</script>

<template>
    <Head title="Админ панель"/>
    <MainLayout>
        <template #adminNav>
            <AdminNav/>
        </template>
        <template #filters>
            <PostsFilter @checkbox="catalogView"
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
                <IndexPhoto v-if="!catalog_view"
                            :posts
                            :formFilter
                            :formOrdering
                />
                <IndexTable v-if="catalog_view"
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

