<script setup>

import NavLink from "@/Components/NavLink.vue";
</script>

<template>
    <div class="flex h-10 justify-between">
        <div class="flex space-x-8 sm:-my-px sm:ms-10">
            <NavLink
                :href="route('admin-post.index')"
                :active="route().current('admin-post.index')">
                Список авто
            </NavLink>
            <NavLink
                :href="route('admin-post.create')"
                :active="route().current('admin-post.create')">
                Добавить авто
            </NavLink>
            <NavLink
                :href="route('contact')"
                :active="route().current('contact')">
                Список пользователей
            </NavLink>
        </div>
    </div>
</template>

<style scoped>

</style>
