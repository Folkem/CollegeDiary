function setUpWorkDistributionFileForm() {
    const fileInputButton = document.querySelector('#work-distribution-table-file-button');
    const fileInput = document.querySelector('#work-distribution-table-file');
    fileInputButton.addEventListener('click', () => {
        fileInput.click();
    });
    fileInput.addEventListener('change', () => {
        fileInputButton.classList.toggle('form__input-button--success', true);
    });

    const form = document.querySelector('#work-distribution-form');
    form.addEventListener('submit', onWorkDistributionFileFormSubmit);
}

function onWorkDistributionFileFormSubmit() {
    const resultMessageElement = document.querySelector(`#work-distribution-table-result`);
    const logElement = document.querySelector(`#work-distribution-file-form-log`);

    const fileElement = document.querySelector(`#work-distribution-table-file`);

    if (fileElement.files.length === 0) {
        resultMessageElement.innerHTML = 'Виберіть файл для початку';
        return;
    }

    const file = document.querySelector(`#work-distribution-table-file`).files[0];
    const startRow = document.querySelector(`#work-distribution-table-start-row`).value;
    const nameCell = document.querySelector(`#work-distribution-table-name-cell`).value;
    const subjectCell = document.querySelector(`#work-distribution-table-subject-cell`).value;
    const groupCell = document.querySelector(`#work-distribution-table-group-cell`).value;

    const formData = new FormData();
    formData.append('action', 'table-file');
    formData.append('table-file', file);
    formData.append('start-row', startRow);
    formData.append('name-cell', nameCell);
    formData.append('group-cell', groupCell);
    formData.append('subject-cell', subjectCell);

    const loader = document.querySelector(`#work-distribution-loader`);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/work_distribution_file_form.php', true);
    // todo: remove result message element and move all messages to log message element
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