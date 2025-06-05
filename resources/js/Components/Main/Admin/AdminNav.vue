<script setup>
import {ref} from "vue";

defineProps(['post', 'user'])
import NavLink from "@/Components/NavLink.vue";
import {router} from '@inertiajs/vue3';

let search_is = ref(false);
let filter_is = ref(false);

router.on('navigate', (event) => {

    if (route().current('admin-post.search')) {
        search_is.value = true;
        filter_is.value = false;
    }
    if (route().current('admin-posts.filter')) {
        filter_is.value = true;
        search_is.value = false;
    }
})
</script>

<template>
    <div class="flex h-10 justify-between">
        <div class="flex space-x-3 lg:space-x-5 sm:-my-px">
            <NavLink
                :href="route('admin-posts.index')"
                :active="route().current('admin-posts.index')">
                Каталог
            </NavLink>
            <NavLink
                :href="route('admin-post.create')"
                :active="route().current('admin-post.create')">
                Добавить
            </NavLink>
            <NavLink
                :href="route('admin-users.index')"
                :active="route().current('admin-users.index')">
                Пользователи
            </NavLink>
            <NavLink v-if="route().current('admin-user.show')"
                     :href="route('admin-users.index')"
                     :active="route().current('admin-user.show')">
                {{ user.name }} {{ user.surname }}
            </NavLink>
            <template v-if="search_is">
                <NavLink
                    :href="route('admin-post.search')"
                    :active="search_is">
                    Поиск
                </NavLink>
            </template>
            <template v-if="filter_is">
                <NavLink
                    :href="route('admin-posts.filter')"
                    :active="filter_is">
                    Фильтр
                </NavLink>
            </template>
            <template
                v-if="route().current('admin-post.edit') || route().current('admin-post.show')">
                <NavLink
                    :href="route('admin-post.show',post.id)"
                    :active="route().current('admin-post.show')">
                    Карточка
                </NavLink>
                <NavLink
                    :href="route('admin-post.edit',post.id)"
                    :active="route().current('admin-post.edit')">
                    Редактировать
                </NavLink>
            </template>
        </div>
    </div>
</template>
