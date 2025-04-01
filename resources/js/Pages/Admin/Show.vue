<script setup>
import IndexLayout from "@/Layouts/IndexLayout.vue";
import AdminNav from "@/Components/Admin/AdminNav.vue";
import {Head} from "@inertiajs/vue3";

const props = defineProps({
    post: Object
})
</script>

<template>
    <Head title="Карточка авто"/>

    <IndexLayout>
        <template #adminNav>
            <AdminNav :post/>
            <!--            <AdminNav/>-->
        </template>
        {{ post }}
    </IndexLayout>
</template>

<style scoped>

</style>
