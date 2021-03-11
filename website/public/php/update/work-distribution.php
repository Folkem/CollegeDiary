<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/../util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['id'],
        $_POST['subject'],
        $_POST['teacher'],
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
    $subject = $_POST['subject'];
    $teacherId = (int)$_POST['teacher'];
    $groupId = (int)$_POST['group'];

    $teacher = UserRepository::getUserById($teacherId);
    $group = GroupRepository::getGroupById($groupId);

    $thisRecord = WorkDistributionRepository::getRecordById($id);
    if ($thisRecord !== null) {
        $thisRecord->setSubject($subject);
        $thisRecord->setTeacher($teacher);
        $thisRecord->setGroup($group);

        $recordWasUpdated = WorkDistributionRepository::updateRecord($thisRecord);

        if ($recordWasUpdated) {
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
        'log_message' => $log_message
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}