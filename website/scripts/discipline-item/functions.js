function uploadLessons(idDiscipline) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id-discipline', idDiscipline);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/util/get/lessons.php');
        ajaxRequest.onload = () => {
            try {
                const responseObject = JSON.parse(ajaxRequest.responseText);
                resolve(responseObject);
            } catch (e) {
                console.log('error: ' + e);
                reject(e);
            }
        };
        ajaxRequest.send(formData);
    });
}

function uploadLessonTypes() {
    return new Promise((resolve, reject) => {
        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/util/get/lesson-types.php');
        ajaxRequest.onload = () => {
            try {
                const responseObject = JSON.parse(ajaxRequest.responseText);
                resolve(responseObject);
            } catch (e) {
                console.error('error: ' + e);
                reject(e);
            }
        };
        ajaxRequest.send();
    });
}