<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['id'],
        $_POST['time-start'],
        $_POST['time-end']
    )
    &&
    (
        $currentUser->getRole() === UserRoles::ADMINISTRATOR
        ||
        $currentUser->getRole() === UserRoles::DEPARTMENT_HEAD
    )
) {
    $status = '';
    $log_message = '';

    $id = (int)$_POST['id'];
    $timeStart = DateTimeImmutable::createFromFormat('H:i', $_POST['time-start']);
    $timeEnd = DateTimeImmutable::createFromFormat('H:i', $_POST['time-end']);

    $thisItem = CallScheduleRepository::getCallScheduleItemById($id);
    if ($thisItem !== null) {
        $thisItem->setTimeStart($timeStart);
        $thisItem->setTimeEnd($timeEnd);

        $itemWasUpdated = CallScheduleRepository::updateCallScheduleItem($thisItem);

        if ($itemWasUpdated) {
            $status = 'success';
        } else {
            $status = 'failure';
            $log_message = 'Запис не вдалося оновити';
        }
    } else {
        $status = 'failure';
        $log_message = 'Виникла необроблена помилка на сервері, зверніться до адміністратора';
    }

    $result = [
        'status' => $status,
        'log-message' => $log_message
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}