function requestLessonDelete(idLesson) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id-lesson', idLesson);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/util/delete/lesson.php');
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
        ajaxRequest.open('POST', '/util/add/lesson.php');
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