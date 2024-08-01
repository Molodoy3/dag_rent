<script setup lang="ts">

import Title from "../Components/Title.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import LabelInput from "../Components/LabelInput.vue";
import Button from "../Components/Button.vue";
import {Head, router, useForm} from "@inertiajs/vue3";
import InputError from "../Components/InputError.vue";

const props = defineProps({
    "platform": Object,
    "errors": Object
});

const form = useForm(props.platform);

function submit() {
    form.patch(route('platforms.update', props.platform.id));
}
function deleteAccount() {
    if (confirm("Вы уверены, что хотите удалить игру?"))
        form.delete(route('platforms.destroy', props.platform.id));
}

</script>

<template>
    <DefaultLayout>
        <Head :title="'Редактирование платформы ' + platform.name"></Head>
        <section class="new-user">
            <div class="new-user__container">
                <Title>Редактирование платформы {{platform.name}}</Title>
                <form @submit.prevent="submit" action="#" class="create__form">
                    <LabelInput for="name">Название платформы</LabelInput>
                    <input class="input" id="name" v-model="form.name"
                           :class="{ 'error': props.errors.name }" />
                    <InputError v-if="props.errors.name">{{ props.errors.name }}</InputError>

                    <div class="buttons" style="margin-bottom: 15px">
                        <Button type="submit" :disabled="form.processing"
                                :class="{ 'processing': form.processing }">Сохранить</Button>
                        <div @click="deleteAccount" class="button button_red"
                             :class="{ 'processing': form.processing }">Удалить</div>
                    </div>
                </form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>

</style>
