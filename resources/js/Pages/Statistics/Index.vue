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

//чтобы при поиске обновлять основную статистику
const general = ref(props.general);

//для постоянного обновления статистики
const fields = ref(props.fields);
watch(() => props.fields, (newFields) => {
    fields.value = newFields;
});
let timer = null;
const getUpdatingStatistics = () => {
    updateStatistics();
}

async function updateStatistics() {
    try {
        const params = new URLSearchParams({
            search: search.value,
            //обязательно если есть пагинация указываем номер страницы и конечно из props обязательно
            page: props.fields.current_page
        });
        await fetch(`/get-update-statistics?${params}`).then(response => response.json().then(data => {
            fields.value = data.fields
            general.value = data.general
        }))
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
watch(search, (value) => {
    //если текущая страница не первая, то перезагружаем на первую
    if (fields.value.current_page > 1) {
        router.get(
            "/statistics",
            { search: search.value, page: 1 },
            {
                //сохранение состояния (без перезагрузки)
                preserveState: true,
                preserveScroll: true,
            }
        );
    } else
        updateStatistics();
});

//для сохранения сообщения при обновлении
//const flash = props.flash.message;
//const mess = ref(flash);



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

/*function deleteFlashMessage() {
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
}*/

//чтобы при перелистывании страницы поиск сохранялся
function nextPaginate(url) {
    router.get(
        url,
        { search: search.value },
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
                <div v-if="$page.props.flash.message" class="message">
                    <button class="button-delete-message">X</button>
                    {{$page.props.flash.message}}
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
<!--                тут используем props, так как ref ссылка fields менять link будет при обновлении данных и будут ссылки на json объекты -->
                    <li v-if="fields.links.length > 3" v-for="link in fields.links">
                        <button v-if="link.url && !link.active" :class="{'active': link.active, 'link': link.url }"
                                @click="nextPaginate(link.url)" v-html="link.label"></button>
                        <span v-else v-html="link.label" :class="{'active': link.active }"></span>
                    </li>
                </ul>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped></style>
