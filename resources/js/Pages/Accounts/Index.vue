<script setup lang="ts">

import {onMounted, onUnmounted, PropType, ref} from "vue";
import AccountData = App.Data.AccountData;
import { Head, router } from "@inertiajs/vue3";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import Title from "../Components/Title.vue";
import { useClipboard } from '@vueuse/core';
import dayjs from 'dayjs';//sail npm i dayjs
import Button from "../Components/Button.vue";
import algoliasearch  from 'algoliasearch/lite';
//import VueInstantSearch from 'vue-instantsearch/vue3/es';

const { copy, text } = useClipboard({});

/*defineProps({
    'accounts': Array as PropType<AccountData>,
});*/

//const index = client.initIndex('accounts');

const client = algoliasearch('D13NJE4NTK', '3505f06444af6ed2f27521bfc4c6c390');

/*let updating;
onMounted(() => {
    updating = setInterval(() => {
        router.get(
            "/",
            //{ search: valueSearch },
            {
                //сохранение состояния (без перезагрузки)
                preserveState: true,
                preserveScroll: true,
            }
        );
    }, 10000)
});
onUnmounted(() => {
    clearInterval(updating);
});*/

function formatDate(dateString: string) {
    const date = dayjs(dateString);
    // Then specify how you want your dates to be formatted
    return date.format('DD.MM HH:mm');
}

//вывод сообщения
//{{$page.props.flash.message}}
</script>

<template>
    <DefaultLayout>
        <Head title="Аккаунты"></Head>
        <section class="accounts">
            <ais-instant-search id="search-wrap" :search-client="client" index-name="accounts" class='accounts__container'>
                <div class="accounts__header">
                    <Title>Все аккаунты</Title>
                    <ais-search-box placeholder="Поиск аккаунтов..."></ais-search-box>
                </div>
                <a :href="route('accounts.create')" class="accounts__add button">Добавить</a><br>
                <div v-if="$page.props.flash.message" class="message">
                    <button  class="button-delete-message">X</button>
                    {{$page.props.flash.message}}
                </div>
                <ais-hits>
                        <template v-slot:item="{ item }">
                            <div class="accounts__item" :class="{ 'accounts__item_busy': item.busy, 'accounts__item_process': item.status == 1, 'accounts__item_problem': item.status == 2}">
                                <h3 class="accounts__title-item">{{ item.games }}</h3>
                                <div class="accounts__info">
                                    <div class="accounts__info-title">Логин:</div> <span class="accounts__weight">{{
                                        item.login }}</span>
                                </div>
                                <div class="accounts__info">
                                    <div class="accounts__info-title">Пароль:</div> <span class="accounts__weight">{{
                                        item.password }}</span>
                                </div>
                                <div class="accounts__status">
                                    <div class="accounts__free" v-if="item.busy == null && item.status == null">Свободен</div>
                                    <div class="accounts__process" v-if="item.status == 1">В процессе публикации</div>
                                    <div class="accounts__problem" v-if="item.status == 2">Проблема(ы)</div>
                                    <div class="accounts__date" v-if="item.busy && item.status == null">{{ formatDate(item.busy) }}</div>
                                </div>
                                <div class="accounts__buttons">
                                    <Button @click="router.get('/accounts/' + item.id + '/edit')">Перейти</Button>
                                    <Button @click="copy('Логин: ' + item.login + ' \\n Пароль: ' + item.password)" class="accounts__copy">Копировать</Button>
                                </div>
                            </div>
                        </template>
                </ais-hits>
            </ais-instant-search>
        </section>
        <div class="info-window">
            Данные для входа скопированы
        </div>
    </DefaultLayout>
</template>
<style scoped></style>
