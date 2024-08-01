<script setup lang="ts">

import Button from "../Components/Button.vue";
import {Head, useForm, Link} from "@inertiajs/vue3";
import InputError from "../Components/InputError.vue";
import Title from "../Components/Title.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import LabelInput from "../Components/LabelInput.vue";
import {onMounted, PropType} from "vue";
import {initCustomSelect} from "../../modules/customSelect";

const props = defineProps({
    //"user": Object as PropType<UserData>,
    "errors": Object,
    "roles": Object
});

const form = useForm({
    name: '',
    email: '',
    role_id: null,
    password: '',
});

function onRoleChange(id) {
    form.role_id = id;
}
onMounted(() => {
    initCustomSelect(false);
});
function submit() {
    form.post(route('user.storeNew', {'user': form}));
}
</script>

<template>
    <DefaultLayout>
        <Head title="Добавление нового пользователя"></Head>
        <section class="new-user">
            <div class="new-user__container">
                <Title>Добавление нового пользователя</Title>
                <form @submit.prevent="submit" action="#" class="create__form">
                    <LabelInput for="name">Имя</LabelInput>
                    <input class="input" id="name" v-model="form.name"
                           :class="{ 'error': props.errors.name }" />
                    <InputError v-if="props.errors.name">{{ props.errors.name }}</InputError>

                    <LabelInput for="role_id">Роль</LabelInput>
                    <div class="form__custom-select-body" data-custom-select="Выберите роль">
                        <select v-model="form.role_id" id="role_id" class="form__select">
                            <option :selected="role.id == form.role_id" v-for="role in roles" :value='role.id'>{{ role.name }}
                            </option>
                        </select>
                        <div class="form__custom-select custom-select-form input" :class="{ 'error': props.errors.role_id }">
                            <div data-custom-select-field class="custom-select-form__field"
                                 ></div>
                            <ul data-custom-select-options class="custom-select-form__options">
                                <li @click="onRoleChange(role.id)" v-for="role in roles"
                                    :data-custom-select-option="role.id">{{ role.name }}</li>
                            </ul>
                        </div>
                    </div>
                    <InputError v-if="props.errors.role_id">{{ props.errors.role_id }}</InputError>

                    <LabelInput for="email">Почта</LabelInput>
                    <input class="input" id="email" v-model="form.email"
                           :class="{ 'error': props.errors.email }" />
                    <InputError v-if="props.errors.email">{{ props.errors.email }}</InputError>

                    <LabelInput for="password">Пароль</LabelInput>
                    <input class="input" type="password" id="password" v-model="form.password"
                           :class="{ 'error': props.errors.password }" />
                    <InputError v-if="props.errors.password">{{ props.errors.password }}</InputError>

                    <Button type="submit" :disabled="form.processing"
                            :class="{ 'processing': form.processing }">Добавить</Button>
                </form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
