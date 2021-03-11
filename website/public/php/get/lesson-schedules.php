<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

// todo: remake lesson-schedules.php

$groups = GroupRepository::getGroups();
$groupIds = array_map(
    fn($group) => $group->getId(),
    $groups
);
$groupsMap = array_combine(
    $groupIds,
    $groups
);
$weekDaysArray = WeekDay::getValues();
$lessonVariants = LessonScheduleVariant::getValues();
$lessonNumbersArray = [1, 2, 3, 4, 5, 6, 7];

$lessonSchedulesArray = LessonScheduleRepository::getLessonSchedules();
$groupedLessonSchedulesArray = array_fill_keys(
    $groupIds,
    array_fill_keys(
        $weekDaysArray,
        array_fill_keys($lessonNumbersArray, [])
    )
);

foreach ($lessonSchedulesArray as $lessonSchedule) {
    $lessonScheduleGroupId = $lessonSchedule['group']->getId();
    $group = $groupsMap[$lessonScheduleGroupId];
    $groupedLessonSchedule = array_map(
        function ($scheduleItem) {
            return [
                'discipline' => [
                    'id' => $scheduleItem->getDiscipline()->getId(),
                    'teacher' => $scheduleItem->getDiscipline()->getTeacher()->getFullName(),
                    'subject' => $scheduleItem->getDiscipline()->getSubject()
                ],
                'week-day' => $scheduleItem->getWeekDay(),
                'lesson-number' => $scheduleItem->getLessonNumber(),
                'variant' => [
                    'id' => $scheduleItem->getVariantNumber(),
                    'name' => LessonScheduleVariant::getValues()[$scheduleItem->getVariantNumber()]
                ]
            ];
        },
        $lessonSchedule['lessons']
    );
    $groupedLessonSchedule = [
        $weekDaysArray[1] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 1),
        $weekDaysArray[2] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 2),
        $weekDaysArray[3] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 3),
        $weekDaysArray[4] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 4),
        $weekDaysArray[5] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 5)
    ];
    $groupedLessonSchedule = array_map(
        function ($dayLessonSchedule) use ($lessonNumbersArray) {
            $groupedDaySchedule = array_combine(
                $lessonNumbersArray,
                array_fill(0, 7, [])
            );
            
            foreach ($dayLessonSchedule as $lessonRecord) {
                $lessonNumber = $lessonRecord['lesson-number'];
                $lessonVariantId = $lessonRecord['variant']['id'];
                $groupedDaySchedule[$lessonNumber][$lessonVariantId] = $lessonRecord;
            }
            
            return $groupedDaySchedule;
        },
        $groupedLessonSchedule
    );
    
    $groupedLessonSchedulesArray[$lessonScheduleGroupId] = $groupedLessonSchedule;
}

echo json_encode($groupedLessonSchedulesArray);