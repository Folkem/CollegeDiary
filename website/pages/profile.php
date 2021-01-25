<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";

if (is_null($currentUser)) {
    header("Location: /");
}

?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Профіль користувача</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/normalize.css">
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/general.css">
    <link rel="stylesheet" href="/styles/profile.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/profile.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">
    <div class="menu">
        <div class="menu-buttons">
            <div class="menu-buttons__item menu-buttons__item--active">
                Налаштування
            </div>
            <div class="menu-buttons__item">
                Розклад дзвінків
            </div>
            <div class="menu-buttons__item">
                Розклад занять (WIP)
            </div>
        </div>
        <div class="menu-content">
            <div class="menu-content__item settings-block">
                <h2 class="menu-content__header">Налаштування</h2>
                <hr>
                <div class="menu-content__form">
                    <h3 class="form-header">Пароль</h3>
                    <form class="form" id="password-form" onsubmit="return false;">
                        <div class="form-item">
                            <label for="current-password"
                                   class="form__label">
                                Поточний пароль:
                            </label>
                            <input class="form__input" id="current-password"
                                   type="password" required>
                            <div class="form__error-text" id="current-password-error"></div>
                        </div>
                        <div class="form-item">
                            <label for="new-password"
                                   class="form__label">
                                Новий пароль:
                            </label>
                            <input class="form__input" id="new-password"
                                   type="password" required>
                            <div class="form__error-text" id="new-password-error"></div>
                        </div>
                        <div class="form-item">
                            <label for="repeated-new-password"
                                   class="form__label">
                                Повторіть новий пароль:
                            </label>
                            <input class="form__input" id="repeated-new-password"
                                   type="password" required>
                            <div class="form__error-text" id="repeated-new-password-error"></div>
                        </div>
                        <div class="form-item">
                            <button class="form__button" type="submit">Підтвердіть зміни</button>
                            <div id="password-result"></div>
                        </div>
                    </form>
                </div>
                <div class="menu-content__form">
                    <h3 class="form-header">Зображення профілю</h3>
                    <form class="form" id="avatar-form" onsubmit="return false;">
                        <div class="form-item">
                            <label class="form__label" for="new-avatar-file">
                                Нове зображення профілю:
                            </label>
                            <button id="new-avatar-file-button"
                                    class="form__input-button">
                                Завантажте файл зображення
                            </button>
                            <!--<input class="form__input" id="new-avatar-file"-->
                            <input id="new-avatar-file" class="hidden"
                                   type="file" required>
                        </div>
                        <div class="form-item">
                            <button class="form__button" type="submit">Підтвердіть зміни</button>
                            <div id="avatar-result"></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="menu-content__item call-schedule-block hidden">
                <h2 class="menu-content__header">Розклад дзвінків</h2>
                <hr>
                <div class="call-schedule">
                    <table class="call-schedule-table">
                        <tr>
                            <th class="call-schedule-table__item">№</th>
                            <th class="call-schedule-table__item">Початок</th>
                            <th class="call-schedule-table__item">Кінець</th>
                        </tr>
                        <?php
                            $callSchedule = StorageRepository::getCallSchedule();
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
            <div class="menu-content__item hidden">
                <h2 class="menu-content__header">Розклад занять</h2>
                <hr>
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>