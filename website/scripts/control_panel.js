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
    onUserFileFormSubmit('student');
}

function onTeacherFormSubmit() {
    onUserFileFormSubmit('teacher');
}

function onUserFileFormSubmit(userTypePrefix) {
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

    const addButton = document.querySelector('#users-form__add-button');
    addButton.addEventListener('click', addNewUserElement);
}

function addNewUserElement() {
    const userItemsContainer = document.querySelector('.users-form__user-list');
    const newUserItemElement = buildUserItemElement();
    if (userItemsContainer.hasChildNodes()) {
        userItemsContainer.insertBefore(newUserItemElement, userItemsContainer.firstChild);
    } else {
        userItemsContainer.appendChild(newUserItemElement);
    }

    const userForm = document.querySelector('.users-form');
    userForm.scroll({top: 0});
}

function buildUserItemElement() {
    const userItemElement = document.createElement('div');

    const idElement = document.createElement('div');
    const firstNameElement = document.createElement('div');
    const middleNameElement = document.createElement('div');
    const lastNameElement = document.createElement('div');
    const emailElement = document.createElement('div');
    const roleElement = document.createElement('div');
    const groupElement = document.createElement('div');
    const buttonsElement = document.createElement('div');
    const addButtonElement = document.createElement('div');
    const updateButtonElement = document.createElement('div');
    const deleteButtonElement = document.createElement('div');

    userItemElement.className = 'user-item user-item--not-added';
    idElement.className = 'user-item__component user-item__id';
    firstNameElement.className = 'user-item__component user-item__first-name';
    middleNameElement.className = 'user-item__component user-item__middle-name';
    lastNameElement.className = 'user-item__component user-item__last-name';
    emailElement.className = 'user-item__component user-item__email';
    roleElement.className = 'user-item__component user-item__role';
    groupElement.className = 'user-item__component user-item__group';
    buttonsElement.className = 'user-item__component user-item__buttons';
    addButtonElement.className = 'user-item__button user-item__button-add fa fa-user-plus fa-2x';
    updateButtonElement.className = 'user-item__button user-item__button-update fa fa-user-edit fa-2x hidden';
    deleteButtonElement.className = 'user-item__button user-item__button-delete fa fa-trash fa-2x';

    firstNameElement.setAttribute('contenteditable', 'true');
    middleNameElement.setAttribute('contenteditable', 'true');
    lastNameElement.setAttribute('contenteditable', 'true');
    emailElement.setAttribute('contenteditable', 'true');
    roleElement.appendChild(buildUserItemRoleElement());
    groupElement.appendChild(buildUserItemGroupElement());
    buttonsElement.appendChild(addButtonElement);
    buttonsElement.appendChild(updateButtonElement);
    buttonsElement.appendChild(deleteButtonElement);

    const userItem = {
        'parent': userItemElement,
        'id': idElement,
        'first-name': firstNameElement,
        'middle-name': middleNameElement,
        'last-name': lastNameElement,
        'email': emailElement,
        'role': roleElement.children.item(0),
        'group': groupElement.children.item(0),
        'addButton': addButtonElement,
        'updateButton': updateButtonElement,
        'deleteButton': deleteButtonElement
    };

    for (const userItemKey in userItem) {
        const userItemValue = userItem[userItemKey];
        userItemValue.addEventListener('input', () => {
            updateButtonElement.classList.toggle('user-item__button-update--allowed', true);
        });
    }

    this['userItems'].push(userItem);

    addButtonElement.addEventListener('click', () => {
        requestUserAdd(userItem);
    });
    updateButtonElement.addEventListener('click', () => {
        if (updateButtonElement.classList.contains('user-item__button-update--allowed')) {
            requestUserUpdate(userItem);
        }
    });
    deleteButtonElement.addEventListener('click', () => {
        requestUserDelete(userItem);
    });

    userItemElement.appendChild(idElement);
    userItemElement.appendChild(firstNameElement);
    userItemElement.appendChild(middleNameElement);
    userItemElement.appendChild(lastNameElement);
    userItemElement.appendChild(emailElement);
    userItemElement.appendChild(roleElement);
    userItemElement.appendChild(groupElement);
    buttonsElement.appendChild(updateButtonElement);
    buttonsElement.appendChild(deleteButtonElement);
    userItemElement.appendChild(buttonsElement);

    return userItemElement;
}

function buildUserItemRoleElement() {
    const userRoles = this['userRoles'];
    const selectElement = document.createElement('select');
    for (const userRoleIndex in userRoles) {
        const newOptionElement = document.createElement('option');
        newOptionElement.value = userRoleIndex;
        // noinspection JSUnfilteredForInLoop
        if (userRoles[userRoleIndex] === 'Студент') {
            newOptionElement.selected = true;
        }
        // noinspection JSUnfilteredForInLoop
        newOptionElement.innerHTML = userRoles[userRoleIndex];
        selectElement.appendChild(newOptionElement);
    }
    return selectElement;
}

