<script setup>

import Header from "../Components/Main/Header.vue";
import Footer from "../Components/Main/Footer.vue";
</script>

<template>
    <div class="wrapper">
        <Header></Header>
        <main class="page">
            <slot/>
        </main>
        <Footer></Footer>
    </div>
</template>

<style scoped>

</style>
