<script setup>
import {Head, router} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/Admin/AdminNav.vue";
import PaginationBar from "@/Components/Main/PaginationBar.vue";
import axios from "axios";


const props = defineProps({
    users: Object,
    roles: Array,
})

const patchRole = (userId, roleId) => {
    console.log(userId);
    console.log(roleId);
    axios.patch(`/admin/update-role/${userId}`, {role_id: roleId})
        .catch((error) => {
            console.log(error);
        });
}

const show = (id) => {
    // router.patch(route('admin-post.updateCatalogView', user.value.id), {
    router.get(route('admin-user.show', id))
}

console.log(props.users)
console.log(props.roles)
</script>

<template>
    <Head title="Клиенты"/>
    <MainLayout>
        <template #adminNav>
            <AdminNav/>
        </template>
        <!--        <template #filters>-->
        <!--                    <FiltersUsers-->
        <!--                :users-->
        <!--            />-->
        <!--        </template>-->
        <div class="p-2 lg:p-4 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="overflow-hidden rounded-t-lg">
                            <table class="min-w-full divide-y divide-gray-200 ">
                                <thead class=" ">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                        Имя
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                        Фамилия
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                        Телефон
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">Email
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">Ставок
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">роль
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                <tr v-for="user in users.data" class="hover:bg-gray-100">
                                    <td @click="show(user.id)"
                                        class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ user.name }}
                                    </td>
                                    <td @click="show(user.id)"
                                        class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ user.surname }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ user.phone }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ user.bet.length }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        <select @change="patchRole(user.id, user.role.id)"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500
                                            focus:border-blue-500"
                                                v-model="user.role.id"
                                                id="role">
                                            <option v-for="role in roles" :key="user.id" :value="role.id">
                                                {{ role.title }}
                                            </option>
                                        </select>
                                    </td>

                                    <!--                                    <td class="px-4 py-4 whitespace-nowrap text-end text-sm font-medium">-->
                                    <!--                                <Link :href="route('admin-user.edit', user.id)"-->
                                    <!--                                      class="bg-gray-200 rounded-md px-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">-->
                                    <!--                                    Редактировать-->
                                    <!--                                </Link>-->
                                    <!--                                    </td>-->
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="flex justify-center">
                <PaginationBar :links="users.meta.links"/>
            </nav>
        </div>
    </MainLayout>

</template>

<style scoped>

</style>
