<script setup lang="ts">

import DefaultLayout from "../Layouts/DefaultLayout.vue";
import {Head} from "@inertiajs/vue3";
import Title from "../Components/Title.vue";
</script>

<template>
    <default-layout>
        <Head title="404"></Head>
        <section class="error">
            <div class="error__container">
                <Title>Ошибка 403</Title>
                У вас нет доступа к этой странице
            </div>
        </section>
    </default-layout>
</template>

<style scoped>

</style>
