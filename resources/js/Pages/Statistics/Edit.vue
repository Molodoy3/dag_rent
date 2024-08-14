<script setup lang="ts">

import DefaultLayout from "../Layouts/DefaultLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import {PropType} from "vue";
import StatisticData = App.Data.StatisticData;
import dayjs from "dayjs";
import Title from "../Components/Title.vue";
import LabelInput from "../Components/LabelInput.vue";
import InputError from "../Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import Button from "../Components/Button.vue";

const props = defineProps({
    'sale': Object as PropType<StatisticData>,
    'errors': Object
});

const form = useForm({
    id:props.sale.id,
    account_id:props.sale.account_id,
    price:props.sale.price,
    added_at:props.sale.added_at,
    account:props.sale.account,
    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
});
form.added_at = formatMatherDate(form.added_at);

function formatMatherDate(dateString: string) {
    const date = dayjs(dateString);
    return date.format('DD.MM HH:mm');
}

function submit() {
    router.patch('/statistics/' + props.sale.id, {'statistic': form});
}
function deleteStatistic() {
    if (confirm('Вы уверены, что хотите удалить продажу?')) {
        router.delete(route('statistics.destroy', props.sale.id));
    }
}

</script>

<template>
    <DefaultLayout>
        <Head :title="'Редактирование продажи от ' + formatMatherDate(sale.added_at) + ' аккаунта ' + sale.account.login"></Head>
        <section class="create">
            <div class='create__container'>
                <Title>Редактирование продажи от <mark>{{formatMatherDate(sale.added_at)}}</mark> аккаунта <mark>{{sale.account.login}}</mark></Title>
                <form @submit.prevent="submit" action="#" class="create__form">
                    <LabelInput>Аккаунт</LabelInput>
                    <a class="statistics-edit-account" style="font-size: 18px; margin-bottom: 15px; display: inline-block;" :href="'/accounts/' + sale.account.id + '/edit'"><mark>{{sale.account.login}}</mark> ({{sale.account.platform.name}})</a>

                    <LabelInput for="price">Цена</LabelInput>
                    <input class="input" id="price" v-model="form.price"
                           :class="{ 'error': props.errors.price }" />
                    <InputError v-if="props.errors.price">{{ props.errors.price }}</InputError>

                    <LabelInput for="price">Дата, время</LabelInput>
                    <input style="font-weight: 600; color: var(--yellow)" class="input" id="price" v-model="form.added_at"
                           :class="{ 'error': props.errors.added_at }" />
                    <InputError v-if="props.errors.added_at">{{ props.errors.added_at }}</InputError>

                    <div class="buttons" style="margin-bottom: 15px">
                        <Button type="submit" :disabled="form.processing"
                                :class="{ 'processing': form.processing }">Сохранить</Button>
                        <div @click="deleteStatistic" class="button button_red">Удалить</div>
                    </div>
                </form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
