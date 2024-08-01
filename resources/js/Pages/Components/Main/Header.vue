<script setup>
import {Link, router} from "@inertiajs/vue3";


</script>

<template>
    <header class="header">
        <div class='header__container'>
            <Link :href="route('accounts.index')" class="header__logo logo">
                <img src='../../../../img/logo.png' alt='logo'>
            </Link>
            <div class='header__menu menu'>
                <div class='menu__icon'>
                    <span></span>
                </div>
                <nav class='menu__body'>
                    <ul class='menu__list'>
                        <li><Link :href="route('accounts.index')" class='menu__link'>Аккаунты</Link></li>
                        <li><Link :href="route('games.index')" class='menu__link'>Игры</Link></li>
                        <li><Link :href="route('platforms.index')" class='menu__link'>Платформы</Link></li>
                        <li><Link :href="route('statistics.index')" class='menu__link'>Статистика</Link></li>
                    </ul>
                </nav>
            </div>
            <Link v-if="$page.props.auth.id" :href="route('user.show', $page.props.auth.id)"  class="header__profile button">Профиль</Link>
            <Link v-else :href="route('user.login')" class="header__profile button">Войти</Link>
        </div>
    </header>
</template>

<style scoped>

</style>
