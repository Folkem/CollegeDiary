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
    ajaxRequest.open('POST', '/util/add/user.php', true);
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
                userItem['updateButton'].classList.toggle('user-item__button-update--allowed', false);
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
    ajaxRequest.open('POST', '/util/update/user.php', true);
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
        ajaxRequest.open('POST', '/util/delete/user.php', true);
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