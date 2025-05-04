<script setup>
defineProps(['post'])
import NavLink from "@/Components/NavLink.vue";
</script>

<template>
    <div class="flex h-10 justify-between">
        <div class="flex space-x-3 lg:space-x-5 sm:-my-px mx-3 lg:mx-5">
            <NavLink
                :href="route('admin-post.indexPhoto')"
                :active="route().current('admin-post.indexPhoto')">
                С фото
            </NavLink>
            <template
                v-if="route().current('admin-post.indexPhoto')">
                <form class="max-w-md inline-flex items-center mx-auto">
                    <div class="relative">
                        <input type="search" id="default-search"
                               class="block w-full p-1 pr-10 text-sm text-gray-900 border border-gray-200 rounded-lg "
                               placeholder="Поиск" required/>
                        <button type="submit"
                                class="absolute top-0 end-0 bottom-0 focus:outline-none px-3">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </template>
            <NavLink
                :href="route('admin-post.indexList')"
                :active="route().current('admin-post.indexList')">
                Списком
            </NavLink>
            <template
                v-if="route().current('admin-post.indexList')">
                <div>поиск список</div>
            </template>
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
