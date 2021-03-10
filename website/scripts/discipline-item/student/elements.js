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

function createGradeElement(gradeObject) {
    const element = document.createElement('div');
    const gradeValueElement = document.createElement('div');
    const lessonTypeElement = document.createElement('div');
    const dateElement = document.createElement('div');

    element.className = 'grade';
    gradeValueElement.className = 'grade__value grade__component';
    lessonTypeElement.className = 'grade__type grade__component';
    dateElement.className = 'grade__date grade__component';

    gradeValueElement.innerHTML = gradeObject['grade'];
    lessonTypeElement.innerHTML = gradeObject['lesson']['type'];
    dateElement.innerHTML = gradeObject['date'];

    element.appendChild(gradeValueElement);
    element.appendChild(lessonTypeElement);
    element.appendChild(dateElement);

    return element;
}

function createHomeworkElement(homeworkObject) {
    const element = document.createElement('div');
    const idElement = document.createElement('div');
    const textElement = document.createElement('div');
    const createdDateElement = document.createElement('div');
    const scheduledDateElement = document.createElement('div');

    element.className = 'lesson';
    idElement.className = 'homework__id homework__component';
    textElement.className = 'homework__comment homework__component';
    createdDateElement.className = 'homework__date homework__component';
    scheduledDateElement.className = 'homework__date homework__component';

    idElement.innerHTML = homeworkObject['id'];
    textElement.innerHTML = homeworkObject['text'];
    createdDateElement.innerHTML = homeworkObject['created-date'];
    scheduledDateElement.innerHTML = homeworkObject['scheduled-date'];

    element.appendChild(idElement);
    element.appendChild(textElement);
    element.appendChild(createdDateElement);
    element.appendChild(scheduledDateElement);

    return element;
}