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
    <title>Розроділ роботи — Панель управління — Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/util/normalize.css">
    <link rel="stylesheet" href="/styles/util/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/util/general.css">
    <link rel="stylesheet" href="/styles/control-panel/control_panel.css">
    <link rel="stylesheet" href="/styles/control-panel/work_distribution.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/control-panel/general/functions.js"></script>
    <script src="/scripts/control-panel/work-distribution/elements.js"></script>
    <script src="/scripts/control-panel/work-distribution/requests.js"></script>
    <script src="/scripts/control-panel/work-distribution/main.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">
    <div class="menu">
        <div class="menu-buttons">
            <div class="menu-buttons__item menu-buttons__item--active">
                Таблиця
            </div>
            <div class="menu-buttons__item">
                Завантаження через excel
            </div>
        </div>
        <div class="menu-content">
            <div class="menu-content__item">
                <h2 class="content-item__header">Таблиця</h2>
                <hr>
                <div class="work-distribution-table">
                    <div class="content-item__content">
                        <div class="work-distribution-container">
                            <div class="work-distribution-form">
                                <div class="work-distribution-form__header">
                                    <div class="work-distribution-form__add-button"
                                         id="work-distribution-form__add-button">
                                        <i class="fa fa-plus-circle work-distribution-form__add-icon"></i>
                                        Додати новий запис
                                    </div>
                                    <div class="work-distribution-form__headers-list">
                                        <div class="work-distribution-form__header-item">
                                            Предмет
                                            <i class="fa fa-sort work-distribution-form__sort-button"
                                               data-role="subject"></i>
                                        </div>
                                        <div class="work-distribution-form__header-item">
                                            Викладач
                                            <i class="fa fa-sort work-distribution-form__sort-button"
                                               data-role="teacher"></i>
                                        </div>
                                        <div class="work-distribution-form__header-item">
                                            Група
                                            <i class="fa fa-sort work-distribution-form__sort-button"
                                               data-role="group"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="work-distribution-form__records-list">
                                    <?php
                                    $groups = GroupRepository::getGroups();
                                    $groupsMap = array_combine(
                                        array_map(
                                            fn($group) => $group->getId(),
                                            $groups
                                        ),
                                        $groups
                                    );
                                    ksort($groupsMap);
                                    $groupsMap = array_map(
                                        fn($group) => $group->getReadableName(true),
                                        $groupsMap
                                    );
                                    $teachers = UserRepository::getUsersWithRole(UserRoles::TEACHER);
                                    $teachersMap = array_combine(
                                        array_map(
                                            fn($teacher) => $teacher->getId(),
                                            $teachers
                                        ),
                                        $teachers
                                    );

                                    $records = WorkDistributionRepository::getRecords();
                                    foreach ($records as $record): ?>
                                        <div class="work-distribution-item">
                                            <div class="work-distribution-item__component work-distribution-item__id">
                                                <?= $record->getId() ?>
                                            </div>
                                            <div class="work-distribution-item__component work-distribution-item__subject"
                                                 contenteditable="true">
                                                <?= $record->getSubject() ?>
                                            </div>
                                            <div class="work-distribution-item__component work-distribution-item__teacher">
                                                <!--suppress HtmlFormInputWithoutLabel -->
                                                <select>
                                                    <?php
                                                    foreach ($teachersMap
                                                             as $teacherId => $teacher):
                                                        ?>
                                                        <option value="<?= $teacherId ?>"
                                                            <?= $record->getTeacher()->getId() === $teacherId ? 'selected' : '' ?>>
                                                            <?= $teacher->getFullName() ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="work-distribution-item__component work-distribution-item__group">
                                                <!--suppress HtmlFormInputWithoutLabel -->
                                                <select>
                                                    <?php
                                                    foreach ($groupsMap as
                                                             $availableGroupId => $availableGroupName):
                                                        ?>
                                                        <option value="<?= $availableGroupId ?>"
                                                            <?= $record->getGroup()->getId() === (int)$availableGroupId ? 'selected' : '' ?>>
                                                            <?= $availableGroupName ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="work-distribution-item__component work-distribution-item__buttons">
                                                <div class="work-distribution-item__button work-distribution-item__button-update fa fa-edit fa-2x"
                                                     title="Зберегти зміни"></div>
                                                <div class="work-distribution-item__button work-distribution-item__button-delete fa fa-trash fa-2x"
                                                     title="Видалити запис"></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="work-distribution-response-log">
                                <div class="response-log__header">Лог</div>
                                <div class="response-log__content"
                                     id="work-distribution-table-form-log">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-content__item hidden">
                <h2 class="content-item__header">Завантаження через excel</h2>
                <hr>
                <div class="work-distribution-file">
                    <div class="content-item__content">
                        <form class="form work-distribution-form" id="work-distribution-form"
                              onsubmit="return false;">
                            <div class="form-item">
                                <label class="form__label" for="work-distribution-table-file">
                                    Файл з таблицею
                                </label>
                                <div id="work-distribution-table-file-button"
                                     class="form__input-button">
                                    Завантажте файл
                                </div>
                                <input id="work-distribution-table-file" name="work-distribution-file"
                                       class="hidden" type="file">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="work-distribution-table-start-row">
                                    Номер рядку, з якого починаються дані
                                </label>
                                <input id="work-distribution-table-start-row"
                                       class="form__input form__number-input"
                                       type="number" required value="7">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="work-distribution-table-subject-cell">
                                    Номер стовпця з предметом
                                </label>
                                <input id="work-distribution-table-subject-cell"
                                       class="form__input form__number-input"
                                       type="number" required value="1">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="work-distribution-table-name-cell">
                                    Номер стовпця з ПІБ
                                </label>
                                <input id="work-distribution-table-name-cell"
                                       class="form__input form__number-input"
                                       type="number" required value="2">
                            </div>
                            <div class="form-item">
                                <label class="form__label" for="work-distribution-table-group-cell">
                                    Номер стовпця з групою
                                </label>
                                <input id="work-distribution-table-group-cell"
                                       class="form__input form__number-input"
                                       type="number" required value="27">
                            </div>
                            <div class="form-item">
                                <button class="form__button" type="submit">Підтвердіть зміни</button>
                                <div class="loader loader--hidden" id="work-distribution-loader"></div>
                                <div class="form__response-text" id="work-distribution-table-result"></div>
                            </div>
                        </form>
                        <div class="work-distribution__response-log">
                            <div class="response-log__header">Лог</div>
                            <div class="response-log__content"
                                 id="work-distribution-file-form-log">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>