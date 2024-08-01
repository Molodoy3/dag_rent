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
                <Title>Ошибка 404</Title>
                По данному URL адресу не найдена страница
            </div>
        </section>
    </default-layout>
</template>

<style scoped>

</style>
