<script setup>
import {Head, router} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import AdminNav from "@/Components/Main/Admin/AdminNav.vue";
import PaginationBar from "@/Components/Main/PaginationBar.vue";
import axios from "axios";
import {reactive} from "vue";
import TableHeader from "@/Components/Main/TableHeader.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";

const props = defineProps({
    users: Object,
    roles: Array,
    orderingDirection: String,
    orderingValue: String,
    orderingDesc: String,
    orderingAsc: String,
    headerIndex: String,
    search: String,
})

let form = reactive({
    ordering_direction: props.orderingDirection,
    ordering_value: props.orderingValue,
    ordering_desc: props.orderingDesc,
    ordering_asc: props.orderingAsc,
    header_index: props.headerIndex,
    search: props.search,
})

const tableHeaders = [
    {id: 1, title: 'Имя', value: 'name', exception: false},
    {id: 2, title: 'Фамилия', value: 'surname', exception: false},
    {id: 3, title: 'Телефон', value: 'phone', exception: true},
    {id: 4, title: 'Email', value: 'email', exception: false},
    {id: 5, title: 'Дата', value: 'created_at', exception: false},
    {id: 6, title: 'Ставок', value: 'count_bets', exception: false},
    {id: 7, title: 'Роль', value: 'role_id', exception: false},
]

const patchRole = (userId, roleId) => {
    axios.patch(`/admin/update-role/${userId}`, {role_id: roleId})
        .catch((error) => {
            console.log(error);
        });
}
const show = (id) => {
    router.get(route('admin-user.show', id))
}

const filterOn = (i, v) => {
    form.header_index = i.toString()
    form.ordering_value = v
    if (form.ordering_desc === 'false') {
        form.ordering_direction = 'desc';
        form.ordering_desc = 'true';
        form.ordering_asc = 'false';
    } else {
        form.ordering_direction = 'asc';
        form.ordering_desc = 'false';
        form.ordering_asc = 'true';
    }
    router.get(route('admin-users.index'),
        form,
        {
            preserveState: true,
        }
    )
}
const getSearch = () => {
    if (form.search) {
        router.get(route("admin-users.index",
            form,
            {
                preserveState: true,
            })
        );
    }
}

const cleanForm = () => {
    router.get(route("admin-users.index"))
}

</script>

<template>
    <Head title="Клиенты"/>
    <MainLayout>
        <template #adminNav>
            <AdminNav/>
        </template>
        <div class="p-2 lg:p-4 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <form
                        @submit.prevent="getSearch"
                        class="inline-flex w-1/3 m-1 items-center ">
                        <div class="relative w-full">
                            <input type="search"
                                   id="users_search"
                                   autocomplete="search"
                                   v-model="form.search"
                                   class="block w-full p-1 pl-2 pr-10 text-sm text-gray-800 border border-gray-200 rounded-lg "
                                   placeholder="Поиск"/>
                            <button type="submit"
                                    class="absolute top-0 end-0 bottom-0 focus:outline-none px-3">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </button>
                        </div>
                        <SecondaryButton class="py-1.5 ml-3" @click="cleanForm">
                            x
                        </SecondaryButton>
                    </form>

                    <div class="overflow-hidden rounded-t-lg">
                        <table class="min-w-full divide-y divide-gray-200 ">
                            <thead>
                            <tr>
                                <TableHeader
                                    :tableHeaders
                                    :orderingDesc="form.ordering_desc"
                                    :orderingAsc="form.ordering_asc"
                                    :headerIndex="form.header_index"
                                    @filter-on="filterOn"
                                />
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-100">
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
                                    {{ user.created_at }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                    {{ user.count_bets }}
                                </td>
                                <td class="px-3.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800">
                                    <select @change="patchRole(user.id, user.role.id)"
                                            class="w-min-[113px] py-1 cursor-pointer rounded-md border-gray-300 shadow-sm focus:ring-blue-500
                                            focus:border-blue-500"
                                            v-model="user.role.id"
                                            :id="`role` + user.id">
                                        <option v-for="role in roles" :key="role.id" :value="role.id">
                                            {{ role.title }}
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <nav class="flex justify-center">
                <PaginationBar :links="users.meta.links"/>
            </nav>
        </div>
    </MainLayout>
</template>
