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

    element.className = 'fa fa-edit fa-lg lesson-button';

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