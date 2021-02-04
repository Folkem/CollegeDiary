<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['user-id']
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

    $userId = (int) $_POST['user-id'];

    $userWasDeleted = UserRepository::deleteUser($userId);

    if ($userWasDeleted) {
        $status = 'success';
    } else {
        $status = 'failure';
        $log_message = 'Користувача не вдалося видалити';
    }

    $result = [
        'status' => $status,
        'log-message' => $log_message
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}