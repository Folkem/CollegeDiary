function setUpMenu() {
    const buttonElements = document.querySelectorAll('.menu-button');
    const contentBlockElements = document.querySelectorAll('.menu-content-block');

    buttonElements.forEach(buttonElement => {
        buttonElement.addEventListener('click', () => {
            if (buttonElement.hasAttribute('data-block')) {
                const contentBlockId = buttonElement.getAttribute('data-block');
                const contentBlockElement = document.querySelector(`#${contentBlockId}`);

                if (contentBlockElement != null) {
                    contentBlockElements.forEach(contentBlockElement =>
                        contentBlockElement.classList.toggle('hidden', true));
                    contentBlockElement.classList.toggle('hidden', false);

                    buttonElements.forEach(buttonElement =>
                        buttonElement.classList.toggle('menu-button--selected', false));
                    buttonElement.classList.toggle('menu-button--selected', true);
                }
            }
        });
    });
}

function setUpLessons(lessonsArray) {
    const listBlock = document.querySelector('.lessons-list');

    for (const lessonObject of lessonsArray) {
        const lessonElement = createLessonElement(lessonObject);

        listBlock.appendChild(lessonElement);
    }
}

window.addEventListener('load', () => {
    setUpMenu();
    uploadLessons(ID_DISCIPLINE)
        .then(setUpLessons);
});