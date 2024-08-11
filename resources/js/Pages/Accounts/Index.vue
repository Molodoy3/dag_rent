<script setup lang="ts">

import { onMounted, onUnmounted, PropType, ref, watch } from "vue";
import AccountData = App.Data.AccountData;
import { Head, router, Link } from "@inertiajs/vue3";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import Title from "../Components/Title.vue";
import { useClipboard } from '@vueuse/core';
import dayjs from 'dayjs';//sail npm i dayjs
import Button from "../Components/Button.vue";
import { initCustomSelect } from "../../modules/customSelect";
//import Pagination from "@/Components/Pagination.vue";
//import algoliasearch from 'algoliasearch/lite';

const { copy, text } = useClipboard({});

const props = defineProps({
    'accounts': Object,
    "flash": Object,
    "platforms": Object,
    "links": Object,
    'accountInfo': Object
});

const accounts = ref(props.accounts);
watch(() => props.accounts, (newAccounts) => {
    accounts.value = newAccounts;
});


//const index = client.initIndex('accounts');
//const client = algoliasearch('D13NJE4NTK', '3505f06444af6ed2f27521bfc4c6c390');

let timer = null;

//получение обновленных аккаунтов через get запрос с помощью fetch
const getAccounts = async () => {
    //console.log(router.post('account.get'));
    try {
        const params = new URLSearchParams({
            search: valueSearch,
            platform_id: platform_id.value
        });

        accounts.value = await fetch(`/get-update-accounts?${params}`)
            .then(response => response.json());
    } catch (error) {
        console.error('Ошибка при получении данных:', error);
    }
};
const startTimer = () => {
    timer = setInterval(getAccounts, 3000); // Запускаем таймер каждые 1000 мс
};
onUnmounted(() => {
    clearInterval(timer); // Очищаем таймер при размонтировании
});
onMounted(() => {
    startTimer();


    initCustomSelect(false);

    //const select = document.querySelector('[data-options]');
    //const searchItems = select.querySelectorAll('option');
    const input = document.querySelector('[data-input-search]');
    const customSelect = document.querySelector('[data-custom-select-options]');
    const searchCustomItems = customSelect.querySelectorAll('li');

    input.addEventListener('input', function(e) {
        const target = e.target;

        const val = target.value.trim().toUpperCase();
        const fragment = document.createDocumentFragment();

        //if (!target.classList.contains('keyboard__key')) return;

        for (const elem of searchCustomItems) {
            elem.remove();

            if (val === '' || elem.textContent.toUpperCase().includes(val)) {
                fragment.append(elem);
            }
        }

        customSelect.append(fragment);
    })

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

function formatDate(dateString: string) {
    const date = dayjs(dateString);
    const now = dayjs();
    const tomorrow = now.add(1, 'day');

    //проверяем сегодня ли кончится
    if (date.isSame(now, 'day')) {
        return "Сегодня в " + date.format('HH:mm');
    }
    //проверяем завтра ли
    else if (date.isSame(tomorrow, 'day')) {
        return `Завтра в ${date.format('HH:mm')}`;
    } else {
        return date.format('DD.MM HH:mm');
    }
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
    valueSearch = search.value;
    router.get(
        "/",
        { search: valueSearch, platform_id: value },
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
        { search: value, platform_id: platform_id.value },
        {
            //сохранение состояния (без перезагрузки)
            preserveState: true,
            preserveScroll: true,
        }
    );
});

/*let updating;
onMounted(() => {
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


//const mess = ref(props.flash.message)
function deleteFlashMessage() {
    //router.post('delete-flash-message');
    mess.value = null;
    router.get(
        "/",
        { search: valueSearch, platform_id: platform_id.value },
        {
            //сохранение состояния (без перезагрузки)
            preserveState: true,
            preserveScroll: true,
        }
    );
}

</script>

<template>
    <DefaultLayout>
        <Head title="Аккаунты"></Head>
        <section class="accounts">
            <!--            <ais-instant-search id="search-wrap" :search-client="client" index-name="accounts" class='accounts__container'>
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
</ais-instant-search>-->
            <div class='accounts__container'>
                <div class="accounts__header">
                    <Title>Все аккаунты</Title>
                    <div class="search-col">
                        <div class="form__custom-select-body" data-custom-select="Платформа">
                            <select data-options v-model="platform_id" id="platformId" class="form__select">
                                <option value="0">Сброс</option>
                                <option :selected="platform.id == platform_id" v-for="platform in platforms"
                                    :value='platform.id'>{{
                                    platform.name }}
                                </option>
                            </select>
                            <div class="form__custom-select custom-select-form input">
                                <input style="visibility:collapse; margin: 0; position: absolute;" type="text" class="custom-select-form__field custom-select-form__input input" data-input-search placeholder="Платформа">
                                <div data-custom-select-field class="custom-select-form__field"></div>
                                <ul data-custom-select-options class="custom-select-form__options">
                                    <li data-custom-select-option="0" @click="onPlatformChange('0')">Сброс</li>
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
                    <button @click="deleteFlashMessage()" class="button-delete-message">X</button>
                    {{ $page.props.flash.message }}
                </div>
                <div v-if="accountInfo" class="accounts__row accounts__row_top">
                    <div class="accounts__item"
                         :class="{ 'accounts__item_busy': accountInfo.busy, 'accounts__item_process': accountInfo.status == 1, 'accounts__item_problem': accountInfo.status == 2 }">
                        <h3 class="statistics__account-games"><span v-for="game in accountInfo.games">{{ game.name }}</span>
                        </h3>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Логин:</div>-->
                            <span @click="copy(accountInfo.login)" class="accounts__weight accounts__copy">{{
                                    accountInfo.login
                                }}</span>
                        </div>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Пароль:</div>-->
                            <span @click="copy(accountInfo.password)" class="accounts__weight accounts__copy">{{ accountInfo.password }}</span>
                        </div>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Пароль:</div>-->
                            <span class="accounts__platform">{{ accountInfo.platform.name }}</span>
                        </div>
                        <div class="accounts__status">
                            <div class="accounts__free" v-if="accountInfo.busy == null && accountInfo.status == null">Свободен</div>
                            <div class="accounts__process" v-if="accountInfo.status == 1">В процессе публикации</div>
                            <div class="accounts__problem" v-if="accountInfo.status == 2">Проблема(ы)</div>
                            <div class="accounts__date" v-if="accountInfo.busy && accountInfo.status == null">{{
                                    formatDate(accountInfo.busy)
                                }}
                            </div>
                        </div>
                        <div class="accounts__buttons">
                            <Button @click="router.get('/accounts/' + accountInfo.id + '/edit')">Перейти</Button>
                            <Button @click="copy('Логин: ' + accountInfo.login + ' \\n Пароль: ' + accountInfo.password)"
                                    class="accounts__copy">Копировать
                            </Button>
                        </div>
                    </div>
                    <!--                    <Pagination :data="accounts"/>-->
                </div>


                <div class="accounts__row">
                    <div class="accounts__item" v-for="item in accounts.data"
                        :class="{ 'accounts__item_busy': item.busy, 'accounts__item_process': item.status == 1, 'accounts__item_problem': item.status == 2 }">
                        <h3 class="statistics__account-games"><span v-for="game in item.games">{{ game.name }}</span>
                        </h3>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Логин:</div>-->
                            <span @click="copy(item.login)" class="accounts__weight accounts__copy">{{
                                item.login
                                }}</span>
                        </div>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Пароль:</div>-->
                            <span @click="copy(item.password)" class="accounts__weight accounts__copy">{{ item.password }}</span>
                        </div>
                        <div class="accounts__info">
                            <!--                        <div class="accounts__info-title">Пароль:</div>-->
                            <div class="accounts__status">
                                <div class="accounts__free" v-if="item.busy == null && item.status == null">Свободен</div>
                                <div class="accounts__process" v-if="item.status == 1">В процессе публикации</div>
                                <div class="accounts__problem" v-if="item.status == 2">Проблема(ы)</div>
                                <div class="accounts__date" v-if="item.busy && item.status == null">{{
                                        formatDate(item.busy)
                                    }}
                                </div>
                            </div>
                            <span class="accounts__platform">{{ item.platform.name }}</span>
                        </div>
                        <div class="accounts__buttons">
                            <Button @click="router.get('/accounts/' + item.id + '/edit')">Перейти</Button>
                            <Button @click="copy('Логин: ' + item.login + ' \\n Пароль: ' + item.password)"
                                class="accounts__copy">Копировать
                            </Button>
                        </div>
                    </div>
<!--                    <Pagination :data="accounts"/>-->
                </div>
                <ul class="pagination">
                    <li v-if="accounts.links.length > 3" v-for="link in accounts.links">
                        <Link v-if="link.url && !link.active" :class="{'active': link.active, 'link': link.url }" @click="router.get(link.url)" v-html="link.label"></Link>
                        <span v-else v-html="link.label" :class="{'active': link.active }"></span>
                    </li>
                </ul>
            </div>
        </section>
        <div class="info-window">
            Скопировано
        </div>
    </DefaultLayout>
</template>
<style scoped></style>
