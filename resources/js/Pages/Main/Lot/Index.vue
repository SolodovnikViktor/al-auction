<script setup>
import {Head} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {ref} from 'vue'
import MainLotsFilter from "@/Components/Main/PostsIndex/MainLotsFilter.vue";
import PaginationBar from "@/Components/Main/PaginationBar.vue";
import MainLotsPhoto from "@/Components/Main/PostsIndex/MainLotsPhoto.vue";
import MainLotsTable from "@/Components/Main/PostsIndex/MainLotsTable.vue";
import ObjectNotFound from "@/Components/Main/ObjectNotFound.vue";
import MainNav from "@/Components/Main/MainNav.vue";

const props = defineProps(
    {
        lots: Object,
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
            <MainLotsFilter @checkbox="catalogView"
                            :lots
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
            <template v-if="!lots.data.length">
                <ObjectNotFound v-if="search">
                    Поиск: "{{ search }}" не дал результатов.
                </ObjectNotFound>
                <ObjectNotFound v-else>
                    Автомобили не добавлены.
                </ObjectNotFound>
            </template>
            <template v-else>
                <MainLotsPhoto v-if="!catalog_view"
                               :lots
                               :formFilter
                               :formOrdering
                               :formSearch
                />
                <MainLotsTable v-if="catalog_view"
                               :lots
                               :formFilter
                               :formOrdering
                               :formSearch
                />
            </template>
            <nav class="flex justify-center">
                <PaginationBar :links="lots.meta.links"/>
            </nav>
        </div>
    </MainLayout>
</template>

