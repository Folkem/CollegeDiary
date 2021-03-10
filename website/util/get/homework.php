<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

if (isset($_POST['id-discipline'])) {
    $idDiscipline = (int)$_POST['id-discipline'];
    
    $homeworkArray = HomeworkRepository::getHomeworkByDiscipline($idDiscipline);
    
    $homeworkArray = array_reverse($homeworkArray);
    usort($homeworkArray, fn($one, $two)
        => $one->getScheduledDate()->getTimestamp() <=> $two->getScheduledDate()->getTimestamp());
    
    $result = array_map(
        function ($homeworkItem) {
            return [
                'id' => $homeworkItem->getId(),
                'text' => $homeworkItem->getText(),
                'created-date' => $homeworkItem->getCreatedDate()->format('d.m.Y'),
                'scheduled-date' => $homeworkItem->getScheduledDate()->format('d.m.Y'),
            ];
        },
        $homeworkArray
    );
    
    echo json_encode($result);
} else {
    echo json_encode(['message' => 'id-discipline не вказано']);
}