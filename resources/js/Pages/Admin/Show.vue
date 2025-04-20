<script setup>
import IndexLayout from "@/Layouts/IndexLayout.vue";
import AdminNav from "@/Components/Admin/AdminNav.vue";
import {Head} from "@inertiajs/vue3";

const props = defineProps({
    // post: Object,
    postRes: Object
})
let post = props.postRes.data
</script>

<template>
    <Head title="Карточка авто"/>

    <IndexLayout>
        <template #adminNav>
            <AdminNav :post/>
        </template>
        <div class="w-full p-2 lg:p-6 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
            {{ post }}
        </div>
    </IndexLayout>
</template>

<style scoped>

</style>
