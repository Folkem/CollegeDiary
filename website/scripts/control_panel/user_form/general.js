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