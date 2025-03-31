<script setup>
import {ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link} from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

let adminActive = false;
if (route().current('admin-post.index') || route().current('admin-post.create')) {
    adminActive = true;
}
</script>

<template>
    <div class="min-h-screen bg-[#0085db12] sm:p-4">
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
        <nav class="border-b mb-4 sm:rounded-2xl shadow max-w-screen-2xl mx-auto border-gray-100 bg-white">
            <!-- Primary Navigation Menu -->
            <div class="px-4 lg:px-6">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('home')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink
                                :href="route('admin-post.index')"
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
                    </div>

                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <!-- Settings Dropdown -->
                        <div v-if="$page.props.auth.user" class="relative ms-3">
                            <Dropdown align="right" width="48">
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
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
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
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{block: showingNavigationDropdown,hidden: !showingNavigationDropdown}"
                class="sm:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    <ResponsiveNavLink
                        :href="route('admin-post.index')"
                        :active="route().current('admin-post.index')">
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
        </nav>

        <!-- Page Heading -->
        <header
            v-if="$slots.header">
            <div class="mb-4 p-4 shadow max-w-screen-2xl mx-auto sm:rounded-2xl bg-white">
                <slot name="header"/>
            </div>
        </header>

        <header v-if="$slots.adminNav">
            <nav class="border-b mb-4 sm:rounded-2xl shadow max-w-screen-2xl mx-auto border-gray-100 bg-white">
                <slot name="adminNav"/>
            </nav>
        </header>

        <!-- Page Content -->
        <main>
            <slot/>
        </main>
    </div>
</template>
