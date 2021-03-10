<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

if (is_null($currentUser)) {
    header("Location: /");
    return;
}

if ($currentUser->getRole() === UserRoles::PARENT) {
    $student = UserRepository::getStudentForParent($currentUser->getId());
    if (isset($student)) {
        $group = GroupRepository::getGroupForStudent($student->getId());
    }
} else {
    $group = GroupRepository::getGroupForStudent($currentUser->getId());
}

$weekDays = WeekDay::getValues();
if ($currentUser->getRole() === UserRoles::TEACHER) {
    $lessonSchedule = LessonScheduleRepository::getTeacherLessonSchedule($currentUser->getId());
} elseif ($currentUser->getRole() === UserRoles::PARENT) {
    $lessonSchedule = LessonScheduleRepository::getStudentLessonSchedule($student->getId());
} else {
    $lessonSchedule = LessonScheduleRepository::getStudentLessonSchedule($currentUser->getId());
}

$groupedLessonSchedule = array_map(
    function ($scheduleItem) use ($weekDays) {
        return [
            'discipline' => [
                'id' => $scheduleItem->getDiscipline()->getId(),
                'teacher' => $scheduleItem->getDiscipline()->getTeacher()->getFullName(),
                'subject' => $scheduleItem->getDiscipline()->getSubject(),
                'group' => [
                    'id' => $scheduleItem->getDiscipline()->getGroup()->getId(),
                    'name' => $scheduleItem->getDiscipline()->getGroup()->getReadableName(true)
                ]
            ],
            'week-day' => $scheduleItem->getWeekDay(),
            'lesson-number' => $scheduleItem->getLessonNumber(),
            'variant' => $scheduleItem->getVariantNumber()
        ];
    },
    $lessonSchedule
);
$groupedLessonSchedule = [
    $weekDays[1] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 1),
    $weekDays[2] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 2),
    $weekDays[3] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 3),
    $weekDays[4] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 4),
    $weekDays[5] => array_filter($groupedLessonSchedule, fn($scheduleItem) => $scheduleItem['week-day'] == 5)
];
$groupedLessonSchedule = array_map(
    function ($daySchedule) {
        $lessonSchedules = [];
        foreach ($daySchedule as $item) {
            $lessonNumber = $item['lesson-number'];
            if (array_key_exists($lessonNumber, $lessonSchedules)) {
                $lessonSchedules[$lessonNumber][$item['variant']] = $item;
            } else {
                $lessonSchedules[$lessonNumber] = [
                    $item['variant'] => $item
                ];
            }
        }

        return $lessonSchedules;
    },
    $groupedLessonSchedule
);

