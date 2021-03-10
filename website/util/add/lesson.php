<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';

if (isset($currentUser) && $currentUser->getRole() === UserRoles::TEACHER) {
    if (isset($_POST['id-discipline'], $_POST['date'], $_POST['type'], $_POST['comment'])) {
        $idDiscipline = (int) $_POST['id-discipline'];
        
        $discipline = WorkDistributionRepository::getRecordById($idDiscipline);
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $_POST['date']);
        $type = $_POST['type'];
        $comment = htmlentities($_POST['comment']);
        
        $newLesson = (new Lesson())
            ->setDate($date)
            ->setType($type)
            ->setComment($comment)
            ->setDiscipline($discipline);
        
        $newLessonId = LessonRepository::addLesson($newLesson);
        
        $status = '';
        $message = '';
        $id = null;
        
        if ($newLessonId !== false) {
            $status = 'success';
            $id = $newLessonId;
        } else {
            $status = 'failure';
            $message = 'Необроблена ще помилка';
        }
        
        $result = [
            'status' => $status,
            'message' => $message
        ];
        
        if (isset($id)) {
            $result['id'] = $id;
        }
        
        echo json_encode($result);
    } else {
        echo json_encode(
            [
                'status' => 'failure',
                'message' => 'Потрібно вказати id-discipline, date, type, comment'
            ]
        );
    }
} else {
    echo json_encode(
        [
            'status' => 'failure',
            'message' => 'Додавання записів дозволено лишь вчителям'
        ]
    );
}