<script setup lang="ts">

import { PropType } from "vue";
import AccountData = App.Data.AccountData;
import {Head, Link, useForm} from "@inertiajs/vue3";
import Title from "../Components/Title.vue";
import LabelInput from "../Components/LabelInput.vue";
import Button from "../Components/Button.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import InputError from "../Components/InputError.vue";
import dayjs from 'dayjs';
import {useClipboard} from "@vueuse/core";
//так как надо js загружать именно для этого компонента, делаем так:
import { onMounted,  } from 'vue';
import { initCustomSelect } from '../../modules/customSelect.js';

onMounted(() => {
    initCustomSelect(false);
    let images;
    const form = document.querySelector("#formImage");
    if (form) {
        const fileInput = form.querySelector("input[type='file']");
        fileInput.addEventListener("change", e => {
            images = e.target.files;
            for (let i = 0; i < images.length; i++) {
                const wrap = form.querySelector(".form__image");
                const item = document.createElement('div');
                item.classList.add("item-image");
                wrap.appendChild(item);
                const image = images[i];
                const imageUrl = URL.createObjectURL(image);
                //data-image-numb="${i}"
                item.innerHTML += `<img data-open-image src="${imageUrl}" alt="image">`;
                const closeButton = document.createElement('div');
                closeButton.classList.add("button-delete-image");
                closeButton.innerText = 'X';
                item.appendChild(closeButton);
            }
        });
    }
})

const props = defineProps({
    'account': Object as PropType<AccountData>,
    'gamesAccount': Array,
    'games': Array,
    'platforms': Array,
    'images': Array,
    'errors': Object
});

const account = props.account;
const form = useForm({
    id:account.id,
    login:account.login,
    password:account.password,
    platform_id:account.platform_id,
    busy:account.busy,
    email:account.email,
    passwordEmail:account.passwordEmail,
    price: account.price,
    comment:account.comment,
    user_id:props.account.user_id,
    status:props.account.status,
    imagesForDel: props.images,
    images: Array,
    //для хранения даты в едином формате берем часовой пояс пользователя
    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
});

let selectedGames = props.gamesAccount;
function onPlatformChange(id) {
    form.platform_id = id;
}
function onStatusChange(status) {
    form.status = status;
}
function submit() {
    if (selectedGames.length === 0) {
        alert("Выберите хотя бы одну игру");
        return;
    }
   // console.log(account.id)
    form.post(route('account.update', { 'id': account.id, 'games': selectedGames }));
    //router.put('/accounts/' + account.id, { 'games': selectedGames, 'account': form });
}
//Получение корректного пути для картинки
function getImage(image) {
    return  new URL('/storage/app/' + image, import.meta.url).href;
}

function deleteAccount() {
    if (confirm('Вы уверены, что хотите удалить аккаунт?')) {
        router.delete('/accounts/' + account.id);
    }
}

function deleteImage(image) {
    form.imagesForDel = form.imagesForDel.filter(img => img !== image);
    console.log(form.imagesForDel);
}

const { copy, text } = useClipboard({});
function generatePassword() {
    const length = (Math.random() + 1) * 7; // Длина пароля
    const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@#$%^&*()_+=\\/|?'; // Символы, которые будут использоваться
    let password = '';
    for (let i = 0; i < length; i++) {
        const index = Math.floor(Math.random() * charset.length);
        password += charset[index];
    }
    form.password = password;
    copy(password);
}

//Ниже работа с датами
function deleteDate() {
    form.busy = null;
}
function addDay(hours = 0) {
    const currentTime = new Date();
    const tomorrow = new Date(currentTime.getTime() + ((24 + hours) * 60 * 60 * 1000));
    form.busy = formatTime(tomorrow);
}
function formatTime(date) {
    const day = ("0" + date.getDate()).slice(-2);
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const hours = ("0" + date.getHours()).slice(-2);
    const minutes = ("0" + date.getMinutes()).slice(-2);
    return `${day}.${month} ${hours}:${minutes}`;
}

