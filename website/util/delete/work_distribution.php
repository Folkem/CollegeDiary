<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['id']
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

    $id = $_POST['id'];

    $recordWasDeleted = WorkDistributionRepository::deleteRecord($id);

    if ($recordWasDeleted) {
        $status = 'success';
    } else {
        $status = 'failure';
        $log_message = 'Запис не вдалося видалити';
    }

    $result = [
        'status' => $status,
        'log_message' => $log_message
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}