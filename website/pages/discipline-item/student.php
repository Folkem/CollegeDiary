<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/logging.php";

$discipline = null;
$redirect = true;

if (is_null($currentUser) ||
    ($currentUser->getRole() !== UserRoles::STUDENT &&
        $currentUser->getRole() !== UserRoles::PARENT)) {
    header('Location: /');
    return;
}

if (isset($_GET['id'])) {
    try {
        $idDiscipline = intval($_GET['id']);
        
        $discipline = WorkDistributionRepository::getRecordById($idDiscipline);
        
        $student = $currentUser;
        if ($currentUser->getRole() === UserRoles::PARENT) {
            $student = UserRepository::getStudentForParent($currentUser->getId());
        }
        
        try {
            $group = GroupRepository::getGroupForStudent($student->getId());
            
            $redirect = $discipline->getGroup()->getId() !== $group->getId();
        } catch (Throwable $exception) {
            $redirect = true;
        }
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
    <title><?= $discipline->getSubjectTeacher() ?> — Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/util/normalize.css">
    <link rel="stylesheet" href="/styles/util/reset.css">
    <link rel="stylesheet" href="/styles/discipline-item/main.css">
    <link rel="stylesheet" href="/styles/discipline-item/student.css">
    <link rel="stylesheet" href="/styles/util/general.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <script>
        const ID_DISCIPLINE = <?= $idDiscipline ?>;
        const ID_STUDENT = <?= $student->getId() ?>;
    </script>
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/discipline-item/functions.js"></script>
    <script src="/scripts/discipline-item/student/elements.js"></script>
    <script src="/scripts/discipline-item/student/main.js"></script>
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
            <div class="menu-content-block" id="lessons-block">
                <div class="lessons-list-block">
                    <div class="lessons-list-block__header">
                        <div class="lessons-list__header-element">Опис</div>
                        <div class="lessons-list__header-element">Тип заняття</div>
                        <div class="lessons-list__header-element">Дата</div>
                    </div>
                    <div class="lessons-list" id="lessons-list">

                    </div>
                </div>
            </div>
            <div class="menu-content-block hidden" id="grades-block">
                <div class="grades-list-block">
                    <div class="grades-list-block__header">
                        <div class="grades-list__header-element">Оцінка</div>
                        <div class="grades-list__header-element">Тип заняття</div>
                        <div class="grades-list__header-element">Дата</div>
                    </div>
                    <div class="grades-list" id="grades-list">

                    </div>
                </div>
            </div>
            <div class="menu-content-block hidden" id="homework-block">
                Homework
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>