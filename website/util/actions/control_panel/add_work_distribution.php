<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/functions/general.php';

if (
    isset(
        $currentUser,
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

    $subject = $_POST['subject'];
    $teacherId = (int)$_POST['teacher'];
    $groupId = (int)$_POST['group'];

    $teacher = UserRepository::getUserById($teacherId);
    $group = GroupRepository::getGroupById($groupId);

    $newRecord = new WorkDistributionRecord();
    $newRecord->setSubject($subject);
    $newRecord->setTeacher($teacher);
    $newRecord->setGroup($group);

    $newRecordId = WorkDistributionRepository::addRecord($newRecord);
    if ($newRecordId !== false) {
        $status = 'success';
    } else {
        $status = 'failure';
        $log_message = 'Користувача не вдалося додати';
    }

    $result = [
        'status' => $status,
        'log_message' => $log_message,
        'record-id' => $newRecordId
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}