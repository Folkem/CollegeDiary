<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

if (isset($currentUser) && $currentUser->getRole() === UserRoles::TEACHER) {
    if (isset($_POST['id-discipline'], $_POST['created-date'], $_POST['scheduled-date'], $_POST['text'])) {
        $idDiscipline = (int) $_POST['id-discipline'];
        
        $discipline = WorkDistributionRepository::getRecordById($idDiscipline);
        $createdDate = DateTimeImmutable::createFromFormat('Y-m-d', $_POST['created-date']);
        $scheduledDate = DateTimeImmutable::createFromFormat('Y-m-d', $_POST['scheduled-date']);
        $text = htmlentities($_POST['text']);
        
        $newHomework = (new Homework())
            ->setCreatedDate($createdDate)
            ->setScheduledDate($scheduledDate)
            ->setText($text)
            ->setDiscipline($discipline);
        
        $newHomeworkId = HomeworkRepository::addHomework($newHomework);
        
        $status = '';
        $message = '';
        $id = null;
        
        if ($newHomeworkId !== false) {
            $status = 'success';
            $id = $newHomeworkId;
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
                'message' => 'Потрібно вказати id-discipline, created-date, scheduled-date, text'
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