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
        <div class="p-4 max-w-screen-2xl  mx-auto shadow sm:rounded-2xl bg-white">
            <p>
                Пользователь: {{user.surname}} {{user.name}} {{user.patronymic}}
            </p>
            <p>
                Телефон: {{user.phone}}
            </p>
            <p>
                Email: {{user.email}}
            </p>
            <p>
               Адрес: {{user.address}}
            </p>
            <p>
                Ставок: {{ user.count_bets}}
            </p>
        </div>
    </MainLayout>
</template>

