<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";

if (is_null($currentUser)) {
    header('Location: /');
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
    <title>Дисципліни — Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/util/normalize.css">
    <link rel="stylesheet" href="/styles/util/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/util/general.css">
    <link rel="stylesheet" href="/styles/disciplines.css">
    <script src="/scripts/sections.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">
    <div class="content">
        <div class="disciplines">
            <div class="discipline-header">
                <div class="discipline-header__item">Предмет</div>
                <div class="discipline-header__item">
                    <?= $currentUser->getRole() === UserRoles::TEACHER ? 'Група' : 'Викладач' ?>
                </div>
                <div class="discipline-header__item">Посилання</div>
            </div>
            <div class="discipline-list">
                
                <?php
                
                if ($currentUser->getRole() === UserRoles::TEACHER) {
                    $role = 'teacher';
                    $disciplinesArray = WorkDistributionRepository::getRecordsForTeacher($currentUser->getId());
                } else {
                    $role = 'student';
                    if ($currentUser->getRole() === UserRoles::STUDENT) {
                        $group = GroupRepository::getGroupForStudent($currentUser->getId());
                    } else {
                        $student = UserRepository::getStudentForParent($currentUser->getId());
                        $group = GroupRepository::getGroupForStudent($student->getId());
                    }
                    
                    $disciplinesArray = WorkDistributionRepository::getRecordsForGroup($group->getId());
                }
                
                foreach ($disciplinesArray as $discipline): ?>

                    <div class="discipline">
                        <div class="discipline__item">
                            <?= $discipline->getSubject() ?>
                        </div>
                        <div class="discipline__item">
                            <?= $currentUser->getRole() === UserRoles::TEACHER ?
                                $discipline->getGroup()->getReadableName(true) :
                                $discipline->getTeacher()->getFullName() ?>
                        </div>
                        <div class="discipline__item">
                            <a href="/pages/discipline-item/<?= $role ?>.php?id=<?= $discipline->getId() ?>">Посилання</a>
                        </div>
                    </div>
                
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>