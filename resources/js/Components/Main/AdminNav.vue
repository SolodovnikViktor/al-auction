<script setup>
import NavLink from "@/Components/NavLink.vue";

defineProps(['post', 'user'])
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


            <template v-if="route().current('admin-posts.search')">
                <NavLink
                    :href="route('admin-posts.search')"
                    :active="route().current('admin-posts.search')">
                    Поиск
                </NavLink>
            </template>

            <template v-if="route().current('admin-posts.filter')">
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
                    {{ post.brand }} {{ post.model }}
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
