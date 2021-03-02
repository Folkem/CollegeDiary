<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

$groupedDisciplines = WorkDistributionRepository::getRecordsByGroups();
$groupedDisciplines = array_map(
    fn($disciplineArray) => array_map(
        fn($discipline) => [
            'id' => $discipline->getId(),
            'subject-teacher' =>
                $discipline->getSubjectTeacher(),
        ],
        $disciplineArray
    ),
    $groupedDisciplines
);

echo json_encode($groupedDisciplines);