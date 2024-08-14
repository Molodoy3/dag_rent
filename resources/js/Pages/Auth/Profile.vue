<script setup lang="ts">

import DefaultLayout from "../Layouts/DefaultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Title from "../Components/Title.vue";
import { PropType } from "vue";
import UserData = App.Data.UserData;
import Button from "../Components/Button.vue";
import LabelInput from "../Components/LabelInput.vue";
import Input from "../Components/Input.vue";

const props = defineProps({
    "user": Object as PropType<UserData>,
    "isPushSubscription": Boolean,
    "userIcon": String
});

const formLogout = useForm({});
function logout() {
    formLogout.post(route('user.logout'));
}

const form = useForm({
    image: ''
});
function submitUserIcon(image) {
    form.image = image;
    form.post(route('user.update-icon', { 'userID': props.user.id }))
}

</script>

<template>
    <DefaultLayout>

        <Head :title="'Профиль ' + user.name"></Head>
        <section class="profile">
            <div class="profile__container">
                <div class="profile__header">
                    <div class="profile__icon">
                        <img v-bind:src="userIcon" alt="user icon">
                        <form v-if="user.id == $page.props.auth.id" action="#" enctype="multipart/form-data">
                            <label class="profile__edit-user-icon" for="image">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    stroke="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.8477 1.87868C19.6761 0.707109 17.7766 0.707105 16.605 1.87868L2.44744 16.0363C2.02864 16.4551 1.74317 16.9885 1.62702 17.5692L1.03995 20.5046C0.760062 21.904 1.9939 23.1379 3.39334 22.858L6.32868 22.2709C6.90945 22.1548 7.44285 21.8693 7.86165 21.4505L22.0192 7.29289C23.1908 6.12132 23.1908 4.22183 22.0192 3.05025L20.8477 1.87868ZM18.0192 3.29289C18.4098 2.90237 19.0429 2.90237 19.4335 3.29289L20.605 4.46447C20.9956 4.85499 20.9956 5.48815 20.605 5.87868L17.9334 8.55027L15.3477 5.96448L18.0192 3.29289ZM13.9334 7.3787L3.86165 17.4505C3.72205 17.5901 3.6269 17.7679 3.58818 17.9615L3.00111 20.8968L5.93645 20.3097C6.13004 20.271 6.30784 20.1759 6.44744 20.0363L16.5192 9.96448L13.9334 7.3787Z"
                                            fill="#0F0F0F"></path>
                                    </g>
                                </svg>
                            </label>
                            <input @input="submitUserIcon($event.target.files)" type="file" class="input_image"
                                id="image" name="image" accept="image/*">
                        </form>
                    </div>
                    <div class="profile__info">
                        <Title>Профиль <mark>{{ user.name }}</mark></Title>
                        <span v-if="user.role_id == 1"
                            style="margin-top: -19px; font-size: 11px; display: block; color: var(--yellow); font-weight: 500;">Администратор</span>
                    </div>
                </div>


                <div v-if="user.id == $page.props.auth.id" class="profile__confidential">
                    <div v-if="$page.props.flash.message" class="message">
                        <button class="button-delete-message">X</button>
                        {{ $page.props.flash.message }}
                    </div>

                    <LabelInput>Почта</LabelInput>
                    <input class="input" readonly v-model="user.email" />
                    <Link :href="route('password.edit')" class="button" style="margin-bottom: 15px;">Изменить пароль
                    </Link><br>
                    <Button @click="logout" class="button button_red" style="margin-bottom: 15px;">Выйти</Button><br>
                    <Button v-if="!isPushSubscription" class="button_blue" id="enable-push">Подписаться на
                        уведомления</Button>
                    <Button v-else class="button_red" id="disable-push">Отписаться от уведомлений</Button>
                </div>
                <div v-if="user.id == $page.props.auth.id && user.role_id == 1" class="profile__admin">
                    <Link :href="route('user.create')" style="margin-top: 15px;" class="button">Добавить пользователя
                    </Link>
                </div>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped></style>
