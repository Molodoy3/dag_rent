<script setup lang="ts">

import DefaultLayout from "../Layouts/DefaultLayout.vue";
import { Head } from "@inertiajs/vue3";
import Title from "../Components/Title.vue";
import LabelInput from "../Components/LabelInput.vue";
import { useForm } from "@inertiajs/vue3";
import Button from "../Components/Button.vue";
import { PropType } from "vue";
import AccountData = App.Data.AccountData;
import InputError from "../Components/InputError.vue";
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
    'platforms': Array,
    'games': Array,
    'errors': Object
});

props.account.platform_id = null;
const form = useForm({
    id:null,
    login:"",
    password:"",
    platform_id:null,
    busy:null,
    email:"",
    price: null,
    passwordEmail:"",
    comment:"",
    user_id:props.account.user_id,
    images: Array
});

//Надо вручную присваивать id, когда мы через js select меняем, так как vue с ним автоматически не обновляется
function onPlatformChange(id) {
    form.platform_id = id;
}

let selectedGames = [];
function submit() {
    if (selectedGames.length === 0) {
        alert("Выберите хотя бы одну игру");
        return;
    }
    form.post(route('accounts.store', { 'games': selectedGames }))
    //router.post('/accounts', { 'games': selectedGames, 'account': form });
}

</script>

<template>
    <DefaultLayout>
        <Head title="Новый аккаунт"></Head>
        <section class="create">
            <div class='create__container'>
                <Title>Добавление нового аккаунта</Title>
                <form @submit.prevent="submit" id="formImage" action="#" class="create__form"
                    enctype="multipart/form-data">
                    <div class="create__row">
                        <div class="create__col">
                            <LabelInput for="login">Игра/Игры</LabelInput>
                            <select v-model="selectedGames" multiple id="platformId" class="input select-multiple">
                                <option v-for="game in games" :value='game.id'>{{ game.name }}</option>
                            </select>

                            <LabelInput for="login">Логин</LabelInput>
                            <input class="input" id="login" v-model="form.login"
                                :class="{ 'error': props.errors.login }" />
                            <InputError v-if="props.errors.login">{{ props.errors.login }}</InputError>

                            <LabelInput for="password">Пароль</LabelInput>
                            <input class="input" id="password" v-model="form.password"
                                :class="{ 'error': props.errors.password }" />
                            <InputError v-if="props.errors.password">{{ props.errors.password }}</InputError>

                            <LabelInput for="price">Цена</LabelInput>
                            <input class="input" id="price" v-model="form.price"
                                   :class="{ 'error': props.errors.price }" />
                            <InputError v-if="props.errors.price">{{ props.errors.price }}</InputError>

                            <LabelInput for="platformId">Платформа</LabelInput>
                            <div class="form__custom-select-body" data-custom-select="Выберите платформу">
                                <select v-model="form.platform_id" id="platformId" class="form__select">
                                    <option v-for="platform in platforms" :value='platform.id'>{{ platform.name }}
                                    </option>
                                </select>
                                <div class="form__custom-select custom-select-form input" :class="{ 'error': props.errors.platform_id }">
                                    <div data-custom-select-field class="custom-select-form__field"></div>
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
                            <input class="input_image" type="file" name="image" id="image" multiple="multiple" accept="image/*"
                                   @input="form.images = $event.target.files"/>
                            <div class="form__image"></div>
                            <label for="image" class="button button_image">Добавить изображение</label>
                        </div>
                    </div>
                    <Button type="submit" :disabled="form.processing"
                        :class="{ 'processing': form.processing }">Добавить</Button>
                </form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped></style>
