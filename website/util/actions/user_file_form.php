<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/functions/general.php';

if (
    isset(
        $currentUser,
        $_POST['action'],
        $_POST['user-type'],
        $_FILES['table-file'],
        $_POST['start-row'],
        $_POST['name-cell'],
        $_POST['email-cell']
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

    // todo: exception handling

    $startRowIndex = (int)$_POST['start-row'] - 1;
    $nameCellIndex = (int)$_POST['name-cell'] - 1;
    $emailCellIndex = (int)$_POST['email-cell'] - 1;
    $userRole = null;
    switch ($_POST['user-type']) {
        case 'student':
            $userRole = UserRoles::STUDENT;
            break;
        case 'teacher':
            $userRole = UserRoles::TEACHER;
            break;
        default:
            header('Location: /');
    }

    if ($parsedData = SimpleXLSX::parse($_FILES['table-file']['tmp_name'])) {
        $rows = $parsedData->rows();
        $rows = array_filter(
            $rows,
            fn($rowIndex) => $rowIndex >= $startRowIndex,
            ARRAY_FILTER_USE_KEY
        );
        $rows = array_map(
            function ($row) use ($nameCellIndex, $emailCellIndex) {
                $nameCell = $row[$nameCellIndex];
                $emailCell = $row[$emailCellIndex];
                return [$nameCell, $emailCell];
            },
            $rows
        );
        $localNameCellIndex = 0;
        $localEmailCellIndex = 1;

        $usersToAdd = [];

        $validator = new EmailValidator();

        foreach ($rows as $rowIndex => $rowValue) {
            foreach ($rowValue as $cellIndex => $cellValue) {
                if ($cellIndex === $localEmailCellIndex) {
                    if ($validator->isValid($cellValue, new RFCValidation())) {
                        $fullName = $rowValue[$localNameCellIndex];
                        $fullName = str_replace(
                            ['.', 'C', 'c', '- ', ' -'],
                            [' ', 'С', 'с', '-', '-'],
                            $fullName
                        );

                        [$lastName, $firstName, $middleName] = decomposeUserFullName($fullName);
                        $email = $cellValue;
                        $randomPassword = getRandomString(16);

                        $newUser = (new User())
                            ->setFirstName($firstName)
                            ->setMiddleName($middleName)
                            ->setLastName($lastName)
                            ->setEmail($email)
                            ->setRole($userRole)
                            ->setPassword($randomPassword);

                        $usersToAdd[] = $newUser;
                    } else {
                        $rowNumber = $rowIndex + 1;
                        $log_message .= "Пошта на $rowNumber-му рядку не пройшла перевірку<br>";
                    }
                }
            }
        }

        $addingResult = UserRepository::addUsers($usersToAdd);

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
        $log_message .= '<br>' . implode('<br>', $addingResult['error_messages']);
    } else {
        $status = 'failure';
        $message = 'Виникла помилка обробки файлу, продивіться лог';
        $log_message .= "<br><b>" . SimpleXLSX::parseError() . "</b>";
    }

    $result = [
        'status' => $status,
        'message' => $message,
        'log-message' => $log_message
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}