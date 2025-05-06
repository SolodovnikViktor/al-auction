<script setup>
defineProps(['post'])
import NavLink from "@/Components/NavLink.vue";
</script>

<template>
    <div class="flex h-10 justify-between mx-3 lg:mx-5">
        <div class="flex space-x-3 lg:space-x-5 sm:-my-px">
            <NavLink
                :href="route('admin-post.index')"
                :active="route().current('admin-post.index')">
                Каталог
            </NavLink>
            <NavLink
                :href="route('admin-post.create')"
                :active="route().current('admin-post.create')">
                Добавить
            </NavLink>
            <NavLink
                :href="route('contact')"
                :active="route().current('contact')">
                Пользователи
            </NavLink>
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
