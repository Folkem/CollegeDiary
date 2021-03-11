function onMenuButtonClick(button, key) {
    const menuContents = document.querySelectorAll('.menu-content__item');
    menuContents.forEach((content) =>
        content.classList.toggle('hidden', true)
    );
    menuContents.item(key).classList.toggle('hidden', false);
    document.querySelector('.menu-buttons__item--active').classList
        .toggle('menu-buttons__item--active', false);
    button.classList.toggle('menu-buttons__item--active', true);
}

window.addEventListener('load', () => {
    const menuButtons = document.querySelectorAll('.menu-buttons__item');

    menuButtons.forEach((button, key) =>
        button.addEventListener('click', () => onMenuButtonClick(button, key))
    );
});