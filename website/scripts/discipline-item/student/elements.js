function createLessonElement(lessonObject) {
    const element = document.createElement('div');
    const idElement = document.createElement('div');
    const commentElement = document.createElement('div');
    const lessonTypeElement = document.createElement('div');
    const dateElement = document.createElement('div');

    element.className = 'lesson';
    idElement.className = 'lesson__id lesson__component';
    commentElement.className = 'lesson__comment lesson__component';
    lessonTypeElement.className = 'lesson__type lesson__component';
    dateElement.className = 'lesson__date lesson__component';

    idElement.innerHTML = lessonObject['id'];
    commentElement.innerHTML = lessonObject['comment'];
    lessonTypeElement.innerHTML = lessonObject['type'];
    dateElement.innerHTML = lessonObject['date'];

    element.appendChild(idElement);
    element.appendChild(commentElement);
    element.appendChild(lessonTypeElement);
    element.appendChild(dateElement);

    return element;
}