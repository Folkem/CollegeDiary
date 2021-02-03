<?php

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
    <title>Панель управління — Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/normalize.css">
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/general.css">
    <link rel="stylesheet" href="/styles/control_panel.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/control_panel.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">
    <div class="menu">
        <div class="menu-buttons">
            <div class="menu-buttons__item">
                Педагогічна нагрузка
            </div>
            <div class="menu-buttons__item">
                Вчителі через excel
            </div>
            <div class="menu-buttons__item menu-buttons__item--active">
                Студенти через excel
            </div>
            <div class="menu-buttons__item">
                Користувачі
            </div>
        </div>
        <div class="menu-content">
            <div class="menu-content__item work-distribution-block hidden">
                <h2 class="content-item__header">Педагогічна нагрузка</h2>
                <hr>
                <div class="">

                </div>
                <div class="">

                </div>
            </div>
            <div class="menu-content__item teachers-block hidden">
                <h2 class="content-item__header">Додавання вчителів через excel</h2>
                <hr>
                <div class="content-item__content">
                    <form class="form teacher-form" id="teacher-form" onsubmit="return false;">
                        <div class="teacher-form__items">
                            <div class="form-item">
                                <label class="form__label" for="teacher-table-file">
                                    Файл з таблицею
                                </label>
                                <div id="teacher-table-file-button"
                                     class="form__input-button">
                                    Завантажте файл
                                </div>
                                <input id="teacher-table-file" name="file"
                                       class="hidden"
                                       type="file" required>
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="teacher-table-start-row">
                                    Номер рядку, з якого починаються дані студентів
                                </label>
                                <input id="teacher-table-start-row" name="start-row"
                                       class="form__input form__number-input"
                                       type="number" required value="4">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="teacher-table-name-cell">
                                    Номер стовпця з ПІБ
                                </label>
                                <input id="teacher-table-name-cell" name="name-cell"
                                       class="form__input form__number-input"
                                       type="number" required value="2">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="teacher-table-email-cell">
                                    Номер стовпця з корпоративною поштою
                                </label>
                                <input id="teacher-table-email-cell" name="email-cell"
                                       class="form__input form__number-input"
                                       type="number" required value="6">
                            </div>
                            <div class="form-item">
                                <button class="form__button" type="submit">Підтвердіть зміни</button>
                                <div class="form__response-text" id="teacher-table-result"></div>
                            </div>
                        </div>
                        <div class="teacher-form__response-log">
                            <div class="response-log__header">Лог</div>
                            <div class="response-log__content"
                                 id="teacher-form-log">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="menu-content__item students-block">
                <h2 class="content-item__header">Додавання студентів через excel</h2>
                <hr>
                <div class="content-item__content">
                    <form class="form student-form" id="student-form" onsubmit="return false;">
                        <div class="student-form__items">
                            <div class="form-item">
                                <label class="form__label" for="student-table-file">
                                    Файл з таблицею
                                </label>
                                <div id="student-table-file-button"
                                     class="form__input-button">
                                    Завантажте файл
                                </div>
                                <input id="student-table-file" name="student-file"
                                       class="hidden" type="file">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="student-table-start-row">
                                    Номер рядку, з якого починаються дані студентів
                                </label>
                                <input id="student-table-start-row" name="student-start-row"
                                       class="form__input form__number-input"
                                       type="number" required value="4">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="student-table-name-cell">
                                    Номер стовпця з ПІБ
                                </label>
                                <input id="student-table-name-cell" name="student-name-cell"
                                       class="form__input form__number-input"
                                       type="number" required value="2">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="student-table-email-cell">
                                    Номер стовпця з корпоративною поштою
                                </label>
                                <input id="student-table-email-cell" name="student-email-cell"
                                       class="form__input form__number-input"
                                       type="number" required value="7">
                            </div>
                            <div class="form-item">
                                <button class="form__button" type="submit">Підтвердіть зміни</button>
                                <div class="form__response-text" id="student-table-result"></div>
                            </div>
                        </div>
                        <div class="student-form__response-log">
                            <div class="response-log__header">Лог</div>
                            <div class="response-log__content"
                                 id="student-form-log">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="menu-content__item users-block hidden">
                <h2 class="content-item__header">Користувачі</h2>
                <hr>
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>