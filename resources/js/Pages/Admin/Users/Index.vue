<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/Admin/AdminNav.vue";
import Filters from "@/Components/Main/Filters.vue";

const props = defineProps({
    users: Object
})

const show = (id) => {
    // router.patch(route('admin-post.updateCatalogView', user.value.id), {
    router.get( route('admin-user.show', id))
}

console.log(props.users)
</script>

<template>
    <Head title="Клиенты"/>
    <MainLayout>
<!--        <div class="flex flex-col">-->
<!--            <div class="-m-1.5 overflow-x-auto">-->
<!--                <div class="p-1.5 min-w-full inline-block align-middle">-->
<!--                    <div class="overflow-hidden rounded-t-lg">-->
<!--                        <table class="min-w-full divide-y divide-gray-200 ">-->
<!--                            <thead class=" ">-->
<!--                            <tr>-->
<!--                                <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">-->
<!--                                    Бренд-->
<!--                                </th>-->
<!--                                <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">-->
<!--                                    Модель-->
<!--                                </th>-->
<!--                                <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">-->
<!--                                    Год-->
<!--                                </th>-->
<!--                                <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">VIN-->
<!--                                </th>-->
<!--                                <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">-->
<!--                                    Цена-->
<!--                                </th>-->
<!--                                <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">-->
<!--                                    Ставка-->
<!--                                </th>-->
<!--                                <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">-->
<!--                                    Пробег-->
<!--                                </th>-->
<!--                                <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">-->
<!--                                    Трансмиссия-->
<!--                                </th>-->
<!--                                <th scope="col"-->
<!--                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"></th>-->
<!--                            </tr>-->
<!--                            </thead>-->
<!--                            <tbody class="divide-y divide-gray-200">-->
<!--                            <tr v-for="user in users.data" @click="show(user.id)" class="hover:bg-gray-100">-->
<!--                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">-->
<!--                                    {{ user.name }}-->
<!--                                </td>-->
<!--                                <td class="px-4 py-4 whitespace-nowrap text-end text-sm font-medium">-->
<!--                                    &lt;!&ndash;                                <Link :href="route('admin-user.edit', user.id)"&ndash;&gt;-->
<!--                                    &lt;!&ndash;                                      class="bg-gray-200 rounded-md px-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">&ndash;&gt;-->
<!--                                    &lt;!&ndash;                                    Редактировать&ndash;&gt;-->
<!--                                    &lt;!&ndash;                                </Link>&ndash;&gt;-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            </tbody>-->
<!--                        </table>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    <MainLayout/>

</template>

<style scoped>

</style>