?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Розклади — Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/util/normalize.css">
    <link rel="stylesheet" href="/styles/util/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/util/general.css">
    <link rel="stylesheet" href="/styles/schedules.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/schedules.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">
    <div class="menu">
        <div class="menu-buttons">
            <div class="menu-buttons__item menu-buttons__item--active">
                Розклад занять
            </div>
            <div class="menu-buttons__item">
                Розклад дзвінків
            </div>
        </div>
        <div class="menu-content">
            <div class="menu-content__item">
                <h2 class="content-item__header">Розклад занять</h2>
                <hr>
                <div class="content-item__content">
                    <div class="lesson-schedule">

                        <?php if (($currentUser->getRole() === UserRoles::STUDENT
                            || $currentUser->getRole() === UserRoles::PARENT)): ?>

                            <?php if (isset($group)): ?>

                                <?php if ($currentUser->getRole() === UserRoles::PARENT): ?>
                            
                                    <h3 class="lesson-schedule-user">
                                        Студент
                                        <?= $student->getFullName() ?? 'Дітей в базі даних не знайдено' ?>
                                    </h3>

                                <?php endif; ?>
                            
                                <h3 class="lesson-schedule-group">Група <?= $group->getReadableName(true) ?></h3>

                            <?php endif; ?>

                            <table class="lesson-schedule-table">
                                <tr>
                                    <th class="lesson-schedule-table-header__item lesson-schedule-table__item">День</th>
                                    <th class="lesson-schedule-table-header__item lesson-schedule-table__item">Пара</th>
                                    <th class="lesson-schedule-table-header__item lesson-schedule-table__item">Предмет</th>
                                    <th class="lesson-schedule-table-header__item lesson-schedule-table__item">Викладач</th>
                                </tr>
                                
                                <?php foreach ($groupedLessonSchedule as $day => $daySchedule): ?>

                                    <?php foreach ($daySchedule as $lessonNumber => $lesson): ?>

                                        <?php
                                        $subjectText = '';
                                        $teacherText = '';
                                        if (array_key_first($lesson) === 3) {
                                            $subjectText = $lesson[3]['discipline']['subject'];
                                            $teacherText = $lesson[3]['discipline']['teacher'];
                                        } else {
                                            $subjectText = " / ";
                                            $teacherText = " / ";
                                            if (array_key_exists(1, $lesson)) {
                                                $subjectText = $lesson[1]['discipline']['subject'] . $subjectText;
                                                $teacherText = $lesson[1]['discipline']['teacher'] . $teacherText;
                                            } else {
                                                $subjectText = 'Вікно' . $subjectText;
                                                $teacherText = 'Вікно' . $teacherText;
                                            }
                                            if (array_key_exists(2, $lesson)) {
                                                $subjectText .= $lesson[2]['discipline']['subject'];
                                                $teacherText .= $lesson[2]['discipline']['teacher'];
                                            } else {
                                                $subjectText .= 'Вікно';
                                                $teacherText .= 'Вікно';
                                            }
                                        }
                                        ?>

                                        <tr class="lesson-schedule-table__row">

                                            <?php if ($lessonNumber === array_key_first($daySchedule)): ?>

                                                <td class="lesson-schedule-table__item lesson-schedule-item__table-day"
                                                    rowspan="<?= count($daySchedule) ?>">
                                                    <?= $day ?>
                                                </td>

                                            <?php endif; ?>

                                            <td class="lesson-schedule-table__item lesson-schedule-table__lesson-number">
                                                <?= $lessonNumber ?>
                                            </td>
                                            <td class="lesson-schedule-table__item lesson-schedule-table__subject">
                                                <?= $subjectText ?>
                                            </td>
                                            <td class="lesson-schedule-table__item lesson-schedule-table__teacher">
                                                <?= $teacherText ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php endforeach; ?>

                            </table>

                        <?php else: ?>

                            <table class="lesson-schedule-table">
                                <tr>
                                    <th class="lesson-schedule-table-header__item lesson-schedule-table__item">День</th>
                                    <th class="lesson-schedule-table-header__item lesson-schedule-table__item">Пара</th>
                                    <th class="lesson-schedule-table-header__item lesson-schedule-table__item">Предмет</th>
                                    <th class="lesson-schedule-table-header__item lesson-schedule-table__item">Викладач</th>
                                </tr>

                                <?php foreach ($groupedLessonSchedule as $day => $daySchedule): ?>

                                    <?php foreach ($daySchedule as $lessonNumber => $lesson): ?>

                                        <?php
                                        $subjectText = '';
                                        $groupText = '';
                                        if (array_key_first($lesson) === 3) {
                                            $subjectText = $lesson[3]['discipline']['subject'];
                                            $groupText = $lesson[3]['discipline']['group']['name'];
                                        } else {
                                            $subjectText = " / ";
                                            $groupText = " / ";
                                            if (array_key_exists(1, $lesson)) {
                                                $subjectText = $lesson[1]['discipline']['subject'] . $subjectText;
                                                $groupText = $lesson[1]['discipline']['group']['name'] . $groupText;
                                            } else {
                                                $subjectText = 'Вікно' . $subjectText;
                                            }
                                            if (array_key_exists(2, $lesson)) {
                                                $subjectText .= $lesson[2]['discipline']['subject'];
                                                $groupText .= $lesson[2]['discipline']['group']['name'];
                                            } else {
                                                $subjectText .= 'Вікно';
                                                $groupText .= 'Вікно';
                                            }
                                        }
                                        ?>

                                        <tr class="lesson-schedule-table__row">

                                            <?php if ($lessonNumber === array_key_first($daySchedule)): ?>

                                                <td class="lesson-schedule-table__item lesson-schedule-item__table-day"
                                                    rowspan="<?= count($daySchedule) ?>">
                                                    <?= $day ?>
                                                </td>

                                            <?php endif; ?>

                                            <td class="lesson-schedule-table__item lesson-schedule-table__lesson-number">
                                                <?= $lessonNumber ?>
                                            </td>
                                            <td class="lesson-schedule-table__item lesson-schedule-table__subject">
                                                <?= $subjectText ?>
                                            </td>
                                            <td class="lesson-schedule-table__item lesson-schedule-table__group">
                                                <?= $groupText ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php endforeach; ?>

                            </table>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="menu-content__item hidden">
                <h2 class="content-item__header">Розклад дзвінків</h2>
                <hr>
                <div class="content-item__content">
                    <div class="call-schedule">
                        <table class="call-schedule-table">
                            <tr>
                                <th class="call-schedule-table__item">№</th>
                                <th class="call-schedule-table__item">Початок</th>
                                <th class="call-schedule-table__item">Кінець</th>
                            </tr>
                            <?php $callSchedule = CallScheduleRepository::getCallSchedule();
                            foreach ($callSchedule as $callScheduleItem):?>
                                <tr class="call-schedule-table__row">
                                    <td class="call-schedule-table__item">
                                        <?= $callScheduleItem->getLessonNumber() ?>
                                    </td>
                                    <td class="call-schedule-table__item">
                                        <?= $callScheduleItem->getTimeStart()->format('H:i:s') ?>
                                    </td>
                                    <td class="call-schedule-table__item">
                                        <?= $callScheduleItem->getTimeEnd()->format('H:i:s') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>