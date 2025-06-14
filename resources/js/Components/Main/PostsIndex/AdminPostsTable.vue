<script setup>
import {Link, router} from "@inertiajs/vue3";
import TableHeader from "@/Components/Main/TableHeader.vue";
import {reactive} from "vue";

const props = defineProps({
    posts: Object,
    formFilter: Object,
    formOrdering: Object,
    formSearch: Object,
})
let formOrdering = reactive({
    ordering_direction: '',
    ordering_value: '',
    ordering_desc: '',
    ordering_asc: '',
})
if (props.formOrdering) {
    formOrdering = reactive({
        ordering_direction: props.formOrdering.ordering_direction,
        ordering_value: props.formOrdering.ordering_value,
        ordering_desc: props.formOrdering.ordering_desc,
        ordering_asc: props.formOrdering.ordering_asc,
    })
}

const tableHeaders = [
    {title: 'Бренд', value: 'brand_id', exception: false},
    {title: 'Модель', value: 'model_id', exception: false},
    {title: 'Год', value: 'year_release', exception: false},
    {title: 'VIN', value: 'vin', exception: false},
    {title: 'Цена', value: 'price', exception: false},
    {title: 'Пробег', value: 'mileage', exception: false},
    {title: 'Трансмиссия', value: 'transmission_id', exception: false},
    {title: 'Ставка', value: 'up_price', exception: false},
    {title: '', value: '', exception: true},
]

const filterOn = (v) => {
    formOrdering.ordering_value = v
    if (formOrdering.ordering_desc === 'false') {
        formOrdering.ordering_direction = 'desc';
        formOrdering.ordering_desc = 'true';
        formOrdering.ordering_asc = 'false';
    } else {
        formOrdering.ordering_direction = 'asc';
        formOrdering.ordering_desc = 'false';
        formOrdering.ordering_asc = 'true';
    }
    if (route().current('admin-posts.filter')) {
        router.get(route('admin-posts.filter'),
            {formOrdering: formOrdering, formFilter: props.formFilter, formSearch: props.formSearch},
            {
                preserveState: true,
                preserveScroll: true
            }
        );
    }
    if (route().current('admin-posts.search')) {
        router.get(route('admin-posts.search'),
            {formOrdering: formOrdering, formFilter: props.formFilter, formSearch: props.formSearch},
            {
                preserveState: true,
                preserveScroll: true
            }
        )
    }
    if (route().current('admin-posts.index')) {
        router.get(route('admin-posts.index'),
            {formOrdering: formOrdering, formFilter: props.formFilter, formSearch: props.formSearch},
            {
                preserveState: true,
                preserveScroll: true
            }
        )
    }
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
                        :formOrdering
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
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ post.vin }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ numberFilter(post.price) }}р
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ numberFilter(post.mileage) }}км
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ post.transmission }}
                    </td>
                    <td v-if="post.betce" class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ numberFilter(post.betce) }}р
                    </td>
                    <td v-else class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        Ставок нет
                    </td>
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
