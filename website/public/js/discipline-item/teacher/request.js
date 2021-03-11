function requestLessonDelete(idLesson) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id-lesson', idLesson);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/delete/lesson.php');
        ajaxRequest.onload = () => {
            try {
                const responseObject = ajaxRequest.responseText;
                resolve(responseObject);
            } catch (e) {
                console.error('error: ' + e);
                reject(e);
            }
        }
        ajaxRequest.send(formData);
    });
}

function requestLessonAdd(lessonObject) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id-discipline', lessonObject['id-discipline']);
        formData.append('date', lessonObject['date']);
        formData.append('type', lessonObject['type']);
        formData.append('comment', lessonObject['comment']);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/add/lesson.php');
        ajaxRequest.onload = () => {
            try {
                const responseObject = JSON.parse(ajaxRequest.responseText);
                resolve(responseObject);
            } catch (e) {
                console.error('error: ' + e);
                reject(e);
            }
        };
        ajaxRequest.send(formData);
    });
}

function requestGradeUpdate(idGrade, value, idLesson = -1, idStudent = -1) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id-grade', idGrade);
        formData.append('value', value);
        if (idLesson !== -1) formData.append('id-lesson', `${idLesson}`);
        if (idStudent !== -1) formData.append('id-student', `${idStudent}`);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/update/grade.php');
        ajaxRequest.onload = () => {
            try {
                const responseObject = JSON.parse(ajaxRequest.responseText);
                resolve(responseObject);
            } catch (e) {
                console.error(`error: ${e}`);
                reject(e);
            }
        };
        ajaxRequest.send(formData);
    });
}

function requestHomeworkDelete(idHomework) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id-homework', idHomework);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/delete/homework.php');
        ajaxRequest.onload = () => {
            try {
                const responseObject = ajaxRequest.responseText;
                resolve(responseObject);
            } catch (e) {
                console.error(`error: ${e}`);
                reject(e);
            }
        };
        ajaxRequest.send(formData);
    });
}

function requestHomeworkAdd(homeworkObject) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id-discipline', homeworkObject['id-discipline']);
        formData.append('created-date', homeworkObject['created-date']);
        formData.append('scheduled-date', homeworkObject['scheduled-date']);
        formData.append('text', homeworkObject['text']);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/add/homework.php');
        ajaxRequest.onload = () => {
            try {
                const responseObject = JSON.parse(ajaxRequest.responseText);
                resolve(responseObject);
            } catch (e) {
                console.error(`error: ${e}`);
                reject(e);
            }
        };
        ajaxRequest.send(formData);
    });
}