function buildUserItemGroupElement() {
    const groups = this['groups'];
    const selectElement = document.createElement('select');
    const defaultOption = document.createElement('option');
    defaultOption.selected = true;
    selectElement.appendChild(defaultOption);
    for (const groupIndex in groups) {
        const newOptionElement = document.createElement('option');
        newOptionElement.value = groupIndex;
        // noinspection JSUnfilteredForInLoop
        newOptionElement.innerHTML = groups[groupIndex];
        selectElement.appendChild(newOptionElement);
    }
    return selectElement;
}

function requestUserAdd(userItem) {
    const logMessageElement = document.querySelector('#users-form-log');

    const formData = new FormData();
    formData.append('first-name', userItem['first-name'].innerHTML.trim());
    formData.append('middle-name', userItem['middle-name'].innerHTML.trim());
    formData.append('last-name', userItem['last-name'].innerHTML.trim());
    formData.append('email', userItem['email'].innerHTML.trim());
    formData.append('role', userItem['role'].value);
    formData.append('group', userItem['group'].value);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/add_user.php', true);
    ajaxRequest.onload = () => {
        try {
            const responseObject = JSON.parse(ajaxRequest.response);
            const status = responseObject['status'];
            const logMessage = responseObject['log_message'];
            const newUserId = responseObject['user-id'];

            if (status === 'success') {
                userItem['parent'].classList.toggle('user-item--not-added', false);
                userItem['addButton'].remove();
                delete userItem['addButton'];
                userItem['updateButton'].classList.toggle('hidden', false);
                userItem['id'].innerHTML = newUserId;
                sortUsersByColumn();
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

    const userId = userItem['id'].innerHTML.trim();
    const userEmail = userItem['email'].innerHTML.trim();

    if (userItem['parent'].classList.contains('user-item--not-added')) {
        userItem['parent'].remove();
        const userItems = this['userItems'];
        userItems.splice(userItems.indexOf(userItem));
    } else {
        let field, value;
        if (userId === '') {
            field = 'email';
            value = userEmail;
        } else {
            field = 'id';
            value = userId;
        }

        const formData = new FormData();
        formData.append('user-field', field);
        formData.append('user-value', value);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/util/actions/control_panel/delete_user.php', true);
        ajaxRequest.onload = () => {
            try {
                const responseObject = JSON.parse(ajaxRequest.response);
                const status = responseObject['status'];
                const logMessage = responseObject['log_message'];

                if (status === 'success') {
                    const usersList = document.querySelector('.users-form__user-list');
                    const userItems = this['userItems'];
                    usersList.removeChild(userItem['parent']);
                    userItems.splice(userItems.indexOf(userItem), 1);
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
}

function sortUsersByColumn(column, side) {
    const userList = document.querySelector('.users-form__user-list');
    const userItems = this['userItems'];

    if (column === undefined) {
        column = 'id';
    }
    if (['role', 'group'].includes(column)) {
        userItems.sort((ui1, ui2) => {
            const firstValue = ui1[column].value;
            const secondValue = ui2[column].value;

            if (side === 'up') {
                return firstValue - secondValue;
            } else {
                return secondValue - firstValue;
            }
        });
    } else if (column === 'id') {
        userItems.sort((ui1, ui2) => {
            const firstValue = ui1['id'].innerHTML;
            const secondValue = ui2['id'].innerHTML;

            return firstValue - secondValue;
        });
    } else {
        userItems.sort((ui1, ui2) => {
            const firstValue = ui1[column].innerHTML.trim();
            const secondValue = ui2[column].innerHTML.trim();

            if (side === 'up') {
                return firstValue.localeCompare(secondValue);
            } else {
                return secondValue.localeCompare(firstValue);
            }
        });
    }
    userItems.forEach(userItem => userList.appendChild(userItem['parent']));
}

function uploadRoles() {
    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/get_user_roles.php');
    ajaxRequest.onload = () => {
        window['userRoles'] = JSON.parse(ajaxRequest.response);
    };
    ajaxRequest.send();
}

function uploadGroups() {
    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/get_groups.php');
    ajaxRequest.onload = () => {
        window['groups'] = JSON.parse(ajaxRequest.response);
    };
    ajaxRequest.send();
}

window.addEventListener('load', () => {
    setUpMenu();
    setUpStudentForm();
    setUpTeacherForm();
    setUpUserForm();
    setUpWorkDistributionFileForm();
    uploadRoles();
    uploadGroups();
});