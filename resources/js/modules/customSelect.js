//ИМпортирование
//import { initCustomSelect } from './modules/customSelect.js';

//Инициализация. В аргументе указываем надо ли открывать только одно меню true или false
//initCustomSelect(false);

//Пример html структуры (css уже любой можно)
/* <div class="form__custom-select-body" data-custom-select="Choose service">
    <select name="" id="" class="form__select">
        <option value="1" selected>service 1</option> //выбранная опция
        <option value="2">service 2</option>
        <option value="3">service 3</option>
        <option value="4">service 4</option>
    </select>
    <div class="form__custom-select custom-select-form">
        <div data-custom-select-field class="custom-select-form__field"></div>
        <ul data-custom-select-options class="custom-select-form__options">
            <li class="custom-select-form__option" data-custom-select-option="1">service 1</li>
            <li class="custom-select-form__option" data-custom-select-option="2">service 2</li>
            <li class="custom-select-form__option" data-custom-select-option="3">service 3</li>
            <li class="custom-select-form__option" data-custom-select-option="4">service 4</li>
        </ul>
    </div>
</div> */

//Посяснения всех дата атрибутов
//data-custom-select - основная оболочка, в значении можно указать надпись написанную по умолчанию, пока не выбрана ни одна надпись (будет отображаться только, если нет уже выбранной опции)
//data-custom-select-field - поле, в котором написан выбранная опция, либо надпись, что надо выбрать ее
//data-custom-select-options - список всех опций
//data-custom-select-option - одна опция (в значении должно быть value от оригинального option)

export function initCustomSelect(onlyOneOpen) {

    const customSelects = document.querySelectorAll("[data-custom-select]");
    if (customSelects.length) {
        customSelects.forEach(customSelect => {
            const originalSelect = customSelect.querySelector("select");
            customSelect.style.cssText = `position: relative;`;
            if (originalSelect) {
                originalSelect.style.display = "none";
                const selectedOption = originalSelect.querySelector("option[selected]");
                const customSelectField = customSelect.querySelector("[data-custom-select-field]");
                if (selectedOption) {
                    customSelectField.textContent = selectedOption.textContent;
                } else {
                    customSelectField.textContent = customSelect.dataset.customSelect;
                }

                customSelect.querySelector("[data-custom-select-options]").style.cssText = `
                    opacity: 0;
                    visibility: hidden;
                    transform: translate(0, 1.5rem);
                    pointer-events: none;
                    position: absolute;
            `;
            }
        });
    }

    document.addEventListener("click", e => {

    });
}
