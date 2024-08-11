<script setup lang="ts">

import { Head, Link, router } from "@inertiajs/vue3";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import {onMounted,onUnmounted, PropType, ref, watch} from "vue";
import StatisticData = App.Data.StatisticData;
import dayjs from "dayjs";
import Title from "../Components/Title.vue";
import Button from "../Components/Button.vue";

let props = defineProps({
    "fields": Object as PropType<StatisticData>,
    "general": Object,
    "flash": Object
});

//для постоянного обновления статистики
const fields = ref(props.fields);
let timer = null;
const getUpdatingStatistics = async () => {
    try {
        const data = {
            search: valueSearch,
        };
        fields.value = await fetch('/get-update-statistics',{
            method: 'get',
        }).then(response => response.json());
    } catch (error) {
        console.error('Произошла ошибка ', error);
    }
}
onUnmounted(() => {
    clearInterval(timer);
})
onMounted(()=> {
    startTimer();
});
function startTimer() {
    timer = setInterval( getUpdatingStatistics, 2000);
}

//Для поиска
let search = ref('');
//для сохранения сообщения при обновлении
const flash = props.flash.message;
const mess = ref(flash);

let valueSearch = "";
watch(search, (value) => {
    valueSearch = value;
    router.get(
        "/statistics",
        { search: value, mess: flash },
        {
            //сохранение состояния (без перезагрузки)
            preserveState: true,
            preserveScroll: true,
        }
    );
});

//let updating;
/*onMounted(() => {
    //старый плохой способ обновления
    updating = setInterval(() => {
            router.get(
                "/statistics",
                { search: valueSearch, mess: flash },
                {
                    //сохранение состояния (без перезагрузки)
                    preserveState: true,
                    preserveScroll: true,
                }
            );
        }, 5000);
});*/
/*onUnmounted(() => {
    //clearInterval(updating);
});*/
/*window.Echo.channel(`account-update`).listen('AccountUpdate', (e) => {

});*/

function formatMatherDate(dateString: string) {
    const date = dayjs(dateString);
    return date.format('DD.MM HH:mm');
}

function deleteFlashMessage() {
    mess.value = null;
    router.get(
        "/statistics",
        { search: valueSearch },
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
        <Head title="Статистика продаж"></Head>
        <section class="statistics">
            <div class="statistics__container">
                <div class="statistics__header accounts__header">
                    <div class="statistics__titles">
                        <Title>Всего заработано <mark>{{ general.money }}Р.</mark></Title>
                        <h2 class="statistics__sub-title">Всего продано <mark>{{ general.count }} предложений</mark>.
                            Средняя цена <mark>{{ general.averagePrice }}Р. </mark></h2>
                    </div>
                    <input class="statistics__search input" type="text" v-model="search" placeholder="Поиск...">
                </div>
                <Link :href="route('statistics.create')" class="button accounts__add">Добавить</Link><br>
                <div v-if="mess" class="message">
                    <button @click="deleteFlashMessage()" class="button-delete-message">X</button>
                    {{mess}}
                </div>
                <div class="statistics__row">
                    <div @click="router.get('/statistics/' + field.id + '/edit')" v-for="field in fields.data"
                        class="statistics__item">
                        <div class="statistics__date">{{ formatMatherDate(field.added_at) }}</div>
                        <div class="statistics__account-info">
                            <div class="statistics__account-login">{{ field.account.login }}</div>
                            <div class="statistics__account-games"><span v-for="game in field.account.games">{{ game.name }}</span></div>
                            <div class="statistics__account-platform">{{ field.account.platform.name }}</div>
                        </div>
                        <div class="statistics__date"><mark>{{ field.price }}р.</mark></div>
                    </div>
                </div>
                <ul class="pagination">
                    <li v-if="fields.links.length > 3" v-for="link in fields.links">
                        <Link v-if="link.url && !link.active" :class="{'active': link.active, 'link': link.url }" @click="router.get(link.url)" v-html="link.label"></Link>
                        <span v-else v-html="link.label" :class="{'active': link.active }"></span>
                    </li>
                </ul>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped></style>
