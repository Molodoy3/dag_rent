<script setup lang="ts">

import DefaultLayout from "../Layouts/DefaultLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import {onMounted, PropType} from "vue";
import StatisticData = App.Data.StatisticData;
import dayjs from "dayjs";
import Title from "../Components/Title.vue";
import LabelInput from "../Components/LabelInput.vue";
import InputError from "../Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import Button from "../Components/Button.vue";
import { initCustomSelect } from '../../modules/customSelect.js';

onMounted(() => {
    initCustomSelect(false);
});

const props = defineProps({
    //'sale': Object as PropType<StatisticData>,
    'accounts': Object,
    'errors': Object
});

const form = useForm({
    'account_id': null,
    'price': null,
    'added_at': new Date().toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    }).replace(',', ''),
    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
});
//form.added_at = formatMatherDate(form.added_at);

function formatMatherDate(dateString: string) {
    const date = dayjs(dateString);
    return date.format('DD.MM HH:mm');
}

function submit() {
    router.post('/statistics', {'statistic': form});
}
function onAccountChange(id) {
    form.account_id = id;
}

</script>

<template>
    <DefaultLayout>
        <Head title="Добавление продажи"></Head>
        <section class="create">
            <div class='create__container'>
                <Title>Добавление продажи</Title>
                <form @submit.prevent="submit" action="#" class="create__form">
                    <LabelInput for="account_id">Аккаунт</LabelInput>
                    <div class="form__custom-select-body" data-custom-select="Выберите аккаунт">
                        <select v-model="form.account_id" id="account_id" class="form__select">
                            <option v-for="account in accounts" :value='account.id'>{{ account.login }}
                            </option>
                        </select>
                        <div class="form__custom-select custom-select-form input" :class="{ 'error': props.errors.account_id }">
                            <div data-custom-select-field class="custom-select-form__field"></div>
                            <ul data-custom-select-options class="custom-select-form__options">
                                <li @click="onAccountChange(account.id)" v-for="account in accounts"
                                    :data-custom-select-option="account.id"> <span>{{ account.login }}</span>
                                    <div class="statistics__account-games" style="margin-bottom: 0;"><span v-for="game in account.games">{{ game.name }}</span></div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <LabelInput for="price">Цена</LabelInput>
                    <input class="input" id="price" v-model="form.price"
                           :class="{ 'error': props.errors.price }" />
                    <InputError v-if="props.errors.price">{{ props.errors.price }}</InputError>

                    <LabelInput for="price">Дата, время</LabelInput>
                    <input style="font-weight: 600; color: var(--yellow)" class="input" id="price" v-model="form.added_at"
                           :class="{ 'error': props.errors.added_at }" />
                    <InputError v-if="props.errors.added_at">{{ props.errors.added_at }}</InputError>

                    <Button type="submit" :disabled="form.processing"
                            :class="{ 'processing': form.processing }">Добавить</Button>
                </form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
