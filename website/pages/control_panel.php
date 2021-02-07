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
            <div class="menu-buttons__item menu-buttons__item--active">
                Педагогічна нагрузка
            </div>
            <div class="menu-buttons__item">
                Вчителі через excel
            </div>
            <div class="menu-buttons__item">
                Студенти через excel
            </div>
            <div class="menu-buttons__item">
                Користувачі
            </div>
        </div>
        <div class="menu-content">
            <div class="menu-content__item work-distribution-block">
                <h2 class="content-item__header">Педагогічна нагрузка</h2>
                <hr>
                <div class="work-distribution__file">
                    <h3 class="content-item__subheader">Файл
                    </h3>
                    <div class="content-item__content">
                        <form class="form work-distribution-form" id="work-distribution-form"
                              onsubmit="return false;">
                            <div class="work-distribution-form__items">
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
                            </div>
                            <div class="work-distribution-form__response-log">
                                <div class="response-log__header">Лог</div>
                                <div class="response-log__content"
                                     id="work-distribution-file-form-log">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="work-distribution__table">

                </div>
            </div>
            <div class="menu-content__item teachers-block hidden">
                <h2 class="content-item__header">Додавання вчителів через excel
                </h2>
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
                                <div class="loader loader--hidden" id="teacher-loader"></div>
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
            <div class="menu-content__item students-block hidden">
                <h2 class="content-item__header">Додавання студентів через excel
                </h2>
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
                                <div class="loader loader--hidden" id="student-loader"></div>
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
                <div class="content-item__content">
                    <div class="users-form">
                        <div class="users-form__heading">
                            <div class="users-form__header-item">
                                Ім'я
                                <i class="fa fa-sort users-form__sort-button"
                                   data-role="first-name"></i>
                            </div>
                            <div class="users-form__header-item">
                                По батькові
                                <i class="fa fa-sort users-form__sort-button"
                                   data-role="middle-name"></i>
                            </div>
                            <div class="users-form__header-item">
                                Прізвище
                                <i class="fa fa-sort users-form__sort-button"
                                   data-role="last-name"></i>
                            </div>
                            <div class="users-form__header-item">
                                Пошта
                                <i class="fa fa-sort users-form__sort-button"
                                   data-role="email"></i>
                            </div>
                            <div class="users-form__header-item">
                                Ранг
                                <i class="fa fa-sort users-form__sort-button"
                                   data-role="role"></i>
                            </div>
                            <div class="users-form__header-item">
                                Група
                                <i class="fa fa-sort users-form__sort-button"
                                   data-role="group"></i>
                            </div>
                        </div>
                        <div class="users-form__user-list">
                            <?php
                            $availableGroups = GroupRepository::getGroupList();
                            $availableGroupsMap = array_combine(
                                array_map(
                                    fn($group) => $group->getId(),
                                    $availableGroups
                                ),
                                $availableGroups
                            );
                            ksort($availableGroupsMap);
                            $availableGroupsMap = array_map(
                                function ($group) {
                                    $shortName = $group->getSpeciality()->getShortName();
                                    $yearAndNumber = $group->getGroupYear() . $group->getGroupNumber();
                                    return "$shortName-$yearAndNumber";
                                },
                                $availableGroupsMap
                            );

                            $users = UserRepository::getUsers();
                            $studentsByGroups = GroupRepository::getStudentGroupDistribution();
                            foreach ($users as $user):
                                $userId = $user->getId();
                                $firstName = $user->getFirstName();
                                $middleName = $user->getMiddleName();
                                $lastName = $user->getLastName();
                                $roleId = $user->getRole();
                                $email = $user->getEmail();
                                $groupId = -1;
                                if (isset($studentsByGroups[$userId])) {
                                    $groupId = $studentsByGroups[$userId];
                                }
                                ?>
                                <div class="user-item">
                                    <div class="user-item__component user-item__id"
                                         contenteditable="true">
                                        <?= $userId ?>
                                    </div>
                                    <div class="user-item__component user-item__first-name"
                                         contenteditable="true">
                                        <?= $firstName ?>
                                    </div>
                                    <div class="user-item__component user-item__middle-name"
                                         contenteditable="true">
                                        <?= $middleName ?>
                                    </div>
                                    <div class="user-item__component user-item__last-name"
                                         contenteditable="true">
                                        <?= $lastName ?>
                                    </div>
                                    <div class="user-item__component user-item__email"
                                         contenteditable="true">
                                        <?= $email ?>
                                    </div>
                                    <div class="user-item__component user-item__role">
                                        <!--suppress HtmlFormInputWithoutLabel -->
                                        <select>
                                            <?php
                                            $availableUserRoles = UserRoles::getValues();
                                            foreach ($availableUserRoles as
                                                     $availableUserRoleId => $availableUserRole):
                                                ?>
                                                <option value="<?= $availableUserRoleId ?>"
                                                    <?= $roleId === $availableUserRoleId ? 'selected' : '' ?>>
                                                    <?= $availableUserRole ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="user-item__component user-item__group">
                                        <!--suppress HtmlFormInputWithoutLabel -->
                                        <select>
                                            <option selected></option>
                                            <?php

                                            foreach ($availableGroupsMap as
                                                     $availableGroupId => $availableGroupName):
                                                ?>
                                                <option value="<?= $availableGroupId ?>"
                                                    <?= (int)$groupId === (int)$availableGroupId ? 'selected' : '' ?>>
                                                    <?= $availableGroupName ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="user-item__component user-item__buttons">
                                        <div class="user-item__button user-item__button-update fa fa-user-edit fa-2x"
                                             title="Зберегти зміни"></div>
                                        <div class="user-item__button user-item__button-delete fa fa-trash fa-2x"
                                             title="Видалити запис"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="users-response-log">
                        <div class="response-log__header">Лог</div>
                        <div class="response-log__content"
                             id="users-form-log">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>