function onFileFormSubmit(userTypePrefix) {
    const resultMessageElement = document.querySelector(`#${userTypePrefix}-table-result`);
    const logElement = document.querySelector(`#${userTypePrefix}-form-log`);

    const fileElement = document.querySelector(`#${userTypePrefix}-table-file`);

    if (fileElement.files.length === 0) {
        resultMessageElement.innerHTML = 'Виберіть файл для початку';
        return;
    }

    const file = document.querySelector(`#${userTypePrefix}-table-file`).files[0];
    const startRow = document.querySelector(`#${userTypePrefix}-table-start-row`).value;
    const nameCell = document.querySelector(`#${userTypePrefix}-table-name-cell`).value;
    const emailCell = document.querySelector(`#${userTypePrefix}-table-email-cell`).value;

    const formData = new FormData();
    formData.append('action', 'table-file');
    formData.append('user-type', userTypePrefix);
    formData.append('table-file', file);
    formData.append('start-row', startRow);
    formData.append('name-cell', nameCell);
    formData.append('email-cell', emailCell);

    const loader = document.querySelector(`#${userTypePrefix}-loader`);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/php/actions/user_file_form.php', true);
    ajaxRequest.onload = () => {
        try {
            const parsedJsonResponse = JSON.parse(ajaxRequest.response);
            const status = parsedJsonResponse['status'];
            // noinspection UnnecessaryLocalVariableJS
            const message = parsedJsonResponse['message'];
            const logMessage = parsedJsonResponse['log-message'];

            resultMessageElement.innerHTML = message;
            if (status === 'success') {
                resultMessageElement.className = 'form__response-text form__response-text--success';
            } else if (status === 'failure') {
                resultMessageElement.className = 'form__response-text form__response-text--error';
            } else {
                resultMessageElement.className = 'form__response-text form__response-text--warning';
            }
            logElement.innerHTML = logMessage;
        } catch (e) {
            alert('Виникла помилка на сервері. ' +
                'Зверніться до розробника або спробуйте пізніше');
            console.log(e);
        }
    };
    ajaxRequest.onloadstart = () => {
        resultMessageElement.innerHTML = '';
        resultMessageElement.className = 'form__response-text';
        loader.classList.toggle('loader--hidden', false);
    };
    ajaxRequest.onloadend = () => loader.classList.toggle('loader--hidden', true);
    ajaxRequest.send(formData);
}

function onMenuButtonClick(button, key) {
    const menuContents = document.querySelectorAll('.menu-content__item');
    menuContents.forEach((content) =>
        content.classList.toggle('hidden', true)
    );
    menuContents.item(key).classList.toggle('hidden', false);
    document.querySelector('.menu-buttons__item--active').classList
        .toggle('menu-buttons__item--active', false);
    button.classList.toggle('menu-buttons__item--active', true);
}

function setUpMenu() {
    const menuButtons = document.querySelectorAll('.menu-buttons__item');
    menuButtons.forEach((button, key) => {
        button.addEventListener('click', () =>
            onMenuButtonClick(button, key)
        );
    });
}

function uploadRoles() {
    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/php/get/user-roles.php');
    ajaxRequest.onload = () => {
        window['userRoles'] = JSON.parse(ajaxRequest.response);
    };
    ajaxRequest.send();
}

function uploadUsers(globalVariableName = 'users', roleId = '-1') {
    const ajaxRequest = new XMLHttpRequest();

    const formData = new FormData();
    formData.append('roleId', roleId);

    ajaxRequest.open('POST', '/php/get/users.php');
    ajaxRequest.onload = () => {
        window[globalVariableName] = JSON.parse(ajaxRequest.response);
    };
    ajaxRequest.send(formData);
}

function uploadGroups() {
    return new Promise((resolve, reject) => {
        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/get/groups.php');
        ajaxRequest.onload = () => {
            try {
                window['groups'] = JSON.parse(ajaxRequest.response);
                resolve();
            } catch (e) {
                reject(e);
            }
        };
        ajaxRequest.send();
    });
}

function uploadCallSchedule() {
    return new Promise((resolve, reject) => {
        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/get/call-schedule.php');
        ajaxRequest.onload = () => {
            try {
                window['call-schedule'] = JSON.parse(ajaxRequest.response);
                resolve();
            } catch (e) {
                reject(e);
            }
        };
        ajaxRequest.send();
    });
}

function uploadLessonSchedules() {
    return new Promise((resolve, reject) => {
        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/get/lesson-schedules.php');
        ajaxRequest.onload = () => {
            try {
                window['lesson-schedules'] = JSON.parse(ajaxRequest.response);
                resolve();
            } catch (e) {
                reject();
            }
        };
        ajaxRequest.send();
    });
}

function uploadReadableDisciplines() {
    return new Promise((resolve, reject) => {
        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/php/get/readable-disciplines.php');
        ajaxRequest.onload = () => {
            try {
                window['readable-disciplines'] = JSON.parse(ajaxRequest.response);
                resolve();
            } catch (e) {
                reject(e);
            }
        };
        ajaxRequest.send();
    });
}

function uploadLessonScheduleForGroup(groupId) {
    // todo: uploadLessonScheduleForGroup(groupId)
}