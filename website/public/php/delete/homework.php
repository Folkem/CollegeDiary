<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

if (isset($currentUser, $_POST['id-homework']) && ($currentUser->getRole() === UserRoles::TEACHER)) {
    $idHomework = (int) $_POST['id-homework'];
    
    $status = null;
    $logMessage = null;
    
    $homeworkIsDeleted = HomeworkRepository::deleteHomework($idHomework);
    
    if ($homeworkIsDeleted) {
        $status = 'success';
    } else {
        $status = 'failure';
        $logMessage = 'Невідома помилка при видаленні';
    }
    
    $result = [
        'status' => $status,
        'log-message' => $logMessage
    ];
    
    echo json_encode($result);
} else {
    header('Location: /');
}