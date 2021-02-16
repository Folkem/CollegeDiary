<?php

// todo: remake this page

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";

if (is_null($currentUser) ||
    $currentUser->getRole() !== UserRoles::ADMINISTRATOR &&
    $currentUser->getRole() !== UserRoles::DEPARTMENT_HEAD) {
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
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/util/normalize.css">
    <link rel="stylesheet" href="/styles/util/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/util/general.css">
    <link rel="stylesheet" href="/styles/control_panel/control_panel.css">
    <link rel="stylesheet" href="/styles/control_panel/schedules.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/control_panel/general/functions.js"></script>
    <script src="/scripts/control_panel/schedules/requests.js"></script>
    <script src="/scripts/control_panel/schedules/main.js"></script>
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