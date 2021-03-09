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

function setUpLessons() {
    const lessonsArray = window['lessons'];
    const listBlock = document.querySelector('.lessons-list');

    for (const lessonObject of lessonsArray) {
        const lessonElement = createLessonElement(lessonObject);

        listBlock.appendChild(lessonElement);
    }
}

function setUpGrades() {
    const gradesObject = Object.values(window['grades']).reverse();
    const gradesBlock = document.querySelector('.grades-list');

    for (const gradeObject of gradesObject) {
        if (gradeObject['grade'] === null) continue;

        const gradeElement = createGradeElement(gradeObject);

        gradesBlock.appendChild(gradeElement);
    }
}

window.addEventListener('load', () => {
    setUpMenu();
    uploadLessons(ID_DISCIPLINE)
        .then((responseObject) => window['lessons'] = responseObject)
        .then(setUpLessons);
    uploadGrades(ID_DISCIPLINE, ID_STUDENT)
        .then((responseObject) => window['grades'] = responseObject['data'])
        .then(setUpGrades);
});