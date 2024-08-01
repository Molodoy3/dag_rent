<script setup lang="ts">


import {Head, useForm} from "@inertiajs/vue3";
import { ref } from "vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import Title from "../Components/Title.vue";
import InputError from "../Components/InputError.vue";
import LabelInput from "../Components/LabelInput.vue";
import Button from "../Components/Button.vue";
import {route} from "../../../../vendor/tightenco/ziggy";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        //onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                //passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                //currentPasswordInput.value.focus();
            }
        },
    });
};

</script>

<template>
    <DefaultLayout>
        <Head title="Изменение пароля"></Head>
        <section class="update-password">
            <div class="update-password__container">
                <Title>Изменение пароля</Title>
                <form @submit.prevent="updatePassword" action="#" class="create__form">
                    <LabelInput for="current_password">Старый пароль</LabelInput>
                    <input class="input" type="password" id="current_password" v-model="form.current_password"
                           :class="{ 'error': form.errors.current_password }" />
                    <InputError v-if="form.errors.current_password">{{ form.errors.current_password }}</InputError>

                    <LabelInput for="password">Новый пароль</LabelInput>
                    <input class="input" type="password" id="password" v-model="form.password"
                           :class="{ 'error': form.errors.password }" />
                    <InputError v-if="form.errors.password">{{ form.errors.password }}</InputError>

                    <LabelInput for="password_confirmation">Повторите новый пароль</LabelInput>
                    <input class="input" type="password" id="password_confirmation" v-model="form.password_confirmation"
                           :class="{ 'error': form.errors.password_confirmation }" />
                    <InputError v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</InputError>

                    <Button type="submit" :disabled="form.processing"
                            :class="{ 'processing': form.processing }">Сохранить</Button>
                </form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
