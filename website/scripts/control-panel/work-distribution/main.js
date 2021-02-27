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
    ajaxRequest.open('POST', '/util/actions/control-panel/work_distribution_file_form.php', true);
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

function setUpWorkDistributionTable() {
    const recordElements = document.querySelectorAll('.work-distribution-item');
    const records = [];
    Array.from(recordElements)
        .forEach((recordElement) => {
            const childrenElements = Array.from(recordElement.children);
            const idElement = childrenElements.filter(
                value => value.classList.contains('work-distribution-item__id')
            )[0];
            const subjectElement = childrenElements.filter(
                value => value.classList.contains('work-distribution-item__subject')
            )[0];
            const teacherElement = childrenElements.filter(
                value => value.classList.contains('work-distribution-item__teacher')
            )[0].children.item(0);
            const groupElement = childrenElements.filter(
                value => value.classList.contains('work-distribution-item__group')
            )[0].children.item(0);
            const updateButtonElement = Array.from(
                childrenElements.filter(
                    value => value.classList.contains('work-distribution-item__buttons')
                )[0].children
            ).filter(
                value => value.classList.contains('work-distribution-item__button-update')
            )[0];
            const deleteButtonElement = Array.from(
                childrenElements.filter(
                    value => value.classList.contains('work-distribution-item__buttons')
                )[0].children
            ).filter(
                value => value.classList.contains('work-distribution-item__button-delete')
            )[0];

            const record = {
                'parent': recordElement,
                'id': idElement,
                'subject': subjectElement,
                'teacher': teacherElement,
                'group': groupElement,
                'updateButton': updateButtonElement,
                'deleteButton': deleteButtonElement
            };

            records.push(record);

            updateButtonElement.addEventListener('click', () => {
                if (updateButtonElement.classList.contains('work-distribution-item__button-update--allowed')) {
                    requestWorkDistributionRecordUpdate(record);
                }
            });
            deleteButtonElement.addEventListener('click', () => {
                requestWorkDistributionRecordDelete(record);
            });

            for (const recordKey in record) {
                const recordValue = record[recordKey];
                recordValue.addEventListener('input', () => {
                    updateButtonElement.classList.toggle('work-distribution-item__button-update--allowed', true);
                });
            }
        });
    this['records'] = records;

    const recordListSortButtons = Array.from(document.querySelectorAll('.work-distribution-form__sort-button'));
    for (const recordListSortButton of recordListSortButtons) {
        recordListSortButton.addEventListener('click', () => {
            recordListSortButtons
                .filter((button) => button !== recordListSortButton)
                .forEach(
                    (button) => button.className = 'fa fa-sort work-distribution-form__sort-button'
                );
            if (recordListSortButton.classList.contains('fa-sort')) {
                recordListSortButton.classList.toggle('fa-sort', false);
                recordListSortButton.classList.toggle('fa-sort-up', true);
                sortWorkDistributionRecordsByColumn(recordListSortButton.getAttribute('data-role'), 'up');
            } else if (recordListSortButton.classList.contains('fa-sort-up')) {
                recordListSortButton.classList.toggle('fa-sort-up', false);
                recordListSortButton.classList.toggle('fa-sort-down', true);
                sortWorkDistributionRecordsByColumn(recordListSortButton.getAttribute('data-role'), 'down');
            } else {
                recordListSortButton.classList.toggle('fa-sort-down', false);
                recordListSortButton.classList.toggle('fa-sort', true);
                sortWorkDistributionRecordsByColumn();
            }
        });
    }

    const addButton = document.querySelector('#work-distribution-form__add-button');
    addButton.addEventListener('click', addNewWorkDistributionRecordElement);
}

function sortWorkDistributionRecordsByColumn(column, side) {
    const userList = document.querySelector('.work-distribution-form__records-list');
    const records = this['records'];

    if (column === undefined) {
        column = 'id';
    }
    if (['teacher', 'group'].includes(column)) {
        records.sort((ui1, ui2) => {
            const firstValue = ui1[column].value;
            const secondValue = ui2[column].value;

            if (side === 'up') {
                return firstValue - secondValue;
            } else {
                return secondValue - firstValue;
            }
        });
    } else if (column === 'id') {
        records.sort((ui1, ui2) => {
            const firstValue = ui1['id'].innerHTML;
            const secondValue = ui2['id'].innerHTML;

            return firstValue - secondValue;
        });
    } else {
        records.sort((ui1, ui2) => {
            const firstValue = ui1[column].innerHTML.trim();
            const secondValue = ui2[column].innerHTML.trim();

            if (side === 'up') {
                return firstValue.localeCompare(secondValue);
            } else {
                return secondValue.localeCompare(firstValue);
            }
        });
    }
    records.forEach(userItem => userList.appendChild(userItem['parent']));
}

window.addEventListener('load', () => {
    setUpMenu();
    setUpWorkDistributionFileForm();
    setUpWorkDistributionTable();
    uploadGroups();
    uploadUsers('teachers', 4 /* teacher */);
});