<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

// todo: remake get_lesson_schedules.php

$lessonScheduleVariants = LessonScheduleVariant::getValues();
$schedules = LessonScheduleRepository::getLessonSchedules();
$schedules = array_map(
    function ($schedule) use ($lessonScheduleVariants) {
        $group = $schedule['group'];
        $items = $schedule['items'];
        $groupPart = [
            'id' => $group->getId(),
            'name' => $group->getReadableName(true)
        ];
        $itemsPart = array_map(
            function ($scheduleItem) use ($lessonScheduleVariants) {
                $id = $scheduleItem->getId();
                $discipline = $scheduleItem->getDiscipline();
                $weekDay = $scheduleItem->getWeekDay();
                $lessonNumber = $scheduleItem->getLessonNumber();
                $variantNumber = $scheduleItem->getVariantNumber();

                return [
                    'id' => $id,
                    'discipline' => [
                        'id' => $discipline->getId(),
                        'teacher' => [
                            'id' => $discipline->getTeacher()->getId(),
                            'name' => $discipline->getTeacher()->getFullName()
                        ],
                        'subject' => $discipline->getSubject()
                    ],
                    'week-day' => $weekDay,
                    'lesson-number' => $lessonNumber->getLessonNumber(),
                    'variant' => [
                        'id' => $variantNumber,
                        'name' => $lessonScheduleVariants[$variantNumber]
                    ]
                ];
            },
            $items
        );

        return [
            'group' => $groupPart,
            'lessons' => $itemsPart
        ];
    },
    $schedules
);

echo json_encode($schedules);