<script setup lang="ts">

import Title from "../Components/Title.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import LabelInput from "../Components/LabelInput.vue";
import Button from "../Components/Button.vue";
import {Head, useForm} from "@inertiajs/vue3";
import InputError from "../Components/InputError.vue";

const props = defineProps({
    "errors": Object
});

const form = useForm({
    "name": ""
});

function submit() {
    form.post(route('platforms.store'));
}

</script>

<template>
    <DefaultLayout>
        <Head title="Добавление новой платформы"></Head>
        <section class="new-user">
            <div class="new-user__container">
                <Title>Добавление новой платформы</Title>
                <form @submit.prevent="submit" action="#" class="create__form">
                    <LabelInput for="name">Название платформы</LabelInput>
                    <input class="input" id="name" v-model="form.name"
                           :class="{ 'error': props.errors.name }" />
                    <InputError v-if="props.errors.name">{{ props.errors.name }}</InputError>

                    <Button type="submit" :disabled="form.processing"
                            :class="{ 'processing': form.processing }">Добавить</Button>
                </form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
