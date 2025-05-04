<script setup>
import {Head} from '@inertiajs/vue3';
import IndexLayout from "@/Layouts/IndexLayout.vue";
import Header from "@/Components/Main/Header.vue";

const title = 'Главная';
</script>

<template>
    <Head :title='title'/>
    <IndexLayout>
        <template #header>
            <Header :title="title"/>
        </template>
        <div class="relative w-full p-4 lg:p-6 shadow sm:rounded-2xl bg-white">
            <div
                class="overflow-hidden grid grid-cols-12 gap-4 pb-4 mb-4 border-b border-gray-200">
                <div class="col-span-12  sm:col-span-3 text-gray-900 ">
                    <a href="#">
                        <div class="min-h-[300px] hover:grow hover:shadow-lg bg-lime-500"></div>
                    </a>
                    Фото машин
                </div>
            </div>
            <nav>
                Навигация
            </nav>
        </div>
    </IndexLayout>
</template>

<style scoped>
.hover\:grow {
    transition: all 0.3s;
    transform: scale(1);
}

.hover\:grow:hover {
    transform: scale(1.04);
}

</style>
