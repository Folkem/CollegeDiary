<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

$result = [];

if (!isset($_POST['id-discipline'])) {
    $result['status'] = 'failure';
    $result['message'] = 'id-discipline не вказано';
} else {
    $idDiscipline = (int)$_POST['id-discipline'];
    
    $lessons = LessonRepository::getLessonsByDiscipline($idDiscipline);
    $lessonTypes = LessonType::getValues();
    
    if (isset($_POST['id-student']) && intval($_POST['id-student']) !== -1) {
        $idStudent = (int)$_POST['id-student'];
        
        $data = array_combine(
            array_map(
                fn($lesson) => $lesson->getId(),
                $lessons
            ),
            array_map(
                function ($lesson) use ($lessonTypes) {
                    return [
                        'lesson' => [
                            'id' => $lesson->getId(),
                            'type' => $lessonTypes[$lesson->getType()],
                        ],
                        'date' => $lesson->getDate()->format('Y-m-d'),
                        'grade' => null,
                    ];
                },
                $lessons
            )
        );
        
        $grades = GradeRepository::getGradesForStudentByDiscipline($idStudent, $idDiscipline);
        foreach ($grades as $grade) {
            $idLesson = $grade->getLesson()->getId();
            
            if ($grade->isPresence()) {
                if ($grade->getGradeValue() !== null) {
                    $data[$idLesson]['grade'] = $grade->getGradeValue();
                } else {
                    $data[$idLesson]['grade'] = 'Присутній';
                }
            } else {
                $data[$idLesson]['grade'] = 'Відсутній';
            }
        }
    } else {
        $students = UserRepository::getStudentsByDiscipline($idDiscipline);
        
        $gradesTemplateArray = array_fill_keys(
            array_map(
                fn($lesson) => $lesson->getId(),
                $lessons
            ),
            [
                'id' => null,
                'value' => null
            ]
        );
        
        $data = array_combine(
            array_map(
                fn($student) => $student->getId(),
                $students
            ),
            array_map(
                function ($student) use ($gradesTemplateArray) {
                    return [
                        'id' => $student->getId(),
                        'full-name' => $student->getFullName(),
                        'grades' => $gradesTemplateArray
                    ];
                },
                $students
            )
        );
        
        $grades = GradeRepository::getGradesForDiscipline($idDiscipline);
        foreach ($grades as $grade) {
            $idLesson = $grade->getLesson()->getId();
            $idStudent = $grade->getStudent()->getId();
    
            $data[$idStudent]['grades'][$idLesson]['id'] = $grade->getId();
            
            if ($grade->isPresence()) {
                if ($grade->getGradeValue() !== null) {
                    $data[$idStudent]['grades'][$idLesson]['value'] = $grade->getGradeValue();
                } else {
                    $data[$idStudent]['grades'][$idLesson]['value'] = 'Присутній';
                }
            } else {
                $data[$idStudent]['grades'][$idLesson]['value'] = 'Відсутній';
            }
        }
    }
    
    $result['status'] = 'success';
    $result['data'] = $data;
}

echo json_encode($result);