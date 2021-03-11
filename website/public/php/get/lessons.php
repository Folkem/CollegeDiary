<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

if (isset($_POST['id-discipline'])) {
    $idDiscipline = (int)$_POST['id-discipline'];
    
    $lessonsArray = LessonRepository::getLessonsByDiscipline($idDiscipline);
    
    $lessonsArray = array_reverse($lessonsArray);
    usort($lessonsArray, fn($one, $two)
        => $one->getDate()->getTimestamp() <=> $two->getDate()->getTimestamp());
    $lessonsArray = array_reverse($lessonsArray);
    
    $lessonTypes = LessonType::getValues();
    
    $result = array_map(
        function ($lesson) use ($lessonTypes) {
            return [
                'id' => $lesson->getId(),
                'comment' => $lesson->getComment(),
                'date' => $lesson->getDate()->format('d.m.Y'),
                'type' => $lessonTypes[$lesson->getType()]
            ];
        },
        $lessonsArray
    );
    
    echo json_encode($result);
} else {
    echo json_encode(['message' => 'id-discipline not specified']);
}