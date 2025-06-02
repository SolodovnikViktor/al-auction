<script setup>
import {Link} from "@inertiajs/vue3";
import {router} from "@inertiajs/vue3";
import TableHeader from "@/Components/Main/TableHeader.vue";
import {reactive} from "vue";

const props = defineProps({
    posts: Object,
    orderingDirection: String,
    orderingValue: String,
    orderingDesc: String,
    orderingAsc: String,
    headerIndex: String,
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
    {id: 1, title: 'Бренд', value: 'brand_id', exception: false},
    {id: 2, title: 'Модель', value: 'model_id', exception: false},
    {id: 3, title: 'Год', value: 'year_release', exception: false},
    {id: 4, title: 'VIN', value: 'vin', exception: false},
    {id: 5, title: 'Цена', value: 'price', exception: false},
    {id: 6, title: 'Ставка', value: 'up_price', exception: false},
    {id: 7, title: 'Пробег', value: 'mileage', exception: false},
    {id: 8, title: 'Трансмиссия', value: 'transmission_id', exception: false},
    {id: 9, title: '', value: '', exception: true},
]

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
    router.get(route('admin-posts.index'),
        form,
        {
            preserveState: true,
        }
    )
}

function numberFilter(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

const show = (id) => {
    router.get(route('admin-post.show', id))
}
</script>

<template>
    <div class="overflow-x-auto">
        <div class="min-w-full inline-block align-middle">
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
                <tr v-for="post in posts.data" class="hover:bg-gray-100">
                    <td @click="show(post.id)"
                        class="px-4 py-4 cursor-pointer whitespace-nowrap text-sm font-medium text-gray-800">
                        {{ post.brand }}
                    </td>
                    <td @click="show(post.id)"
                        class="px-4 py-4 cursor-pointer whitespace-nowrap text-sm text-gray-800">
                        {{ post.model }}
                    </td>
                    <td @click="show(post.id)"
                        class="px-4 py-4 cursor-pointer whitespace-nowrap text-sm text-gray-800">
                        {{ post.year_release }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">{{ post.vin }}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">{{
                            numberFilter(post.price)
                        }}р
                    </td>
                    <td v-if="post.betce" class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ numberFilter(post.betce) }}р
                    </td>
                    <td v-else class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">Ставок нет</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">{{
                            numberFilter(post.mileage)
                        }}км
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">{{ post.transmission }}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <Link :href="route('admin-post.edit', post.id)"
                              class="bg-gray-200 rounded-md p-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">
                            Редактировать
                        </Link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
