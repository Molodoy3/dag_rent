import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import VueInstantSearch from 'vue-instantsearch/vue3/es';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import "./echo.js";

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
import "./modules/smoothScroll.js"

//?Галерея FancyBox
//import { Fancybox } from "@fancyapps/ui";
//import "@fancyapps/ui/dist/fancybox/fancybox.css";
//Fancybox.bind("[data-gallery]", {});
//<a href="img/3.jfif" data-fancybox="gallery" data-caption="Природа" class="block__item"><img src="img/3.jfif" alt="Природа"></a>

//?Динамический адаптив (  useDynamicAdapt()  )
//import { useDynamicAdapt } from './modules/dynamic.js'


window.addEventListener("DOMContentLoaded", windowLoad);
function windowLoad() {

    new customOpenImage();
    delegationClick();
    //initCustomSelect(false);

    //Создание плавного скролла
    SmoothScroll({
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
    });


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

    //обработка фронта изображений для аккаунтов
    let images;
    const form = document.querySelector("#formImage");
    if (form) {
        const fileInput = form.querySelector("input[type='file']");
        fileInput.addEventListener("change", e => {
            images = e.target.files;
            for (let i = 0; i < images.length; i++) {
                const wrap = form.querySelector(".form__image");
                const item = document.createElement('div');
                item.classList.add("item-image");
                wrap.appendChild(item);
                const image = images[i];
                const imageUrl = URL.createObjectURL(image);
                //data-image-numb="${i}"
                item.innerHTML += `<img data-open-image src="${imageUrl}" alt="image">`;
                const closeButton = document.createElement('div');
                closeButton.classList.add("button-delete-image");
                closeButton.innerText = 'X';
                item.appendChild(closeButton);
              }
        });
    }
    document.addEventListener("click", (e)=> {
        if (e.target.closest(".button-delete-image")) {
            const item = e.target.closest('.item-image');
            const image = item.querySelector("img");
            item.remove();
        }
    });
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



