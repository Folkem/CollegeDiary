<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/../util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['user-field'],
        $_POST['user-value']
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

    $userField = $_POST['user-field'];
    $userValue = $_POST['user-value'];

    $userWasDeleted = UserRepository::deleteUser($userValue, $userField);

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