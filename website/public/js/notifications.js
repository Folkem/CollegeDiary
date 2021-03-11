function getNotificationObjects() {
    return Array.from(document.querySelectorAll('.notification'))
        .map((notificationElement, index, array) => {
            const children = notificationElement.children;
            const notificationId = Array.from(children)
                .filter((child) => child.className === 'notification__id')
                [0].innerHTML.toString();
            const notificationButton = Array.from(
                Array.from(children)
                    .filter((child) => child.className === 'notification__buttons')
                    [0].children
            ).filter((child) => child.classList.contains('read-button'))[0];

            return {
                'notification': notificationElement,
                'notificationId': notificationId,
                'notificationButton': notificationButton
            };
        });
}

function sortNotifications() {
    const readStatusValues = {
        'read': 1,
        'unread': 2
    };
    const notificationContainer = document.querySelector('#notification-list');
    const currentNotificationsArray = Array.from(document.querySelectorAll('.notification'))
        .map((notificationElement) => {
            const children = notificationElement.children;
            const notificationDate = Array.from(children)
                .filter((child) => child.className === 'notification__date')
                [0].innerHTML.toString();
            const notificationTime = Array.from(children)
                .filter((child) => child.className === 'notification__date-pure')
                [0].innerHTML.toString();
            const notificationReadStatus =
                notificationElement.classList.contains('notification--read') ?
                readStatusValues['read'] : readStatusValues['unread'];

            return {
                'elem': notificationElement,
                'date': notificationDate,
                'time': notificationTime,
                'readStatus': notificationReadStatus
            };
        });
    currentNotificationsArray.sort(
        (notificationOne, notificationTwo) => {
            const readStatusComparison = notificationTwo.readStatus - notificationOne.readStatus;
            const dateComparison = notificationOne.date > notificationTwo.date;
            const timeComparison = notificationOne.date > notificationTwo.date;

            return readStatusComparison | dateComparison | timeComparison;
        }
    );

    currentNotificationsArray.forEach(notificationElement =>
        notificationContainer.append(notificationElement.elem)
    );
}

function showPopupMessage() {
    const popupMessageElement = document.querySelector('#pop-up-message');
    popupMessageElement.classList.toggle('hidden', false);
}

function hidePopupMessage(event) {
    const popupMessageElement = document.querySelector('#pop-up-message');
    if (event.target !== popupMessageElement) return;

    popupMessageElement.classList.toggle('hidden', true);
}

function getDocumentTitleWithOneNotificationLesser() {
    const originalTitle = document.title;
    const startIndex = originalTitle.indexOf('(') + 1;
    const endIndex = originalTitle.indexOf(')');
    const oldNotificationCount = document.title.substring(startIndex, endIndex);
    const newNotificationCount = oldNotificationCount - 1;
    const notificationTitlePart = (newNotificationCount === 0 ? '' : '(' + newNotificationCount + ')');
    const newTitle = 'Повідомлення ' + notificationTitlePart + ' — Онлайн-щоденник';

    return newTitle;
}

function markReadNotifications(notificationIds) {
    const notificationObjects = getNotificationObjects();
    sendRequest(
        'mark-read',
        notificationIds,
        function () {
            try {
                const popupMessageContent = document.querySelector('#pop-up-message-content');
                const parsedJsonResponse = JSON.parse(this.response);
                const status = parsedJsonResponse['status'];
                const updatedNotifications = parsedJsonResponse['notifications'];
                // noinspection UnnecessaryLocalVariableJS
                const message = parsedJsonResponse['message'];

                notificationObjects.forEach((object) => {
                    if (updatedNotifications.includes(object['notificationId']) &&
                        object['notification'].classList.contains('notification--unread')) {
                        object['notificationButton']?.parentNode
                            .removeChild(object['notificationButton']);
                        object['notification'].classList.toggle('notification--unread', false);
                        object['notification'].classList.toggle('notification--read', true);
                        document.title = getDocumentTitleWithOneNotificationLesser();
                    }
                });
                popupMessageContent.innerHTML = message;
                if (status === 'warning') {
                    popupMessageContent.classList.toggle('pop-up-message__content--warning', true);
                    popupMessageContent.classList.toggle('pop-up-message__content--error', false);
                    showPopupMessage();
                } else if (status === 'failure') {
                    popupMessageContent.classList.toggle('pop-up-message__content--warning', false);
                    popupMessageContent.classList.toggle('pop-up-message__content--error', true);
                    showPopupMessage();
                }
                sortNotifications();
            } catch (e) {
                alert('Виникла помилка на сервері. ' +
                    'Зверніться до розробника або спробуйте пізніше');
                console.log(e);
            }
        }
    );
}

function deleteNotifications(notificationIds) {
    const notificationObjects = getNotificationObjects();
    sendRequest(
        'delete',
        notificationIds,
        function () {
            try {
                const popupMessageContent = document.querySelector('#pop-up-message-content');
                const parsedJsonResponse = JSON.parse(this.response);
                const status = parsedJsonResponse['status'];
                const updatedNotifications = parsedJsonResponse['notifications'];
                // noinspection UnnecessaryLocalVariableJS
                const message = parsedJsonResponse['message'];

                notificationObjects.forEach((object) => {
                    if (updatedNotifications.includes(object['notificationId'])) {
                        if (object['notification'].classList.contains('notification--unread')) {
                            document.title = getDocumentTitleWithOneNotificationLesser();
                        }
                        object['notification'].parentNode.removeChild(object['notification']);
                    }
                });
                popupMessageContent.innerHTML = message;
                if (status === 'warning') {
                    popupMessageContent.classList.toggle('pop-up-message__content--warning', true);
                    popupMessageContent.classList.toggle('pop-up-message__content--error', false);
                    showPopupMessage();
                } else if (status === 'failure') {
                    popupMessageContent.classList.toggle('pop-up-message__content--warning', false);
                    popupMessageContent.classList.toggle('pop-up-message__content--error', true);
                    showPopupMessage();
                }
                sortNotifications();
            } catch (e) {
                alert('Виникла помилка на сервері. ' +
                    'Зверніться до розробника або спробуйте пізніше');
                console.log(e);
            }
        }
    );
}

function sendRequest(action, notificationIds, onloadCallback) {
    const formData = new FormData();
    formData.append('action', action);
    formData.append('notifications', JSON.stringify(notificationIds));

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/php/actions/notifications.php', true);
    ajaxRequest.onload = onloadCallback;
    ajaxRequest.send(formData);
}

window.addEventListener('load', () => {
    const notificationIds = Array.from(
        document.querySelectorAll('.notification__id')
    ).map(value => value.innerHTML);
    const markReadAllButton = document.querySelector('#read-all-button');
    const deleteAllButton = document.querySelector('#delete-all-button');
    const markReadButtons = document.querySelectorAll('.read-button');
    const deleteButtons = document.querySelectorAll('.delete-button');
    const popupMessage = document.querySelector('#pop-up-message');

    markReadAllButton.addEventListener(
        'click',
        () => markReadNotifications(notificationIds)
    );
    deleteAllButton.addEventListener(
        'click',
        () => deleteNotifications(notificationIds)
    );
    markReadButtons.forEach((button, key) =>
        button.addEventListener(
            'click',
            () => markReadNotifications([notificationIds[key]])
        )
    );
    deleteButtons.forEach((button, key) =>
        button.addEventListener(
            'click',
            () => deleteNotifications([notificationIds[key]])
        )
    );
    popupMessage.addEventListener('click', hidePopupMessage);
});