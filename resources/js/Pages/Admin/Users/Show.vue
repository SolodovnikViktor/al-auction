<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/Admin/AdminNav.vue";
import {Head} from "@inertiajs/vue3";
import 'swiper/css/navigation';

const props = defineProps({
    user: Object
})
</script>

<template>
    <Head title="Пользователь"/>

    <MainLayout>
        <template #adminNav>
            <AdminNav :user/>
        </template>
        <div class="p-4 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
            <div class="*:text-base">
                <div class="flex mb-4">
                    <p>Пользователь:</p>
                    <p class="font-bold ml-2">{{ user.surname }} {{ user.name }} {{ user.patronymic }}</p>
                </div>
            </div>
            <div class="flex mb-4">
                <p>Телефон:</p>
                <p class="font-bold ml-2">{{ user.phone }}</p>
            </div>
            <div class="flex mb-4">
                <p>Email:</p>
                <p class="font-bold ml-2">{{ user.email }}</p>
            </div>
            <div class="flex mb-4">
                <p>Адрес:</p>
                <p class="font-bold ml-2">{{ user.address }}</p>
            </div>
            <div class="flex mb-4">
                <p>Ставок:</p>
                <p class="font-bold ml-2">{{ user.count_bets }}</p>
            </div>
            <div class="flex mb-4">
                <p>Роль:</p>
                <p class="font-bold ml-2">{{ user.role.title }}</p>
            </div>

        </div>
    </MainLayout>
</template>

