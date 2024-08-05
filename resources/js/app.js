import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import VueInstantSearch from 'vue-instantsearch/vue3/es';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
//import "./modules/smoothScroll.js";
import Smooth from 'smooth-scrolling';


createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueInstantSearch)
            .mount(el)
    },
})



//?Импорт кастомного открывания картинок (снипет doi)
import customOpenImage from './modules/customOpenImage.js';
//?Импор Свайпера (снипет swp)
//import Swiper from 'swiper';
//import { Navigation, Pagination } from 'swiper/modules';


import { initCustomSelect } from './modules/customSelect.js';

//?Основные скрипты (делегирование, шапка)
import { delegationClick } from './modules/script.js';
//?Для открытия, закрытия бургера обязательно добавить эту ф-ию (только импортировать, запускать не надо)
import { closeMenu } from './modules/script.js';


//?Функция определения мобильного устройства
//import { isMobile } from "./modules/functions";
//?Импортирование плавного скролла


//?Галерея FancyBox
//import { Fancybox } from "@fancyapps/ui";
//import "@fancyapps/ui/dist/fancybox/fancybox.css";
//Fancybox.bind("[data-gallery]", {});
//<a href="img/3.jfif" data-fancybox="gallery" data-caption="Природа" class="block__item"><img src="img/3.jfif" alt="Природа"></a>

//?Динамический адаптив (  useDynamicAdapt()  )
//import { useDynamicAdapt } from './modules/dynamic.js'


window.addEventListener("DOMContentLoaded", windowLoad);
function windowLoad() {
    const smooth = new Smooth({
        native: true,
        section: document.querySelector('.wrapper'),
        ease: 0.1
    });
    smooth.init();
    new customOpenImage();
    delegationClick();
    //initCustomSelect(false);

    //Создание плавного скролла
    /*SmoothScroll({
        // Время скролла 400 = 0.4 секунды
        animationTime: 600,
        // Размер шага в пикселях
        stepSize: 75,
        // Дополнительные настройки:
        // Ускорение
        accelerationDelta: 30,
        // Максимальное ускорение
        accelerationMax: 2,
        // Поддержка клавиатуры
        keyboardSupport: true,
        // Шаг скролла стрелками на клавиатуре в пикселях
        arrowScroll: 50,
        // Pulse (less tweakable)
        // ratio of "tail" to "acceleration"
        pulseAlgorithm: true,
        pulseScale: 4,
        pulseNormalize: 1,
        // Поддержка тачпада
        touchpadSupport: true,
    });*/


    /*const it = document.querySelector("#image");
    const filePath = "img/accounts/49/cgtAygKUiwMM90OMW3kOBS0SOlNF1rh8GOgAYLlU.jpg";

    var dt  = new DataTransfer();

    const reader = new FileReader();

    // Convert the base64 data to a Blob object
    const blob = new Blob();

    // Create a File object from the Blob object
    const file = new File([blob], filePath.split('/').pop());
    dt.items.add(file);
    it.files = dt.files;

    console.log(it.files);*/

    //визуальное удаление изображения перенес в делегирование клика
    /*document.addEventListener("click", (e)=> {
        console.log(1)
        if (e.target.closest(".button-delete-image")) {
            console.log(111)
            const item = e.target.closest('.item-image');
            const image = item.querySelector("img");
            item.remove();
        }
    });*/
}


/*import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});*/



