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
    const listBlock = document.querySelector('.lessons-list-block__list');

    for (const lessonObject of lessonsArray) {
        const lessonElement = createLessonElement(lessonObject);

        listBlock.appendChild(lessonElement);
    }
}

function setUpLessonsForm() {
    const lessonDateElement = document.querySelector('#lessons-form-date');
    const lessonTypeElement = document.querySelector('#lessons-form-type');
    const lessonCommentElement = document.querySelector('#lessons-form-comment');
    const lessonButtonElement = document.querySelector('#lessons-form-button');

    const lessonTypes = window['lesson-types'];
    const listBlock = document.querySelector('.lessons-list-block__list');

    for (const lessonTypeKey in lessonTypes) {
        const value = lessonTypes[lessonTypeKey];

        const option = document.createElement('option');
        option.value = lessonTypeKey;
        option.innerHTML = value;

        lessonTypeElement.appendChild(option);
    }

    lessonButtonElement.addEventListener('click', () => {
        const lessonDateValue = lessonDateElement.value;
        const lessonTypeValue = lessonTypeElement.value;
        const lessonCommentValue = lessonCommentElement.value;

        requestLessonAdd({
            'id-discipline': ID_DISCIPLINE,
            'date': lessonDateValue,
            'type': lessonTypeValue,
            'comment': lessonCommentValue
        }).then((responseObject) => {
            if (responseObject['status'] === 'success') {
                const newLessonElement = createLessonElement({
                    'id': responseObject['id'],
                    'date': lessonDateValue,
                    'type': lessonTypes[lessonTypeValue],
                    'comment': lessonCommentValue
                });
                listBlock.insertBefore(newLessonElement, listBlock.firstChild);
            } else {
                alert(responseObject['message']);
            }
        }).catch((error) => {
            console.error(`error: ${error}`);
            alert('Необроблена помилка');
        });
    });
}

window.addEventListener('load', () => {
    setUpMenu();
    uploadLessons(ID_DISCIPLINE)
        .then(setUpLessons)
        .catch(() => alert('Помилка при завантаженні даних занять'));
    uploadLessonTypes()
        .then((lessonTypes) => {
            window['lesson-types'] = lessonTypes;
        })
        .then(setUpLessonsForm);
});