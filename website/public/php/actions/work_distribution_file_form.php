<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/../util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['action'],
        $_FILES['table-file'],
        $_POST['start-row'],
        $_POST['name-cell'],
        $_POST['subject-cell'],
        $_POST['group-cell']
    )
    &&
    (
        $currentUser->getRole() === UserRoles::ADMINISTRATOR
        ||
        $currentUser->getRole() === UserRoles::DEPARTMENT_HEAD
    )
) {
    $status = '';
    $message = '';
    $log_message = '';

    $startRowIndex = (int)$_POST['start-row'] - 1;
    $nameCellIndex = (int)$_POST['name-cell'] - 1;
    $subjectCellIndex = (int)$_POST['subject-cell'] - 1;
    $groupCellIndex = (int)$_POST['group-cell'] - 1;

    if ($parsedData = SimpleXLSX::parse($_FILES['table-file']['tmp_name'])) {
        $rows = $parsedData->rows();
        $rows = array_filter(
            $rows,
            fn($rowIndex) => $rowIndex >= $startRowIndex,
            ARRAY_FILTER_USE_KEY
        );
        $rows = array_map(
            function ($row) use ($groupCellIndex, $nameCellIndex, $subjectCellIndex) {
                $nameCell = $row[$nameCellIndex];
                $subjectCell = $row[$subjectCellIndex];
                $groupCell = $row[$groupCellIndex];
                return [$nameCell, $subjectCell, $groupCell];
            },
            $rows
        );
        $localTeacherNameCellIndex = 0;
        $localSubjectCellIndex = 1;
        $localGroupCellIndex = 2;

        $teachers = UserRepository::getUsersWithRole(UserRoles::TEACHER);
        $teachersAndNameMap = [];
        foreach ($teachers as $teacher) {
            $fullName = $teacher->getFullName();
            $teachersAndNameMap[$fullName] = $teacher;
        }
        $groups = GroupRepository::getGroups();
        $groupsMap = [];
        foreach ($groups as $group) {
            $groupFullName = mb_strtolower($group->getReadableName(false));
            $groupsMap[$groupFullName] = $group;
        }

        $recordsToAdd = [];

        excel_loop: foreach ($rows as $rowIndex => $row) {
            $record = new WorkDistributionRecord();

            $teacherNameAccepted = false;
            $groupNameAccepted = false;
            $errorMessage = '';

            foreach ($row as $cellIndex => $cellValue) {
                switch ($cellIndex) {
                    case $localTeacherNameCellIndex:
                        if (in_array($cellValue, array_keys($teachersAndNameMap), true)) {
                            $teacherNameAccepted = true;
                            $teacher = $teachersAndNameMap[$cellValue];
                            $record->setTeacher($teacher);
                        } else {
                            $errorMessage .= "<br>Користувача з іменем " .
                                "\"$cellValue\" не було знайдено";
                        }
                        break;
                    case $localSubjectCellIndex:
                        // no validation (yet)
                        $subject = str_replace(
                            ['_', '  '],
                            '',
                            $cellValue
                        );
                        $subject = trim($subject);
                        $record->setSubject($subject);
                        break;
                    case $localGroupCellIndex:
                        $loweredCellGroupName = mb_strtolower($cellValue);
                        if (groupIsPartTime($loweredCellGroupName)) {
                            continue 3; // excel_loop
                        } elseif (in_array($loweredCellGroupName, array_keys($groupsMap))) {
                            $groupNameAccepted = true;
                            $group = $groupsMap[$loweredCellGroupName];
                            $record->setGroup($group);
                        } else {
                            $errorMessage .= "<br>Групу з іменем " .
                                "\"$loweredCellGroupName\" не було знайдено";
                        }
                        break;
                }
            }
            if ($teacherNameAccepted && $groupNameAccepted) {
                $recordsToAdd[] = $record;
            } else {
                $rowNumber = $rowIndex + 1;
                $log_message .= "<br>- Запис $rowNumber не пройшов: $errorMessage";
            }
        }

        $addingResult = WorkDistributionRepository::addRecords($recordsToAdd);

        if ($addingResult['addedCount'] === count($rows)) {
            $status = 'success';
            $message = 'Додано';
        } elseif ($addingResult['addedCount'] === 0) {
            $status = 'failure';
            $message = 'Виникла помилка';
        } else {
            $status = 'warning';
            $message = 'Не всі записи були добавлені: ' . $addingResult['addedCount'] . '/' . count($rows);
        }
        $log_message .= '<br><hr>' . implode('<br>', $addingResult['error_messages']);
        $log_message = trim($log_message);
    } else {
        $status = 'failure';
        $message = 'Виникла помилка обробки файлу, продивіться лог';
        $log_message .= "<br><b>" . SimpleXLSX::parseError() . "</b>";
    }

    $result = [
        'status' => $status,
        'message' => $message,
        'log-message' => trim($log_message)
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}