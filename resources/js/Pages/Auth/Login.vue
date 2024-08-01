<script setup>

import DefaultLayout from "../Layouts/DefaultLayout.vue";
import {Head, router, useForm} from "@inertiajs/vue3";
import Title from "../Components/Title.vue";
//import Input from "../Components/Input.vue";
import LabelInput from "../Components/LabelInput.vue";
import Button from "../Components/Button.vue";
import InputError from "../Components/InputError.vue";


const form = useForm({
    email: '',
    password: ''
});
const submit = () => {
    form.post('login', {
        onFinish: () => form.reset('password'),
    });
};

</script>

<template>
    <DefaultLayout>
        <Head title="Авторизация"></Head>
        <section class="auth">
            <div class='auth__container'>
                <Title class="auth__title">Авторизация</Title>
                <form @submit.prevent="submit" action="#" class="auth__form">
                    <LabelInput for="email">Введите почту</LabelInput>
                    <input class="input" type="email" id="email" v-model="form.email" :class="{'error': form.errors.email}"/>

                    <InputError v-if="form.errors.email">{{ form.errors.email }}</InputError>
                    <LabelInput for="password">Введите пароль</LabelInput>
                    <input class="input" id="password" type="password" v-model="form.password" :class="{'error': form.errors.password}"/>
                    <InputError v-if="form.errors.password">{{ form.errors.password }}</InputError>

                    <Button>Войти</Button>
                </form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
