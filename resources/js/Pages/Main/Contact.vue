<script setup>
import {Head} from "@inertiajs/vue3";
import IndexLayout from "@/Layouts/IndexLayout.vue";

</script>

<template>
    <Head title="Контакты"/>
    <IndexLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl mr-5 font-semibold leading-tight text-gray-800">
                    Контакты
                </h2>
                <p>+7 (958) 555-32-36</p>
            </div>
        </template>
        <div class="py-6">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Контакты фирмы
                    </div>
                </div>
            </div>
        </div>
    </IndexLayout>
</template>
