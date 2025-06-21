<script setup>
import {Link, router} from "@inertiajs/vue3";
import TableHeader from "@/Components/Main/TableHeader.vue";
import {reactive} from "vue";

const props = defineProps({
    lots: Object,
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
    {title: 'Ставка', value: 'bet', exception: false},
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
    if (route().current('admin-lots.filter')) {
        router.get(route('admin-lots.filter'),
            {formOrdering: formOrdering, formFilter: props.formFilter, formSearch: props.formSearch},
            {
                preserveState: true,
                preserveScroll: true
            }
        );
    }
    if (route().current('admin-lots.search')) {
        router.get(route('admin-lots.search'),
            {formOrdering: formOrdering, formFilter: props.formFilter, formSearch: props.formSearch},
            {
                preserveState: true,
                preserveScroll: true
            }
        )
    }
    if (route().current('admin-lots.index')) {
        router.get(route('admin-lots.index'),
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
    router.get(route('admin-lot.show', id))
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
                <tr v-for="lot in lots.data" class="hover:bg-gray-100">
                    <td @click="show(lot.id)"
                        class="px-4 py-4 cursor-pointer whitespace-nowrap text-sm font-medium text-gray-800">
                        {{ lot.brand }}
                    </td>
                    <td @click="show(lot.id)"
                        class="px-4 py-4 cursor-pointer whitespace-nowrap text-sm text-gray-800">
                        {{ lot.model }}
                    </td>
                    <td @click="show(lot.id)"
                        class="px-4 py-4 cursor-pointer whitespace-nowrap text-sm text-gray-800">
                        {{ lot.year_release }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ lot.vin }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ numberFilter(lot.price) }}р
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ numberFilter(lot.mileage) }}км
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ lot.transmission }}
                    </td>
                    <td v-if="lot.betce" class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        {{ numberFilter(lot.betce) }}р
                    </td>
                    <td v-else class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">
                        Ставок нет
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <Link :href="route('admin-lot.edit', lot.id)"
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
