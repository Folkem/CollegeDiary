<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['group-id'],
        $_POST['lesson-schedule']
    )
    &&
    (
        $currentUser->getRole() === UserRoles::ADMINISTRATOR
        ||
        $currentUser->getRole() === UserRoles::DEPARTMENT_HEAD
    )
) {
    $status = 'unknown';
    $logMessage = '';
    
    $groupId = intval($_POST['group-id']);
    $lessonScheduleArray = json_decode($_POST['lesson-schedule'], true);
    
    $disciplines = WorkDistributionRepository::getRecordsForGroup($groupId);
    $disciplines = array_combine(
        array_map(
            fn($discipline) => $discipline->getId(),
            $disciplines
        ),
        $disciplines
    );
    
    $lessonItemsArray = [];
    
    foreach ($lessonScheduleArray as $dayNumber => $daySchedule) {
        foreach ($daySchedule as $lessonNumber => $lessonDisciplinesArray) {
            foreach ($lessonDisciplinesArray as $variant => $lessonDisciplineId) {
                if (is_numeric($lessonDisciplineId)) {
                    $lessonItem = new LessonScheduleItem();
    
                    $lessonItem->setLessonNumber($lessonNumber)
                        ->setWeekDay($dayNumber)
                        ->setDiscipline($disciplines[$lessonDisciplineId])
                        ->setVariantNumber($variant);
    
                    $lessonItemsArray[] = $lessonItem;
                }
            }
        }
    }
    
    $itemsWereUpdated = LessonScheduleRepository::updateItems($groupId, $lessonItemsArray);
    
    if ($itemsWereUpdated) {
        $status = 'success';
    } else {
        $status = 'failure';
        $log_message = 'Розклад не вдалося оновити';
    }
    
    $result = [
        'status' => $status,
        'log-message' => $logMessage
    ];
    
    echo json_encode($result);
} else {
    header('Location: /');
}