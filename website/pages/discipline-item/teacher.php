<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

$discipline = null;
$redirect = true;

if (is_null($currentUser) || $currentUser->getRole() !== UserRoles::TEACHER) {
    header('Location: /');
    return;
}

if (isset($_GET['id'])) {
    try {
        $idDiscipline = intval($_GET['id']);
        
        $discipline = WorkDistributionRepository::getRecordById($idDiscipline);
        
        $redirect = $discipline->getTeacher()->getId() !== $currentUser->getId();
    } catch (Exception $exception) {
        $redirect = true;
    }
}

if ($redirect == true) {
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
    <title><?= $discipline->getSubject() . ' — ' . $discipline->getGroup()->getReadableName(true) ?> —
        Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/util/normalize.css">
    <link rel="stylesheet" href="/styles/util/reset.css">
    <link rel="stylesheet" href="/styles/discipline-item/main.css">
    <link rel="stylesheet" href="/styles/discipline-item/teacher.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/util/general.css">
    <script>
        const ID_DISCIPLINE = <?= $idDiscipline ?>;
    </script>
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/discipline-item/functions.js"></script>
    <script src="/scripts/discipline-item/teacher/elements.js"></script>
    <script src="/scripts/discipline-item/teacher/request.js"></script>
    <script src="/scripts/discipline-item/teacher/main.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">
    <div class="wrapper-content">
        <div class="menu-button-list">
            <div class="menu-button menu-button--selected" data-block="lessons-block">Заняття</div>
            <div class="menu-button" data-block="grades-block">Оцінки</div>
            <div class="menu-button" data-block="homework-block">Домашнє завдання</div>
        </div>
        <div class="menu-content-list">
            <div class="menu-content-block lessons-block" id="lessons-block">

                <div class="lessons-form-block">
                    <h3 class="lessons-form-block__header">Форма редагування</h3>
                    <div class="lessons-form-block__form lessons-form">
                        <input class="lessons-form__date" type="date" id="lessons-form-date"
                            value="<?= date('Y-m-d') ?>">
                        <select class="lessons-form__type" id="lessons-form-type"></select>
                        <textarea class="lessons-form__comment" rows="10" id="lessons-form-comment"></textarea>
                        <button class="lessons-form__button" id="lessons-form-button"
                                type="submit">Зберегти зміни</button>
                    </div>
                </div>

                <div class="lessons-list-block">
                    <div class="lessons-list-block__header">
                        <div class="lessons-list__header-element">Опис</div>
                        <div class="lessons-list__header-element">Тип заняття</div>
                        <div class="lessons-list__header-element">Дата</div>
                    </div>
                    <div class="lessons-list-block__list" id="lessons-list">

                    </div>
                </div>

            </div>
            <div class="menu-content-block hidden" id="grades-block">
                <div class="grades">
                
                </div>
            </div>
            <div class="menu-content-block homework-block hidden" id="homework-block">

                <div class="homework-form-block">
                    <h3 class="homework-form-block__header">Форма редагування</h3>
                    <div class="homework-form-block__form homework-form">
                        <input class="homework-form__date" type="date" id="homework-form-create-date"
                               value="<?= date('Y-m-d') ?>">
                        <input class="homework-form__date" type="date" id="homework-form-schedule-date"
                               value="<?= date('Y-m-d',
                                   mktime(0, 0, 0, date('m'),
                                       date('d') + 7, date('Y'))) ?>">
                        <textarea class="homework-form__comment" rows="10" id="homework-form-text"></textarea>
                        <button class="homework-form__button" id="homework-form-button"
                                type="submit">Зберегти зміни</button>
                    </div>
                </div>

                <div class="homework-list-block">
                    <div class="homework-list-block__header">
                        <div class="homework-list__header-element">Завдання</div>
                        <div class="homework-list__header-element">Дата публікації</div>
                        <div class="homework-list__header-element">Дата здачі</div>
                    </div>
                    <div class="homework-list-block__list" id="homework-list">

                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>