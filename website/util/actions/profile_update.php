<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/logging.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";

if (isset($_POST['action']) && !is_null($currentUser)) {
    $action = $_POST['action'];
    $response = [];

    switch ($action) {
        case 'password':
            $result = changePassword($currentUser);
            $response['status'] = $result['status'];
            $response['message_places'] = $result['message_places'];
            $response['message'] = $result['message'];
            break;
        case 'avatar':
            $result = changeAvatar($currentUser);
            $response['status'] = $result['status'];
            $response['message'] = $result['message'];
            break;
        default:
            $response['status'] = 'failure';
            $response['message_places'] = ['general'];
            $response['message'] = getTechErrorMessage();
            break;
    }

    echo json_encode($response);
} else {
    header("Location: /pages/news.php");
}

function changePassword($currentUser): array
{
    global $logger;
    $result = [];

    if (isset($_POST['old-password'], $_POST['new-password'])) {
        try {
            $oldPassword = $_POST['old-password'];
            $oldHashedPassword = password_hash($oldPassword, PASSWORD_DEFAULT);
            $newRawPassword = $_POST['new-password'];
            $newPassword = password_hash($newRawPassword, PASSWORD_DEFAULT);

            if (password_verify($oldPassword, $currentUser->getPassword())) {
                $currentUser->setPassword($newPassword);
                $updateResult = StorageRepository::updateUser($currentUser);
                if ($updateResult === true) {
                    $result['status'] = 'success';
                    $result['message_places'] = ['general'];
                    $result['message'] = 'Пароль успішно змінено';
                } else {
                    $currentUser->setPassword($oldHashedPassword);
                    $result['status'] = 'failure';
                    $result['message_places'] = ['general'];
                    $result['message'] = 'Виникла помилка при зміні паролю';
                }
            } else {
                $result['status'] = 'failure';
                $result['message_places'] = ['old-password'];
                $result['message'] = 'Попередній пароль не вірний';
            }
        } catch (Exception $exception) {
            $result['status'] = 'failure';
            $result['message_places'] = ['general'];
            $result['message'] = getTechErrorMessage();
            $logger->error('Error while updating password: ' . $exception->getMessage());
        }
    } else {
        $result['status'] = 'failure';
        $result['message_places'] = ['general', 'old-password', 'new-password'];
        $result['message'] = 'Введіть паролі';
    }

    return $result;
}

function changeAvatar($currentUser): array
{
    global $logger;
    $allowedExtensions = ['jpg', 'png', 'jpeg', 'gif'];
    $allowedMaximumSize = 1048576;

    $result = [];

    if (isset($_FILES['avatar-file'])) {
        try {
            $avatarFile = $_FILES['avatar-file'];
            $fileLocation = $avatarFile['tmp_name'];

            $fileInfo = getimagesize($fileLocation);

            if ($fileInfo !== false) {
                $fileExtension = strtolower(pathinfo($avatarFile['name'], PATHINFO_EXTENSION));

                $fileHasAllowedExtension = (in_array($fileExtension, $allowedExtensions));
                $fileHasAllowedSize = ($avatarFile['size'] <= $allowedMaximumSize);

                if ($fileHasAllowedSize && $fileHasAllowedExtension) {
                    $newFileBaseName = time(); // unix timestamp
                    $newFileName = "$newFileBaseName.$fileExtension";

                    $oldAvatarPath = $currentUser->getAvatarPath();
                    $currentUser->setAvatarPath($newFileName);
                    $updateResult = StorageRepository::updateUser($currentUser);
                    if ($updateResult === true) {
                        $avatarsFolderPath = $_SERVER['DOCUMENT_ROOT'] . '/media/user_avatars/';
                        unlink($avatarsFolderPath . $oldAvatarPath);
                        move_uploaded_file($fileLocation, $avatarsFolderPath . $newFileName);
                        $result['status'] = 'success';
                        $result['message'] = 'Аватар успішно змінено.';
                    } else {
                        $currentUser->setAvatarPath($oldAvatarPath);
                        $result['status'] = 'failure';
                        $result['message'] = 'Виникла помилка при зміні аватару';
                    }
                } else {
                    $result['status'] = 'failure';
                    $result['message'] = '';
                    if (!$fileHasAllowedExtension) {
                        $result['message'] .= 'Файл може мати тільки наступні розширення: '
                            . implode(', ', $allowedExtensions) . '.';
                    }
                    if (!$fileHasAllowedExtension) {
                        $result['message'] .= ' Файл не може важити більше 1 мегабайту.';
                    }
                }
            } else {
                $result['status'] = 'failure';
                $result['message'] = 'Завантажений файл не є зображенням';
            }
        } catch (Exception $exception) {
            $result['status'] = 'failure';
            $result['message'] = getTechErrorMessage();
            $logger->error('Error while updating avatar: ' . $exception->getMessage());
        }
    } else {
        $result['status'] = 'failure';
        $result['message'] = 'Завантажте зображення.';
    }

    return $result;
}

function getTechErrorMessage(): string
{
    return 'Виникла технічна помилка. Зверніться до розробника, будь ласка 
                (может заменить "розробника" на кого-то другого?)';
}