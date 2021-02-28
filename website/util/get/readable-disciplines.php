<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

$groupedDisciplines = WorkDistributionRepository::getRecordsByGroups();
$groupedDisciplines = array_map(
    fn($disciplineArray) => array_map(
        fn($discipline) => [
            'id' => $discipline->getId(),
            'subject-teacher' =>
                $discipline->getSubject() . ' — ' . $discipline->getTeacher()->getFullName(),
        ],
        $disciplineArray
    ),
    $groupedDisciplines
);

echo json_encode($groupedDisciplines);