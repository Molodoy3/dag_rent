import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import VueInstantSearch from 'vue-instantsearch/vue3/es';

export default defineConfig({
    server: {
        hmr: {

        }
    },
    plugins: [
        VueInstantSearch,
        vue(),
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
