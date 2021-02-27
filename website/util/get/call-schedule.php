<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

$schedule = CallScheduleRepository::getCallSchedule();
$schedule = array_combine(
    array_map(
        fn($scheduleItem) => $scheduleItem->getId(),
        $schedule
    ),
    array_map(
        function ($scheduleItem) {
            $number = $scheduleItem->getId();
            $timeStart = $scheduleItem->getTimeStart()->format('H:i:s');
            $timeEnd = $scheduleItem->getTimeEnd()->format('H:i:s');

            return [
                'number' => $number,
                'time-start' => $timeStart,
                'time-end' => $timeEnd
            ];
        },
        $schedule
    )
);

echo json_encode($schedule);