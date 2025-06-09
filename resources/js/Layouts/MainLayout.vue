<script setup>
import {computed, onBeforeMount, reactive, ref,} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link, router, usePage} from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
// const page = usePage()
// let userRole
// onBeforeMount(()=>{
//     if (page.props.auth.user){
//         userRole = computed(() => page.props.auth.user.role_id)
//     }
// })

// console.log(userRole)
// console.log(user.value.role.value === 'admin')
let adminActive = false;
if (route().current('admin-posts.index') || route().current('admin-post.create')
    || route().current('admin-post.show') || route().current('admin-post.edit')
    || route().current('admin-post.search') || route().current('admin-posts.filter')
    || route().current('admin-users.index') || route().current('admin-user.show')) {
    adminActive = true;
}

let form = reactive(
    {
        search: '',
    }
)

let search = ref();
let i = 1
onBeforeMount(() => {
    router.on('navigate', (event) => {
        i++
        if (route().current('admin-post.search') && i === 2) {
            form.search = event.detail.page.props.search
        }
    })
})

const getSearch = () => {
    if (form.search) {
        router.get(route("admin-post.search"),
            form,
            {
                preserveState: true,
            }
        );
    }
}
</script>

<template>
    <div class="min-h-screen bg-[#0085db12] sm:px-4">
        <div v-if="$page.props.message_form" class="alert">
            {{ $page.props.message_form }}
        </div>
        <div v-if="$page.props.flash.message" class="alert">
            {{ $page.props.flash.message }}
        </div>
        <div v-if="$page.props.flash.success" class="alert alert-success">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash.error" class="alert alert-danger">
            {{ $page.props.flash.error }}
        </div>

        <header
            class="max-w-screen-2xl sm:rounded-2xl shadow mb-4 mx-auto bg-white px-4 sm:px-5 fixed top-0 left-0 right-0 sm:left-4 sm:right-4 z-50">
            <div class="border-b border-gray-100">
                <div class="flex h-16 justify-between">
                    <nav class="flex">
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('home')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                            </Link>
                        </div>
                        <div
                            class="hidden *:text-lg space-x-4 lg:space-x-8 sm:-my-px mx-4 sm:flex">
                            <NavLink
                                :href="route('admin-posts.index')"
                                :active=adminActive>
                                Админ панель
                            </NavLink>
                            <NavLink
                                :href="route('home')"
                                :active="route().current('home')">
                                Аукцион
                            </NavLink>
                            <NavLink
                                :href="route('contact')"
                                :active="route().current('contact')">
                                Контакты
                            </NavLink>
                        </div>
                    </nav>
                    <div class="flex">
                        <form
                            @submit.prevent="getSearch"
                            class="inline-flex items-center relative">
                            <input type="search"
                                   id="post_search"
                                   autocomplete="search"
                                   v-model="form.search"
                                   class="block w-full p-1 pl-2 pr-10 text-sm text-gray-900 border border-gray-200 rounded-lg "
                                   placeholder="Поиск по VIN"/>
                            <button type="submit"
                                    class="absolute top-0 end-0 bottom-0 focus:outline-none px-3">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </button>
                        </form>
                        <div class="hidden sm:ms-2 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div v-if="$page.props.auth.user" class="relative">
                                <Dropdown align="right" width="40">
                                    <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                            {{ $page.props.auth.user.name }}
                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </span>
                                    </template>
                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')">
                                            Профиль
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button">
                                            Выйти
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                                    Вход
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                                    Регистрация
                                </Link>
                            </template>
                        </div>
                    </div>
                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown =!showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24">
                                <path
                                    :class="{hidden: showingNavigationDropdown,'inline-flex':!showingNavigationDropdown}"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"/>
                                <path
                                    :class="{hidden: !showingNavigationDropdown,'inline-flex':showingNavigationDropdown}"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Responsive Navigation Menu -->
                <div
                    :class="{block: showingNavigationDropdown,hidden: !showingNavigationDropdown}"
                    class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('admin-posts.index')"
                            :active="route().current('admin-posts.index')">
                            Админ панель
                        </ResponsiveNavLink>
                    </div>
                    <!-- Responsive Settings Options -->
                    <div v-if="$page.props.auth.user"
                         class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Профиль
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button">
                                Выйти
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </div>
            <template v-if="$slots.header">
                <nav class="p-4">
                    <slot name="header"/>
                </nav>
            </template>
            <template v-if="$slots.adminNav ">
                <slot name="adminNav"/>
            </template>
        </header>
        <div class="pt-[123px]"></div>
        <!-- Page Content -->
        <main>
            <template v-if="$slots.filters">
                <slot name="filters"/>
            </template>
            <slot/>
        </main>
    </div>
</template>
