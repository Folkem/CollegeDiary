<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['first-name'],
        $_POST['middle-name'],
        $_POST['last-name'],
        $_POST['email'],
        $_POST['role'],
        $_POST['group']
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

    $firstName = $_POST['first-name'];
    $middleName = $_POST['middle-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $role = (int)$_POST['role'];
    $groupId = (int)$_POST['group'];
    $randomPassword = getRandomString();

    $newUser = new User();
    $newUser->setFirstName($firstName);
    $newUser->setMiddleName($middleName);
    $newUser->setLastName($lastName);
    $newUser->setEmail($email);
    $newUser->setRole($role);
    $newUser->setPassword($randomPassword);

    $newUserId = UserRepository::addUser($newUser);
    if ($newUserId !== false) {
        if ($groupId != 0) {
            $userGroupWasUpdated = GroupRepository::updateStudentGroup($newUserId, $groupId);
        } else {
            $userGroupWasUpdated = true;
        }

        if ($userGroupWasUpdated) {
            $status = 'success';
        } else {
            $status = 'warning';
            $log_message = 'Користувач був добавлений, ' .
                'проте група не була оновлена';
        }
    } else {
        $status = 'failure';
        $log_message = 'Користувача не вдалося додати';
    }

    $result = [
        'status' => $status,
        'log-message' => $log_message,
        'user-id' => $newUserId
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}