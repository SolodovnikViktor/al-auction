<script setup>
import NavLink from "@/Components/NavLink.vue";

defineProps(['lot', 'user'])
</script>

<template>
    <div class="flex h-10 justify-between">
        <div class="flex space-x-3 lg:space-x-5 sm:-my-px">
            <NavLink
                :href="route('main-lots.index')"
                :active="route().current('main-lots.index')">
                Каталог
            </NavLink>
            <NavLink
                :href="route('main-bets.index')"
                :active="route().current('main-bets.index')">
                Мои ставки
            </NavLink>

            <template v-if="route().current('main-lots.search')">
                <NavLink
                    :href="route('main-lots.search')"
                    :active="route().current('main-lots.search')">
                    Поиск
                </NavLink>
            </template>
            <template v-if="route().current('main-lots.filter')">
                <NavLink
                    :href="route('main-lots.filter')"
                    :active="route().current('main-lots.filter')">
                    Фильтр
                </NavLink>
            </template>

            <template
                v-if="route().current('main-lot.show')">
                <NavLink
                    :href="route('main-lot.show',lot.id)"
                    :active="route().current('main-lot.show')">
                    {{ lot.brand }} {{ lot.model }}
                </NavLink>
            </template>
        </div>
    </div>
</template>
