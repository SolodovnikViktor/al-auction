<script setup>
import {Link} from "@inertiajs/vue3";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    posts: Object,
})

function numberFilter(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

const show = (id) => {
    router.get(route('admin-post.show', id))
}
</script>

<template>
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 -mt-3 min-w-full inline-block align-middle">
                <div class="overflow-hidden rounded-t-lg">
                    <table class="min-w-full divide-y divide-gray-200 ">
                        <thead class=" ">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                Бренд
                            </th>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                Модель
                            </th>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                Год
                            </th>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">VIN
                            </th>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                Цена
                            </th>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                Ставка
                            </th>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                Пробег
                            </th>
                            <th scope="col" class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                Трансмиссия
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"></th>
                        </tr>
                        </thead>

                        <!--                        <Link :href="route('admin-post.show', post.id)">-->

                        <tbody class="divide-y divide-gray-200">
                        <tr v-for="post in posts.data" @click="show(post.id)" class="hover:bg-gray-100">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                {{ post.brand }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">{{ post.model }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">{{ post.year_release }}</td>
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
                                      class="bg-gray-200 rounded-md px-1 border-transparent border-b-2 transition focus:outline-none focus:border-indigo-400 hover:bg-gray-300">
                                    Редактировать
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
