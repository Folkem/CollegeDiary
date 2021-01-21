<?php

require_once "../loader.php";
require_once "../auth_check.php";

if (isset($_POST['action']) && !is_null($currentUser)) {
    $action = $_POST['action'];
    $response = [];

    switch ($action) {
        case 'change password':
            changePassword($response['message'], $currentUser);
            break;
        case 'change avatar':
            changeAvatar($response['message'], $currentUser);
            break;
        default:
            $response['message'] = getDefaultErrorMessage();
            break;
    }

    echo json_encode($response);
} else {
    header("Location: /pages/news.php");
}

function changePassword(&$responseMessage, $currentUser) : void {
    try {
        $oldPassword = $_POST['oldPassword'];
        $oldHashedPassword = password_hash($oldPassword, PASSWORD_DEFAULT);
        $newRawPassword = $_POST['newPassword'];
        $newPassword = password_hash($newRawPassword, PASSWORD_DEFAULT);

        if (password_verify($oldPassword, $currentUser->getPassword())) {
            StorageRepository::load();
            $currentUser->setPassword($newPassword);
            $result = StorageRepository::updateUser($currentUser);
            if ($result === true) {
                $responseMessage = 'Пароль успішно змінено';
            } else {
                $currentUser->setPassword($oldHashedPassword);
                $responseMessage = 'Виникла помилка при зміні паролю';
            }
        } else {
            $responseMessage = 'Попередній пароль не вірний';
        }
    } catch (Exception $exception) {
        $responseMessage = getDefaultErrorMessage();
    }
}

function changeAvatar(&$responseMessage, $currentUser) : void {
    try {
        echo 'uh';
    } catch (Exception $exception) {
        $responseMessage = getDefaultErrorMessage();
    }
}

function getDefaultErrorMessage() : string {
    return 'Виникла технічна помилка. Зверніться до розробника, будь ласка 
                (может заменить "розробника" на кого-то другого?)';
}