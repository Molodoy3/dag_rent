<script setup lang="ts">

import DefaultLayout from "../Layouts/DefaultLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import Title from "../Components/Title.vue";
import {PropType} from "vue";
import UserData = App.Data.UserData;
import Button from "../Components/Button.vue";
import LabelInput from "../Components/LabelInput.vue";

const props = defineProps({
   "user": Object as PropType<UserData>,
    "isPushSubscription": Boolean
});

const form = useForm({});
function logout() {
    form.post(route('user.logout'));
}

</script>

<template>
    <DefaultLayout>
        <Head :title="'Профиль ' + user.name"></Head>
            <section class="profile">
                <div class="profile__container">
                    <Title>Профиль <mark>{{ user.name }}</mark></Title>
                    <span v-if="user.role_id == 1" style="margin-top: -17px; font-size: 12px; display: block; margin-bottom: 27px; color: var(--yellow); font-weight: 500;">Администратор</span>

                    <div v-if="user.id == $page.props.auth.id" class="profile__confidential">
                        <div v-if="$page.props.flash.message" class="message">
                            <button  class="button-delete-message">X</button>
                            {{$page.props.flash.message}}
                        </div>

                        <LabelInput>Почта</LabelInput>
                        <input class="input" readonly v-model="user.email"/>
                        <Link :href="route('password.edit')" class="button" style="margin-bottom: 15px;">Изменить пароль</Link><br>
                        <Button @click="logout" class="button button_red" style="margin-bottom: 15px;">Выйти</Button><br>
                        <Button v-if="!isPushSubscription" class="button_blue" id="enable-push">Подписаться на уведомления</Button>
                        <Button v-else class="button_red" id="disable-push">Отписаться от уведомлений</Button>
                    </div>
                    <div v-if="user.id == $page.props.auth.id && user.role_id == 1" class="profile__admin">
                        <Link :href="route('user.create')" style="margin-top: 15px;" class="button">Добавить пользователя</Link>
                    </div>
                </div>
            </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
