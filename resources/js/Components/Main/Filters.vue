<script setup>
import {usePage} from "@inertiajs/vue3";
import {ref, computed} from "vue";

const props = defineProps({});
const emit = defineEmits(['checkbox'])
const page = usePage()
const user = computed(() => page.props.auth.user)
// const user =  page.props.auth.user

let toggle = ref();
toggle.value = user.value.catalog_view;

function updateCatalogView() {
    emit('checkbox', toggle.value);
    axios.patch(`/update-catalog-view/${user.value.id}`, {catalog_view: toggle.value})
        .catch((error) => {
            console.log(error);
        });
    // router.patch(route('admin-post.updateCatalogView', user.value.id), {
    //     catalog_view: toggle.value
    // })
}
</script>

<template>
    <div class="py-2 px-4 sm:px-5 mb-4 max-w-screen-2xl mx-auto shadow sm:rounded-2xl bg-white">
        <div class="flex items-center">
            <p v-if="toggle" class="mr-3">Фото</p>
            <p v-else class="mr-3 font-medium">Фото</p>
            <label class="relative mr-3 inline-flex items-center cursor-pointer">
                <input
                    @change="updateCatalogView"
                    type="checkbox"
                    v-model="toggle"
                    :true-value="1"
                    :false-value="0"
                    class="sr-only peer">
                <div
                    class="w-9 h-5 bg-gray-200 hover:bg-gray-300 peer-focus:outline-0 peer-focus:ring-transparent rounded-full peer transition-all ease-in-out duration-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all">
                </div>
            </label>
            <p v-if="toggle" class="font-medium">Список</p>
            <p v-else>Список</p>
        </div>
    </div>
</template>

<style scoped>

</style>
