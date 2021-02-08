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