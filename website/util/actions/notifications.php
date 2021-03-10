<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

if (isset($_POST['action'], $_POST['notifications'], $currentUser)) {
    $action = $_POST['action'];
    $notifications = $_POST['notifications'];
    $response = [];

    $notifications = json_decode($notifications);
    if (gettype($notifications) === 'array') {
        if (count($notifications) > 0) {
            switch ($action) {
                case 'mark-read':
                    $result = markReadNotifications($currentUser->getId(), $notifications);
                    $response['status'] = $result['status'];
                    $response['message'] = $result['message'];
                    $response['notifications'] = $result['notifications'];
                    break;
                case 'delete':
                    $response = deleteNotifications($currentUser->getId(), $notifications);
                    break;
                default:
                    $response['status'] = 'failure';
                    $response['message'] = 'Відправлені дані некоректні';
                    break;
            }
        } else {
            $response['status'] = 'failure';
            $response['message'] = 'На запит було подано 0 повідомлень. Жодне повідомлення 
                не було змінено';
        }
    } else {
        $response['status'] = 'failure';
        $response['message'] = 'Помилка на сервері - очікувано масив повідомлень';
    }

    echo json_encode($response);
} else {
    header("Location: /");
}

function markReadNotifications(int $userId, array $notifications): array
{
    $result = ['', []];

    $requestNotificationCount = count($notifications);
    $updatedNotifications = NotificationRepository::markReadNotifications($userId, $notifications);
    $updatedNotificationCount = count($updatedNotifications);

    $result['notifications'] = $updatedNotifications;
    if ($updatedNotificationCount <= 0) {
        $result['status'] = 'failure';
        $result['message'] = 'Жодне повідомлення не було змінено';
    } else if ($updatedNotificationCount < $requestNotificationCount) {
        $result['status'] = 'warning';
        $result['message'] = "Було помічено прочитанними $updatedNotificationCount з 
            $requestNotificationCount повідомлень";
    } else {
        $result['status'] = 'success';
        $result['message'] = 'Було помічено прочитанними усі запрошені повідомлення';
    }

    return $result;
}

function deleteNotifications(int $userId, array $notifications): array
{
    $result = ['', []];

    $requestNotificationCount = count($notifications);
    $deletedNotifications = NotificationRepository::deleteNotifications($userId, $notifications);
    $deletedNotificationCount = count($deletedNotifications);

    $result['notifications'] = $deletedNotifications;
    if ($deletedNotificationCount <= 0) {
        $result['message'] = 'Жодне повідомлення не було видалено';
    } else if ($deletedNotificationCount < $requestNotificationCount) {
        $result['message'] = "Було видалено $deletedNotificationCount з 
            $requestNotificationCount повідомлень";
    } else {
        $result['message'] = 'Було видалено усі запрошені повідомлення';
    }

    return $result;
}