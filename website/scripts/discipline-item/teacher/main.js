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
    const lessons = window['lessons'];
    const listBlock = document.querySelector('.lessons-list-block__list');

    for (const lessonObject of lessons) {
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

function setUpGrades() {
    if (window['grades'] === undefined) {
        console.error('Оцінки ще не вспіли завантажитися');
        return;
    }
    const gradesElement = document.querySelector('.grades');

    const headerElement = createGradeHeaderElement();
    const listElement = createGradeListElement();

    gradesElement.appendChild(headerElement);
    gradesElement.appendChild(listElement);
}

function setUpHomework() {
    const homework = window['homework'];
    const listBlock = document.querySelector('.homework-list-block__list');

    for (const homeworkObject of homework) {
        const homeworkElement = createHomeworkElement(homeworkObject);

        listBlock.appendChild(homeworkElement);
    }
}

function setUpHomeworkForm() {
    const homeworkCreateDateElement = document.querySelector('#homework-form-create-date');
    const homeworkScheduleDateElement = document.querySelector('#homework-form-schedule-date');
    const homeworkTextElement = document.querySelector('#homework-form-text');
    const homeworkButtonElement = document.querySelector('#homework-form-button');

    const listBlock = document.querySelector('.homework-list-block__list');

    homeworkButtonElement.addEventListener('click', () => {
        const homeworkCreatedDateValue = homeworkCreateDateElement.value;
        const homeworkScheduledDateValue = homeworkScheduleDateElement.value;
        const homeworkTextValue = homeworkTextElement.value;

        requestHomeworkAdd({
            'id-discipline': ID_DISCIPLINE,
            'created-date': homeworkCreatedDateValue,
            'scheduled-date': homeworkScheduledDateValue,
            'text': homeworkTextValue
        }).then((responseObject) => {
            if (responseObject['status'] === 'success') {
                const newHomeworkElement = createHomeworkElement({
                    'id': responseObject['id'],
                    'created-date': homeworkCreatedDateValue,
                    'scheduled-date': homeworkScheduledDateValue,
                    'text': homeworkTextValue
                });
                listBlock.insertBefore(newHomeworkElement, listBlock.firstChild);
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
        .then((lessons) => {
            window['lessons'] = lessons;
        })
        .then(setUpLessons)
        .catch(() => alert('Помилка при завантаженні даних занять'));
    uploadLessonTypes()
        .then((lessonTypes) => {
            window['lesson-types'] = lessonTypes;
        })
        .then(setUpLessonsForm);
    uploadGrades(ID_DISCIPLINE)
        .then((grades) => {
            window['grades'] = grades['data'];
        })
        .then(setUpGrades)
        .catch((e) => {
            console.error(`${e}`);
            alert('Помилка при завантаженні оцінок');
        });
    uploadHomework(ID_DISCIPLINE)
        .then((homework) => {
            window['homework'] = homework;
        })
        .then(setUpHomework)
        .then(setUpHomeworkForm)
        .catch(() => alert('Помилка при завантаженні даних домашнього завдання'));
});