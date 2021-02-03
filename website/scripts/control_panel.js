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

function setUpStudentForm() {
    const fileInputButton = document.querySelector('#student-table-file-button');
    const fileInput = document.querySelector('#student-table-file');
    fileInputButton.addEventListener('click', () => {
        fileInput.click();
    });
    fileInput.addEventListener('change', () => {
        fileInputButton.classList.toggle('form__input-button--success', true);
    });

    const form = document.querySelector('#student-form');
    form.addEventListener('submit', onStudentFormSubmit);
}

function setUpTeacherForm() {
    const fileInputButton = document.querySelector('#teacher-table-file-button');
    const fileInput = document.querySelector('#teacher-table-file');
    fileInputButton.addEventListener('click', () => {
        fileInput.click();
    });
    fileInput.addEventListener('change', () => {
        fileInputButton.classList.toggle('form__input-button--success', true);
    });

    const form = document.querySelector('#teacher-form');
    form.addEventListener('submit', onTeacherFormSubmit);
}

function onStudentFormSubmit() {
    onFileFormSubmit('student');
}

function onTeacherFormSubmit() {
    onFileFormSubmit('teacher');
}

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
    formData.append('user-type', 'student');
    formData.append('table-file', file);
    formData.append('start-row', startRow);
    formData.append('name-cell', nameCell);
    formData.append('email-cell', emailCell);

    const loader = document.querySelector(`#${userTypePrefix}-loader`);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/file_form.php', true);
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

window.addEventListener('load', () => {
    setUpMenu();
    setUpStudentForm();
    setUpTeacherForm();
});