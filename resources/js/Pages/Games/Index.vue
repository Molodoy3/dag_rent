<script setup lang="ts">

import Title from "../Components/Title.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import {PropType} from "vue";
import GameData = App.Data.GameData;
import dayjs from "dayjs";
import Button from "../Components/Button.vue";

const props = defineProps({
   "games": Object as PropType<GameData>
});

function formatMatherDate(dateString: string) {
    const date = dayjs(dateString);
    return date.format('DD.MM.YYYY');
}

</script>

<template>
    <DefaultLayout>
        <Head title="Игры"></Head>
        <section class="simple-elements">
            <div class="simple-elements__container">
                <Title>Игры</Title>
                <a :href="route('games.create')" class="accounts__add button">Добавить</a><br>
                <div v-if="$page.props.flash.message" class="message">
                    <button  class="button-delete-message">X</button>
                    {{$page.props.flash.message}}
                </div>
                <div class="simple-elements__row">
                    <a :href="route('games.edit', game.id)" v-for="game in games" class="simple-elements__item">
                        <h3 class="simple-elements__title">{{ game.name }}</h3>
                        <div class="simple-elements__date">{{ formatMatherDate(game.created_at) }}</div>
                    </a>
                </div>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
