<script setup>
import {ref} from "vue";
import NavLink from "@/Components/NavLink.vue";
import {router} from '@inertiajs/vue3';

defineProps(['post', 'user'])

let search_is = ref(false);
let filter_is = ref(false);

router.on('navigate', (event) => {

    if (route().current('main-post.search')) {
        search_is.value = true;
        filter_is.value = false;
    }
    if (route().current('main-posts.filter')) {
        filter_is.value = true;
        search_is.value = false;
    }
})
</script>

<template>
    <div class="flex h-10 justify-between">
        <div class="flex space-x-3 lg:space-x-5 sm:-my-px">
            <NavLink
                :href="route('main-posts.index')"
                :active="route().current('main-posts.index')">
                Каталог
            </NavLink>

            <template v-if="route().current('main-posts.search')">
                <NavLink
                    :href="route('main-posts.search')"
                    :active="route().current('main-posts.search')">
                    Поиск
                </NavLink>
            </template>
            <template v-if="route().current('main-posts.filter')">
                <NavLink
                    :href="route('main-posts.filter')"
                    :active="route().current('main-posts.filter')">
                    Фильтр
                </NavLink>
            </template>

            <template
                v-if="route().current('main-post.show')">
                <NavLink
                    :href="route('main-post.show',post.id)"
                    :active="route().current('main-post.show')">
                    {{ post.brand }} {{ post.model }}
                </NavLink>
            </template>
        </div>
    </div>
</template>
