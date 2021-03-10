<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

if (isset($currentUser, $_POST['id-lesson']) && ($currentUser->getRole() === UserRoles::TEACHER)) {
    $idLesson = (int) $_POST['id-lesson'];
    
    $status = null;
    $logMessage = null;
    
    $lessonIsDeleted = LessonRepository::deleteLesson($idLesson);
    
    if ($lessonIsDeleted) {
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