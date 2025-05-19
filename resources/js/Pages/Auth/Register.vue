<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';

const form = useForm({
    name: '',
    surname: '',
    patronymic: '',
    phone: '',
    address: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register"/>

        <form @submit.prevent="submit">
            <div class="flex items-center justify-between">
                <InputLabel for="name" value="Имя" class="mr-4"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-2/3"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div class="flex items-center justify-between">
                <InputLabel for="surname" value="Фамилия" class="mr-4"/>

                <TextInput
                    id="surname"
                    type="text"
                    class="mt-1 block w-2/3"
                    v-model="form.surname"
                    required
                    autofocus
                    autocomplete="surname"
                />

                <InputError class="mt-2" :message="form.errors.surname"/>
            </div>

            <div class="flex items-center justify-between">
                <InputLabel for="patronymic" value="Отчество" class="mr-4"/>

                <TextInput
                    id="patronymic"
                    type="text"
                    class="mt-1 block w-2/3"
                    v-model="form.patronymic"
                    required
                    autofocus
                    autocomplete="patronymic"
                />

                <InputError class="mt-2" :message="form.errors.patronymic"/>
            </div>

            <div class="flex items-center justify-between">
                <InputLabel for="phone" value="Телефон" class="mr-4"/>

                <TextInput
                    id="phone"
                    type="tel"
                    class="mt-1 block w-2/3"
                    v-model="form.phone"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.phone"/>
            </div>

            <div class="flex items-center justify-between">
                <InputLabel for="address" value="Адрес" class="mr-4"/>

                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-2/3"
                    v-model="form.address"
                    required
                    autofocus
                    autocomplete="address"
                />

                <InputError class="mt-2" :message="form.errors.address"/>
            </div>

            <div class="flex items-center justify-between">
                <InputLabel for="email" value="Email" class="mr-4"/>

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-2/3"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div class="flex items-center justify-between">
                <InputLabel for="password" value="Пароль" class="mr-4"/>

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-2/3"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div class="flex items-center justify-between">
                <InputLabel
                    for="password_confirmation"
                    value="Повторите пароль"
                    class="mr-4 sm:mr-0 max-w-[75px]"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-2/3"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Уже зарегистрирован?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Регистрация
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
