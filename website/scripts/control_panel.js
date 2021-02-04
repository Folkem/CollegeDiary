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
    formData.append('user-type', userTypePrefix);
    formData.append('table-file', file);
    formData.append('start-row', startRow);
    formData.append('name-cell', nameCell);
    formData.append('email-cell', emailCell);

    const loader = document.querySelector(`#${userTypePrefix}-loader`);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/user_file_form.php', true);
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

function setUpUserForm() {
    const userItemsElements = document.querySelectorAll('.user-item');
    const userItems = [];
    Array.from(userItemsElements)
        .forEach((userItemElement) => {
            const childrenElements = Array.from(userItemElement.children);
            const idElement = childrenElements.filter(
                value => value.classList.contains('user-item__id')
            )[0];
            const firstNameElement = childrenElements.filter(
                value => value.classList.contains('user-item__first-name')
            )[0];
            const middleNameElement = childrenElements.filter(
                value => value.classList.contains('user-item__middle-name')
            )[0];
            const lastNameElement = childrenElements.filter(
                value => value.classList.contains('user-item__last-name')
            )[0];
            const emailElement = childrenElements.filter(
                value => value.classList.contains('user-item__email')
            )[0];
            const roleElement = childrenElements.filter(
                value => value.classList.contains('user-item__role')
            )[0].children.item(0);
            const groupElement = childrenElements.filter(
                value => value.classList.contains('user-item__group')
            )[0].children.item(0);
            const updateButtonElement = Array.from(
                childrenElements.filter(
                    value => value.classList.contains('user-item__buttons')
                )[0].children
            ).filter(
                value => value.classList.contains('user-item__button-update')
            )[0];
            const deleteButtonElement = Array.from(
                childrenElements.filter(
                    value => value.classList.contains('user-item__buttons')
                )[0].children
            ).filter(
                value => value.classList.contains('user-item__button-delete')
            )[0];

            const userItem = {
                'parent': userItemElement,
                'id': idElement,
                'first-name': firstNameElement,
                'middle-name': middleNameElement,
                'last-name': lastNameElement,
                'email': emailElement,
                'role': roleElement,
                'group': groupElement,
                'updateButton': updateButtonElement,
                'deleteButton': deleteButtonElement
            };

            userItems.push(userItem);

            updateButtonElement.addEventListener('click', () => {
                if (updateButtonElement.classList.contains('user-item__button-update--allowed')) {
                    requestUserUpdate(userItem);
                }
            });
            deleteButtonElement.addEventListener('click', () => {
                requestUserDelete(userItem);
            });

            for (const userItemKey in userItem) {
                const userItemValue = userItem[userItemKey];
                userItemValue.addEventListener('input', () => {
                    updateButtonElement.classList.toggle('user-item__button-update--allowed', true);
                });
            }
        });
    this['userItems'] = userItems;

    const userListSortButtons = Array.from(document.querySelectorAll('.users-form__sort-button'));
    for (const userListSortButton of userListSortButtons) {
        userListSortButton.addEventListener('click', () => {
            userListSortButtons
                .filter((button) => button !== userListSortButton)
                .forEach(
                    (button) => button.className = 'fa fa-sort users-form__sort-button'
                );
            if (userListSortButton.classList.contains('fa-sort')) {
                userListSortButton.classList.toggle('fa-sort', false);
                userListSortButton.classList.toggle('fa-sort-up', true);
                sortUsersByColumn(userListSortButton.getAttribute('data-role'), 'up');
            } else if (userListSortButton.classList.contains('fa-sort-up')) {
                userListSortButton.classList.toggle('fa-sort-up', false);
                userListSortButton.classList.toggle('fa-sort-down', true);
                sortUsersByColumn(userListSortButton.getAttribute('data-role'), 'down');
            } else {
                userListSortButton.classList.toggle('fa-sort-down', false);
                userListSortButton.classList.toggle('fa-sort', true);
                sortUsersByColumn();
            }
        });
    }
}

function requestUserUpdate(userItem) {
    const logMessageElement = document.querySelector('#users-form-log');

    const formData = new FormData();
    formData.append('id', userItem['id'].innerHTML.trim());
    formData.append('first-name', userItem['first-name'].innerHTML.trim());
    formData.append('middle-name', userItem['middle-name'].innerHTML.trim());
    formData.append('last-name', userItem['last-name'].innerHTML.trim());
    formData.append('email', userItem['email'].innerHTML.trim());
    formData.append('role', userItem['role'].value);
    formData.append('group', userItem['group'].value);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/update_user.php', true);
    ajaxRequest.onload = () => {
        try {
            const responseObject = JSON.parse(ajaxRequest.response);
            const status = responseObject['status'];
            const logMessage = responseObject['log_message'];

            if (status === 'success') {
                userItem['updateButton'].classList.toggle('user-item__button-update--allowed', false);
            } else {
                logMessageElement.innerHTML = logMessage;
            }
        } catch (e) {
            alert('Вибачте, виникла ще не оброблена помилка. Зверніться до адміністратора');
            console.log(e);
        }
    };
    ajaxRequest.send(formData);
}

function requestUserDelete(userItem) {
    const logMessageElement = document.querySelector('#users-form-log');

    const userId = userItem['id'].innerHTML;

    const formData = new FormData();
    formData.append('user-id', userId);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/delete_user.php', true);
    ajaxRequest.onload = () => {
        try {
            const responseObject = JSON.parse(ajaxRequest.response);
            const status = responseObject['status'];
            const logMessage = responseObject['log_message'];

            if (status === 'success') {
                const usersList = document.querySelector('.users-form__user-list');
                usersList.removeChild(userItem['parent']);
                this.userItems.splice(this.userItems.indexOf(userItem), 1);
            } else if (status === 'failure') {
                logMessageElement.innerHTML = logMessage;
            }
        } catch (e) {
            alert('Виникла необроблена досі помилка. Зверніться до адміністратора');
            console.log(e);
        }
    };
    ajaxRequest.send(formData);
}

function sortUsersByColumn(column, side) {
    const userList = document.querySelector('.users-form__user-list');

    if (column === undefined) {
        column = 'id';
    }
    if (['role', 'group'].includes(column)) {
        this.userItems.sort((ui1, ui2) => {
            const firstValue = ui1[column].value;
            const secondValue = ui2[column].value;

            if (side === 'up') {
                return firstValue - secondValue;
            } else {
                return secondValue - firstValue;
            }
        });
    } else if (column === 'id') {
        this.userItems.sort((ui1, ui2) => {
            const firstValue = ui1['id'].innerHTML;
            const secondValue = ui2['id'].innerHTML;

            return firstValue - secondValue;
        });
    } else {
        this.userItems.sort((ui1, ui2) => {
            const firstValue = ui1[column].innerHTML.trim();
            const secondValue = ui2[column].innerHTML.trim();

            if (side === 'up') {
                return firstValue.localeCompare(secondValue);
            } else {
                return secondValue.localeCompare(firstValue);
            }
        });
    }
    this.userItems.forEach(userItem => userList.appendChild(userItem['parent']));
}

window.addEventListener('load', () => {
    setUpMenu();
    setUpStudentForm();
    setUpTeacherForm();
    setUpUserForm();
});