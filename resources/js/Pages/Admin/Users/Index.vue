<script setup>
import {Head, router} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/Admin/AdminNav.vue";
import PaginationBar from "@/Components/Main/PaginationBar.vue";
import axios from "axios";
import {ref} from "vue";
import TableHeader from "@/Components/Main/TableHeader.vue";


const props = defineProps({
    users: Object,
    roles: Array,
})
const tableHeaders = [
    {id: 1, title: 'Имя', value: 'name'},
    {id: 2, title: 'Фамилия', value: 'surname'},
    {id: 3, title: 'Телефон', value: 'phone'},
    {id: 4, title: 'Email', value: 'email'},
    {id: 5, title: 'Ставок', value: 'bet'},
    {id: 6, title: 'Роль', value: 'role'},
]
let orderingDesc = ref(false);
let orderingAsc = ref(false)
let indexHeader = ref(null)

const patchRole = (userId, roleId) => {
    axios.patch(`/admin/update-role/${userId}`, {role_id: roleId})
        .catch((error) => {
            console.log(error);
        });
}
const show = (id) => {
    // router.patch(route('admin-post.updateCatalogView', user.value.id), {
    router.get(route('admin-user.show', id))
}

const filterOn = (i, v) => {
    console.log(i)
    console.log(v)
    indexHeader.value = i
    if (!orderingDesc.value) {
        orderingDesc.value = true;
        orderingAsc.value = false;
    } else {
        orderingDesc.value = false;
        orderingAsc.value = true;
    }
    axios.get('/admin/users/ordering', {index_header: indexHeader.value, ordering_desc: ordering.value}).then((res) => {
        router.reload({only: ['users']})
    })
}

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
            <div class="flex lg:-mt-1 flex-col">
                <div class="-m-1.5  overflow-x-auto">
                    <div class="p-1.5 pt-0  min-w-full inline-block align-middle">
                        <div class="overflow-hidden rounded-t-lg">
                            <table class="min-w-full divide-y divide-gray-200 ">
                                <thead>
                                <tr>
                                    <TableHeader
                                        :tableHeaders
                                        :orderingDesc
                                        :orderingAsc
                                        :indexHeader
                                        @filter-on="filterOn"
                                    />
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                <tr v-for="user in users.data" class="hover:bg-gray-100">
                                    <td @click="show(user.id)"
                                        class="px-4 py-4 cursor-pointer whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ user.name }}
                                    </td>
                                    <td @click="show(user.id)"
                                        class="px-4 py-4 cursor-pointer whitespace-nowrap text-sm font-medium text-gray-800">
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
                                    <td class="px-3.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800">
                                        <select @change="patchRole(user.id, user.role.id)"
                                                class="w-min-[113px] py-1 cursor-pointer rounded-md border-gray-300 shadow-sm focus:ring-blue-500
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
