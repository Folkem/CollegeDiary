<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';

$result = [
    'status' => 'failure',
    'message' => '',
];

if (!isset($currentUser) || $currentUser->getRole() !== UserRoles::TEACHER) {
    $result['message'] = 'Змінювати оцінки може тільки викладач';
} elseif (!isset($_POST['id-grade'], $_POST['value'], $_POST['id-lesson'], $_POST['id-student'])) {
    $result['message'] = 'Не вказано деякі наступні поля: id-grade, value, id-lesson, id-student';
} else {
    $idGrade = intval($_POST['id-grade']);
    $value = mb_strtolower($_POST['value']);
    $idLesson = intval($_POST['id-lesson']);
    $idStudent = intval($_POST['id-student']);
    
    $grade = GradeRepository::getGradeByLessonAndStudentId($idLesson, $idStudent);
    
    $updated = false;
    
    if ($grade === null) {
        if ($value === 'null') {
            $result['status'] = 'success';
        } else {
            $lesson = LessonRepository::getLessonById($idLesson);
            $student = UserRepository::getUserById($idStudent);
            
            $grade = (new Grade())
                ->setLesson($lesson)
                ->setStudent($student);
            switch ($value) {
                case 'присутній':
                case 'відсутній':
                    $grade->setGradeValue(null);
                    $grade->setPresence($value === 'присутній');
                    break;
                default:
                    $numericValue = intval($value);
                    if ($numericValue >= 0 && $numericValue <= 100) {
                        $grade->setGradeValue($numericValue);
                        $grade->setPresence(true);
                    } else {
                        $result['message'] = 'Оцінка повинна бути числом від 0 до 100 включно';
                    }
                    break;
            }
            $updated = GradeRepository::addGrade($grade);
        }
    } else {
        switch ($value) {
            case 'null':
                $updated = GradeRepository::deleteGradeById($grade->getId());
                break;
            case 'присутній':
            case 'відсутній':
                $grade->setGradeValue(null);
                $grade->setPresence($value === 'присутній');
                $updated = GradeRepository::updateGrade($grade);
                break;
            default:
                $numericValue = intval($value);
                if ($numericValue >= 0 && $numericValue <= 100) {
                    $grade->setGradeValue($numericValue);
                    $grade->setPresence(true);
                    $updated = GradeRepository::updateGrade($grade);
                } else {
                    $result['message'] = 'Оцінка повинна бути числом від 0 до 100 включно';
                }
                break;
        }
    }
    
    if ($updated) {
        $result['status'] = 'success';
    }
}

echo json_encode($result);