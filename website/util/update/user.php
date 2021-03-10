<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['id'],
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

    $id = (int)$_POST['id'];
    $firstName = $_POST['first-name'];
    $middleName = $_POST['middle-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $role = (int)$_POST['role'];
    $groupId = (int)$_POST['group'];

    $thisUser = UserRepository::getUserById($id);
    if ($thisUser !== null) {
        $thisUser->setFirstName($firstName);
        $thisUser->setMiddleName($middleName);
        $thisUser->setLastName($lastName);
        $thisUser->setEmail($email);
        $thisUser->setRole($role);

        $userWasUpdated = UserRepository::updateUser($thisUser);
        if ($groupId != 0) {
            if ($groupId === -1) {
                $userGroupWasUpdated = GroupRepository::removeStudentGroup($id);
            } else {
                $userGroupWasUpdated = GroupRepository::updateStudentGroup($id, $groupId);
            }
        } else {
            $userGroupWasUpdated = true;
        }

        if ($userWasUpdated && $userGroupWasUpdated) {
            $status = 'success';
        } else if ($userWasUpdated || $userGroupWasUpdated) {
            $status = 'warning';
            if ($userWasUpdated) {
                $log_message = 'Дані користувача були оновлені, 
                    проте група не була оновлена';
            } else {
                $log_message = 'Група користувача була оновлена, 
                    проте сам користувач не був оновлений';
            }
        } else {
            $status = 'failure';
            $log_message = 'Користувача не вдалося оновити';
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