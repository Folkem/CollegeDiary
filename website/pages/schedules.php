<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";

if (is_null($currentUser)) {
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