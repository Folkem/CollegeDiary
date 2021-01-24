window.addEventListener('load', () => {
    const passwordForm = document.querySelector('#password-form');
    const avatarForm = document.querySelector('#avatar-form');
    const menuButtons = document.querySelectorAll('.menu-buttons__item');
    const menuContents = document.querySelectorAll('.menu-content__item');

    menuButtons.forEach((button, key) => {
        button.addEventListener('click', () => {
            menuContents.forEach((content) =>
                content.classList.toggle('hidden', true)
            );
            menuContents.item(key).classList.toggle('hidden', false);
            document.querySelector('.menu-buttons__item--active').classList
                .toggle('menu-buttons__item--active', false);
            button.classList.toggle('menu-buttons__item--active', true);
        });
    });

    passwordForm.addEventListener('submit', () => {
        const oldPassword = document.querySelector("#current-password").value;
        const newPassword = document.querySelector("#new-password").value;
        const repeatedNewPassword = document.querySelector("#repeated-new-password").value;

        const oldPasswordErrorBlock = document.querySelector('#current-password-error');
        const newPasswordErrorBlock = document.querySelector('#new-password-error');
        const repeatedNewPasswordErrorBlock = document.querySelector('#repeated-new-password-error');
        const passwordGeneralErrorBlock = document.querySelector('#password-result');
        const allErrorBlocks = {
            'old-password': oldPasswordErrorBlock,
            'new-password': newPasswordErrorBlock,
            'repeated-new-password': repeatedNewPasswordErrorBlock,
            'general': passwordGeneralErrorBlock
        };

        oldPasswordErrorBlock.innerHTML = '';
        newPasswordErrorBlock.innerHTML = '';
        repeatedNewPasswordErrorBlock.innerHTML = '';

        if (newPassword !== repeatedNewPassword) {
            newPasswordErrorBlock.innerHTML = 'Паролі не співпадають!';
            repeatedNewPasswordErrorBlock.innerHTML = 'Паролі не співпадають!';
        } else {
            const formData = new FormData();
            formData.append('action', 'password');
            formData.append('old-password', oldPassword);
            formData.append('new-password', newPassword);

            const ajaxRequest = new XMLHttpRequest();
            ajaxRequest.open('POST', '/util/actions/profile_update.php');
            ajaxRequest.onload = () => {
                try {
                    const parsedJsonResponse = JSON.parse(ajaxRequest.response);
                    const status = parsedJsonResponse['status'];
                    const message_places = parsedJsonResponse['message_places'];
                    const message = parsedJsonResponse['message'];

                    for (const errorBlockName in allErrorBlocks) {
                        allErrorBlocks[errorBlockName].innerHTML = '';
                    }

                    for (const message_place of message_places) {
                        allErrorBlocks[message_place].innerHTML = message;
                        if (message_place === 'general') {
                            if (status === 'success') {
                                passwordGeneralErrorBlock.className = 'form__success-text';
                            } else {
                                passwordGeneralErrorBlock.className = 'form__error-text';
                            }
                        }
                    }
                } catch (e) {
                    passwordGeneralErrorBlock.innerHTML = 'Виникла помилка на сервері. ' +
                        'Зверніться до розробника або спробуйте пізніше';
                    console.log(e);
                }
            };
            ajaxRequest.send(formData);
        }
    });

    const avatarInputButton = document.querySelector('#new-avatar-file-button');
    const avatarInput = document.querySelector('#new-avatar-file');
    avatarInputButton.addEventListener('click', () => {
        avatarInput.click();
    });
    avatarForm.addEventListener('submit', () => {
        const avatarResultElement = document.querySelector('#avatar-result');
        const avatarInputFile = avatarInput.files[0];

        const formData = new FormData();
        formData.append('action', 'avatar');
        formData.append('avatar-file', avatarInputFile);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/util/actions/profile_update.php', true);
        ajaxRequest.onload = () => {
            try {
                const parsedJsonResponse = JSON.parse(ajaxRequest.response);
                const status = parsedJsonResponse['status'];
                // noinspection UnnecessaryLocalVariableJS
                const message = parsedJsonResponse['message'];

                avatarResultElement.innerHTML = message;
                if (status === 'success') {
                    avatarResultElement.className = 'form__success-text';
                } else {
                    avatarResultElement.className = 'form__error-text';
                }
            } catch (e) {
                alert('Виникла помилка на сервері. ' +
                    'Зверніться до розробника або спробуйте пізніше');
                console.log(e);
            }
        };
        ajaxRequest.send(formData);
    });
});