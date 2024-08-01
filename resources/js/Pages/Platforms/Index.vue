<script setup lang="ts">

import Title from "../Components/Title.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import {PropType} from "vue";
import PlatformData = App.Data.platformData;
import dayjs from "dayjs";
import Button from "../Components/Button.vue";

const props = defineProps({
   "platforms": Object as PropType<PlatformData>
});

function formatMatherDate(dateString: string) {
    const date = dayjs(dateString);
    return date.format('DD.MM.YYYY HH:II');
}

</script>

<template>
    <DefaultLayout>
        <Head title="Платформы"></Head>
        <section class="simple-elements">
            <div class="simple-elements__container">
                <Title>Платформы</Title>
                <a :href="route('platforms.create')" class="accounts__add button">Добавить</a><br>
                <div v-if="$page.props.flash.message" class="message">
                    <button  class="button-delete-message">X</button>
                    {{$page.props.flash.message}}
                </div>
                <div class="simple-elements__row">
                    <a :href="route('platforms.edit', platform.id)" v-for="platform in platforms" class="simple-elements__item">
                        <h3 class="simple-elements__title">{{ platform.name }}</h3>
                        <div class="simple-elements__date">{{ formatMatherDate(platform.created_at) }}</div>
                    </a>
                </div>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
