function createLessonElement(lessonObject) {
    const element = document.createElement('div');
    const idElement = document.createElement('div');
    const commentElement = document.createElement('div');
    const lessonTypeElement = document.createElement('div');
    const dateElement = document.createElement('div');
    const buttonsElement = document.createElement('div');

    element.className = 'lesson';
    idElement.className = 'lesson__id lesson__component';
    commentElement.className = 'lesson__comment lesson__component';
    lessonTypeElement.className = 'lesson__type lesson__component';
    dateElement.className = 'lesson__date lesson__component';
    buttonsElement.className = 'lesson__buttons lesson_component';

    idElement.innerHTML = lessonObject['id'];
    commentElement.innerHTML = lessonObject['comment'];
    lessonTypeElement.innerHTML = lessonObject['type'];
    dateElement.innerHTML = lessonObject['date'];

    const editButtonElement = createEditLessonButton(lessonObject);
    const deleteButtonElement = createDeleteLessonButton(lessonObject, element);

    buttonsElement.appendChild(editButtonElement);
    buttonsElement.appendChild(deleteButtonElement);

    element.appendChild(idElement);
    element.appendChild(commentElement);
    element.appendChild(lessonTypeElement);
    element.appendChild(dateElement);
    element.appendChild(buttonsElement);

    return element;
}

function createEditLessonButton(lessonObject) {
    const element = document.createElement('i');

    element.className = 'fa fa-edit fa-lg lesson-button lesson-button-edit';

    element.setAttribute('title', 'Поки що не підтримується');

    element.addEventListener('click', () => {
        //todo
    });

    return element;
}

function createDeleteLessonButton(lessonObject, lessonElement) {
    const element = document.createElement('i');

    element.className = 'fa fa-trash fa-lg lesson-button';

    element.addEventListener('click', () => {
        requestLessonDelete(lessonObject['id'])
            .then(() => {
                //todo: delete lesson check

                const listElement = document.querySelector('#lessons-list');
                listElement.removeChild(lessonElement);
            })
            .catch(() => alert('Виникла помилка під час видалення заняття'));
    });

    return element;
}

function createGradeHeaderElement() {
    const lessons = Array.from(window['lessons']).reverse();

    const element = document.createElement('div');
    const numberElement = document.createElement('div');
    const nameElement = document.createElement('div');

    element.className = 'grades-header';
    numberElement.className = 'grades-header__component grade__number';
    nameElement.className = 'grades-header__component grade__name';

    numberElement.innerHTML = '№';
    nameElement.innerHTML = 'ПІБ';

    element.appendChild(numberElement);
    element.appendChild(nameElement);

    for (const lesson of lessons) {
        const lessonElement = document.createElement('div');
        lessonElement.className = 'grades-header__component';
        lessonElement.innerHTML = lesson['date'];
        lessonElement.setAttribute('title', lesson['type']);

        element.appendChild(lessonElement);
    }

    return element;
}

function createGradeListElement() {
    const element = document.createElement('div');

    element.className = 'grades-list';

    const grades = Object.values(window['grades']);

    let number = 0;
    for (const gradeObject of grades) {
        number++;
        const gradeElement = createGradeRow(gradeObject, number);
        element.appendChild(gradeElement);
    }

    return element;
}

function createGradeRow(gradeObject, number) {
    const element = document.createElement('div');
    const numberElement = document.createElement('div');
    const nameElement = document.createElement('div');

    element.className = 'grade-row';

    numberElement.innerHTML = number;
    nameElement.innerHTML = gradeObject['full-name'];

    numberElement.className = 'grade-row__component grade__number';
    nameElement.className = 'grade-row__component grade__name';

    element.appendChild(numberElement);
    element.appendChild(nameElement);

    for (const gradeValueKey in gradeObject['grades']) {
        const gradeValueElement = createGradeValueElement(gradeObject, gradeValueKey);

        element.appendChild(gradeValueElement);
    }

    return element;
}

function createGradeValueElement(gradeObject, gradeValueKey) {
    const element = document.createElement('div');

    console.log(gradeObject['grades'][gradeValueKey]['id']);

    element.className = 'grade-row__component';
    element.setAttribute('data-id-lesson', gradeValueKey);
    element.setAttribute('data-id-student', gradeObject['id']);
    element.setAttribute('data-id-grade', gradeObject['grades'][gradeValueKey]['id']);

    const selectElement = createGradeValueSelectElement();
    selectElement.value = gradeObject['grades'][gradeValueKey]['value'];

    const saveButtonElement = document.createElement('i');
    saveButtonElement.className = 'fa fa-check grade__save-button';

    selectElement.addEventListener('input', () => {
        const allowSave = selectElement.value !== gradeObject['grades'][gradeValueKey];
        saveButtonElement.classList.toggle('grade__save-button--allowed', allowSave);
    });

    saveButtonElement.addEventListener('click', () => {
        if (saveButtonElement.classList.contains('grade__save-button--allowed')) {
            const idGrade = element.getAttribute('data-id-grade');
            const value = selectElement.value;
            const idLesson = element.getAttribute('data-id-lesson');
            const idStudent = element.getAttribute('data-id-student');
            requestGradeUpdate(idGrade, value, idLesson, idStudent)
                .then((responseObject) => {
                    if (responseObject['status'] === 'success') {
                        saveButtonElement.classList.toggle('grade__save-button--allowed', false);
                    } else {
                        throw new Error(responseObject['message']);
                    }
                })
                .catch((error) => {
                    alert(error);
                });
        }
    });

    element.appendChild(selectElement);
    element.appendChild(saveButtonElement);

    return element;
}

function createGradeValueSelectElement() {
    const element = document.createElement('select');

    const nullElement = document.createElement('option');
    const absentElement = document.createElement('option');
    const presentElement = document.createElement('option');

    nullElement.value = null;
    absentElement.value = 'Відсутній';
    presentElement.value = 'Присутній';

    nullElement.innerHTML = '';
    absentElement.innerHTML = 'Відсутній';
    presentElement.innerHTML = 'Присутній';

    element.appendChild(nullElement);
    element.appendChild(absentElement);
    element.appendChild(presentElement);

    for (let gradeValue = 0; gradeValue <= 100; gradeValue++) {
        const gradeElement = document.createElement('option');
        gradeElement.value = `${gradeValue}`;
        gradeElement.innerHTML = `${gradeValue}`;

        element.appendChild(gradeElement);
    }

    return element;
}