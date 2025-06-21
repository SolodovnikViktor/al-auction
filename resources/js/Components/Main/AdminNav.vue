<script setup>
import NavLink from "@/Components/NavLink.vue";

defineProps(['lot', 'user'])
</script>

<template>
    <div class="flex h-10 justify-between">
        <div class="flex space-x-3 lg:space-x-5 sm:-my-px">
            <NavLink
                :href="route('admin-lots.index')"
                :active="route().current('admin-lots.index')">
                Каталог
            </NavLink>
            <NavLink
                :href="route('admin-lot.create')"
                :active="route().current('admin-lot.create')">
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


            <template v-if="route().current('admin-lots.search')">
                <NavLink
                    :href="route('admin-lots.search')"
                    :active="route().current('admin-lots.search')">
                    Поиск
                </NavLink>
            </template>

            <template v-if="route().current('admin-lots.filter')">
                <NavLink
                    :href="route('admin-lots.filter')"
                    :active="filter_is">
                    Фильтр
                </NavLink>
            </template>


            <template
                v-if="route().current('admin-lot.edit') || route().current('admin-lot.show')">
                <NavLink
                    :href="route('admin-lot.show',lot.id)"
                    :active="route().current('admin-lot.show')">
                    {{ lot.brand }} {{ lot.model }}
                </NavLink>
                <NavLink
                    :href="route('admin-lot.edit',lot.id)"
                    :active="route().current('admin-lot.edit')">
                    Редактировать
                </NavLink>
            </template>
        </div>
    </div>
</template>
