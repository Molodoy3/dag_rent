<script setup lang="ts">

import { onMounted, onUnmounted, PropType, ref, watch } from "vue";
import AccountData = App.Data.AccountData;
import { Head, router } from "@inertiajs/vue3";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import Title from "../Components/Title.vue";
import { useClipboard } from '@vueuse/core';
import dayjs from 'dayjs';//sail npm i dayjs
import Button from "../Components/Button.vue";
import { initCustomSelect } from "../../modules/customSelect";
//import algoliasearch from 'algoliasearch/lite';

const { copy, text } = useClipboard({});

const props = defineProps({
    'accounts': Array as PropType<AccountData>,
    "flash": Object,
    "platforms": Object
});

//const index = client.initIndex('accounts');

//const client = algoliasearch('D13NJE4NTK', '3505f06444af6ed2f27521bfc4c6c390');

//let updating;
onMounted(() => {
    initCustomSelect(false);

    /*updating = setInterval(() => {
        router.get(
            "/",
            //{ search: valueSearch },
            {
                //сохранение состояния (без перезагрузки)
                preserveState: true,
                preserveScroll: true,
            }
        );
    }, 10000)*/
});
/*onUnmounted(() => {
    clearInterval(updating);
});*/

function formatDate(dateString: string) {
    const date = dayjs(dateString);
    // Then specify how you want your dates to be formatted
    return date.format('DD.MM HH:mm');
}

//Для поиска
let search = ref('');
let platform_id = ref('');
function onPlatformChange(id) {
    platform_id.value = id;

}
//для сохранения сообщения при обновлении
const flash = props.flash.message;
const mess = ref(flash);

let valueSearch = "";
watch(platform_id, (value) => {
    console.log(value)
    valueSearch = search.value;
    router.get(
        "/",
        { search: valueSearch, mess: flash, platform_id: value },
        {
            //сохранение состояния (без перезагрузки)
            preserveState: true,
            preserveScroll: true,
        }
    );
});
watch(search, (value) => {
    valueSearch = value;
    router.get(
        "/",
        { search: value, mess: flash, platform_id: platform_id.value },
        {
            //сохранение состояния (без перезагрузки)
            preserveState: true,
            preserveScroll: true,
        }
    );
});

let updating;
/*onMounted(() => {
    updating = setInterval(() => {
        router.get(
            "/",
            { search: valueSearch, mess: flash, platform_id: platform_id.value },
            {
                //сохранение состояния (без перезагрузки)
                preserveState: true,
                preserveScroll: true,
            }
        );
    }, 1000);
});
onUnmounted(() => {
    clearInterval(updating);
});*/
</script>

<template>
    <DefaultLayout>

        <Head title="Аккаунты"></Head>
        <section class="accounts">
            <div class='accounts__container'>
                <div class="accounts__header">
                    <Title>Все аккаунты</Title>
                    <div class="search-col">
                        <div class="form__custom-select-body" data-custom-select="Платформа">
                            <select v-model="platform_id" id="platformId" class="form__select">
                                <option :selected="platform.id == platform_id" v-for="platform in platforms"
                                    :value='platform.id'>{{
                                    platform.name }}
                                </option>
                            </select>
                            <div class="form__custom-select custom-select-form input">
                                <div data-custom-select-field class="custom-select-form__field"></div>
                                <ul data-custom-select-options class="custom-select-form__options">
                                    <li @click="onPlatformChange(platform.id)" v-for="platform in platforms"
                                        :data-custom-select-option="platform.id">{{ platform.name }}</li>
                                </ul>
                            </div>
                        </div>
                        <input class="statistics__search input" v-model="search" type="text"
                            placeholder="Поиск аккаунтов..." />
                    </div>
                </div>
                <a :href="route('accounts.create')" class="accounts__add button">Добавить</a><br>
                <div v-if="$page.props.flash.message" class="message">
                    <button class="button-delete-message">X</button>
                    {{ $page.props.flash.message }}
                </div>
                <div class="accounts__row">
                    <div class="accounts__item" v-for="item in accounts"
                        :class="{ 'accounts__item_busy': item.busy, 'accounts__item_process': item.status == 1, 'accounts__item_problem': item.status == 2 }">
                        <h3 class="statistics__account-games"><span v-for="game in item.games">{{ game.name }}</span>
                        </h3>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Логин:</div>-->
                            <span class="accounts__weight">{{
                                item.login
                                }}</span>
                        </div>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Пароль:</div>-->
                            <span class="accounts__weight">{{ item.password }}</span>
                        </div>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Пароль:</div>-->
                            <span class="accounts__platform">{{ item.platform.name }}</span>
                        </div>
                        <div class="accounts__status">
                            <div class="accounts__free" v-if="item.busy == null && item.status == null">Свободен</div>
                            <div class="accounts__process" v-if="item.status == 1">В процессе публикации</div>
                            <div class="accounts__problem" v-if="item.status == 2">Проблема(ы)</div>
                            <div class="accounts__date" v-if="item.busy && item.status == null">{{
                                formatDate(item.busy)
                                }}
                            </div>
                        </div>
                        <div class="accounts__buttons">
                            <Button @click="router.get('/accounts/' + item.id + '/edit')">Перейти</Button>
                            <Button @click="copy('Логин: ' + item.login + ' \\n Пароль: ' + item.password)"
                                class="accounts__copy">Копировать
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="info-window">
            Данные для входа скопированы
        </div>
    </DefaultLayout>
</template>
<style scoped></style>
