<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

if (is_null($currentUser) ||
    (($currentUser->getRole() !== UserRoles::ADMINISTRATOR) &&
        ($currentUser->getRole() !== UserRoles::DEPARTMENT_HEAD))) {
    header("Location: /");
    return;
}

?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Розклади — Панель управління — Онлайн-щоденник</title>
    <link rel="stylesheet" href="../../css/font-awesome/all.min.css">
    <link rel="stylesheet" href="../../css/util/normalize.css">
    <link rel="stylesheet" href="../../css/util/reset.css">
    <link rel="stylesheet" href="../../css/sections.css">
    <link rel="stylesheet" href="../../css/control-panel/control_panel.css">
    <link rel="stylesheet" href="../../css/control-panel/schedules.css">
    <link rel="stylesheet" href="../../css/util/general.css">
    <script src="../../js/sections.js"></script>
    <script src="../../js/control-panel/general/functions.js"></script>
    <script src="../../js/control-panel/schedules/elements.js"></script>
    <script src="../../js/control-panel/schedules/requests.js"></script>
    <script src="../../js/control-panel/schedules/main.js"></script>
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
                    <div class="loader lesson-schedule__loader" id="lesson-schedule-loader"></div>
                    <div class="lesson-schedule hidden" id="lesson-schedule">
                        <div class="lesson-schedule__list">
                        </div>
                        <div class="lesson-schedule__response-log" id="lesson-schedule__response-log">
                            <div class="response-log__header">Лог</div>
                            <div class="response-log__content"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-content__item hidden">
                <h2 class="content-item__header">Розклад дзвінків</h2>
                <hr>
                <div class="content-item__content">
                    <div class="call-schedule">
                        <table class="call-schedule-table call-schedule-list">
                            <tr class="call-schedule-table__header">
                                <th class="call-schedule-table__item">№</th>
                                <th class="call-schedule-table__item">Початок</th>
                                <th class="call-schedule-table__item">Кінець</th>
                                <th class="call-schedule-table__item"></th>
                            </tr>
                            <?php
                            $callSchedule = CallScheduleRepository::getCallSchedule();
                            foreach ($callSchedule as $callScheduleItem):?>
                                <tr class="call-schedule-table__row call-schedule-item">
                                    <td class="call-schedule-table__item call-schedule-item__id hidden">
                                        <?= $callScheduleItem->getId() ?>
                                    </td>
                                    <td class="call-schedule-table__item call-schedule-item__number">
                                        <?= $callScheduleItem->getLessonNumber() ?>
                                    </td>
                                    <td class="call-schedule-table__item call-schedule-item__time-start">
                                        <label>
                                            <input type="time"
                                                   value="<?= $callScheduleItem->getTimeStart()->format('H:i') ?>">
                                        </label>
                                    </td>
                                    <td class="call-schedule-table__item call-schedule-item__time-end">
                                        <label>
                                            <input type="time"
                                                   value="<?= $callScheduleItem->getTimeEnd()->format('H:i') ?>">
                                        </label>
                                    </td>
                                    <td class="call-schedule-table__item call-schedule-item__save-button"
                                        title="Зберегти зміни">
                                        <i class="fa fa-check"></i>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <div class="call-schedule__log-message"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>