if (form.busy)
    form.busy = formatMatherDate(form.busy);
function formatMatherDate(dateString: string) {
    const date = dayjs(dateString);
    return date.format('DD.MM HH:mm');
}

//Обновление данных
/*window.Echo.channel(`account-update`)
    .listen('AccountUpdate', (e) => {
        const acc = e.account;
        form.login = acc.login;
        form.password = acc.password;
        form.platform_id = acc.platform_id;
        form.busy = formatMatherDate(acc.busy);
        form.email = acc.email;
        form.passwordEmail = acc.passwordEmail;
        form.comment = acc.comment;
        console.log(e.selectedGames)
        selectedGames = e.selectedGames;
    });*/
</script>

<template>
    <DefaultLayout>
        <Head :title="'Редактирование аккаунта ' + form.login"></Head>
        <section class="create">
            <div class='create__container'>
                <Title>Редактирование аккаунта <mark>{{form.login}}</mark></Title>
                <form @submit.prevent="submit" id="formImage" action="#" class="create__form"
                      enctype="multipart/form-data">
                    <div class="create__row">
                        <div class="create__col">
                            <LabelInput for="login">Игра/Игры</LabelInput>
                            <select v-model="selectedGames" multiple id="platformId" class="input select-multiple">
                                <option v-for="game in games" :key="game.id"  :value='game.id'>{{ game.name }}</option>
                            </select>
                            <LabelInput for="busy">Занятость</LabelInput>
                            <input style="font-weight: 600; color: var(--yellow)" class="input" id="busy" v-model="form.busy"
                                   :class="{ 'error': props.errors.busy }" />
                            <InputError v-if="props.errors.busy">{{ props.errors.busy }}</InputError>
                            <div class="buttons" style="margin-bottom: 15px">
                                <div @click="addDay(0)" style="font-size: 13px" class="button">+ Сутки</div>
                                <div @click="addDay(5)" style="font-size: 13px" class="button button_blue">+ Сутки + 5 часов</div>
                                <div @click="deleteDate" style="font-size: 13px" class="button button_red">Сбросить</div>
                            </div>
                            <LabelInput for="status">Статус</LabelInput>
                            <div class="form__custom-select-body" data-custom-select="Выберите статус">
                                <select v-model="form.status" id="platformId" class="form__select">
                                    <option :selected="account.status == 1" value='1'>В процессе публикации</option>
                                    <option :selected="account.status == 2" value='2'>Есть проблема(ы)</option>
                                    <option :selected="!account.busy" value='null'>Свободен</option>
                                    <option :selected="account.busy" value='0'>Занят</option>
                                </select>
                                <div class="form__custom-select custom-select-form input" :class="{ 'error': props.errors.busy }">
                                    <div data-custom-select-field class="custom-select-form__field"
                                         :style="{ color:
                                                      !account.busy && account.status == null ? 'var(--green)':
                                                      account.busy && account.status == null ? 'var(--red)' :
                                                      account.status == 1 ? 'var(--yellow)':
                                                      account.status == 2 ? '#a10000': null
                                            , fontWeight: account.status == 2 ? 500 : null}">

                                    </div>
                                    <ul data-custom-select-options class="custom-select-form__options">
                                        <li @click="onStatusChange(0)" data-custom-select-option='null' style="color: var(--green)">Свободен</li>
                                        <li @click="onStatusChange(1)" data-custom-select-option='1' style="color: var(--yellow)">В процессе публикации</li>
                                        <li @click="onStatusChange(0)" data-custom-select-option="0" style="color: var(--red)">Занят</li>
                                        <li @click="onStatusChange(2)" data-custom-select-option='2'  style="color: #a10000; font-weight: 500;">Есть проблема(ы)</li>
                                    </ul>
                                </div>
                            </div>
                            <span style="font-size: 12px; color: var(--yellow); margin-top: -10px; display: block; margin-bottom: 10px;">не меняется, если указана дата</span>
                            <LabelInput for="login">Логин</LabelInput>
                            <input class="input" id="login" v-model="form.login"
                                   :class="{ 'error': props.errors.login }" />
                            <InputError v-if="props.errors.login">{{ props.errors.login }}</InputError>

                            <LabelInput for="price">Цена</LabelInput>
                            <input class="input" id="price" v-model="form.price"
                                   :class="{ 'error': props.errors.price }" />
                            <InputError v-if="props.errors.price">{{ props.errors.price }}</InputError>

                            <LabelInput for="password">Пароль</LabelInput>
                            <input class="input" id="password" v-model="form.password"
                                   :class="{ 'error': props.errors.password }" />
                            <InputError v-if="props.errors.password">{{ props.errors.password }}</InputError>
                            <div style="margin-bottom: 15px; font-size: 13px" @click="generatePassword" class="button accounts__copy">Генерировать + копировать</div>
                            <LabelInput for="platformId">Платформа</LabelInput>
                            <div class="form__custom-select-body" data-custom-select="Выберите платформу">
                                <select v-model="form.platform_id" id="platformId" class="form__select">
                                    <option :selected="platform.id == form.platform_id" v-for="platform in platforms" :value='platform.id'>{{ platform.name }}
                                    </option>
                                </select>
                                <div class="form__custom-select custom-select-form input">
                                    <div data-custom-select-field class="custom-select-form__field "
                                         :class="{ 'error': props.errors.platform_id }"></div>
                                    <ul data-custom-select-options class="custom-select-form__options">
                                        <li @click="onPlatformChange(platform.id)" v-for="platform in platforms"
                                            :data-custom-select-option="platform.id">{{ platform.name }}</li>
                                    </ul>
                                </div>
                            </div>
                            <InputError v-if="props.errors.platform_id">{{ props.errors.platform_id }}</InputError>

                            <LabelInput for="email">Почта</LabelInput>
                            <input class="input" id="email" v-model="form.email"
                                   :class="{ 'error': props.errors.email }" />
                            <InputError v-if="props.errors.email">{{ props.errors.email }}</InputError>

                            <LabelInput for="passwordEmail">Пароль от почты</LabelInput>
                            <input class="input" id="passwordEmail" v-model="form.passwordEmail"
                                   :class="{ 'error': props.errors.passwordEmail }" />
                            <InputError v-if="props.errors.passwordEmail">{{ props.errors.passwordEmail }}</InputError>

                            <LabelInput for="comment">Комментарий</LabelInput>
                            <textarea class="input" id="comment" v-model="form.comment"
                                      :class="{ 'error': props.errors.comment }"></textarea>
                            <InputError v-if="props.errors.comment">{{ props.errors.comment }}</InputError>
                        </div>
                        <div class="create__col">
                            <LabelInput for="image">Загрузка изображений</LabelInput>
                            <input class="input_image" type="file" id="image" name="image" multiple="multiple" accept="image/*"
                                   @input="form.images=$event.target.files"/>
                            <div class="form__image">
                                <div v-if="props.images" v-for="image in props.images" class="item-image">
                                    <img data-open-image v-bind:src="image" alt="image">
                                    <button @click="deleteImage(image)" class="button-delete-image">X</button>
                                </div>
                            </div>
                            <label for="image" class="button button_image">Добавить изображение</label>
                        </div>
                    </div>
                    <div class="buttons" style="margin-bottom: 15px">
                        <Button type="submit" :disabled="form.processing"
                                :class="{ 'processing': form.processing }">Сохранить</Button>
                        <div @click="deleteAccount" class="button button_red">Удалить</div>
                    </div>
                </form>
            </div>
        </section>
        <div class="info-window">
            Пароль для входа скопирован
        </div>
    </DefaultLayout>
</template>

<style scoped>

</style>
