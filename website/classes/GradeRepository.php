<?php


class GradeRepository
{
    private static PDO $connection;
    
    private function __construct()
    {
    }
    
    public static function getGradesForStudentByDiscipline(int $idStudent, int $idDiscipline): array
    {
        self::load();
        $result = [];
        
        $student = UserRepository::getUserById($idStudent);
        $lessons = LessonRepository::getLessonsByDiscipline($idDiscipline);
        $lessons = array_combine(
            array_map(
                fn($lesson) => $lesson->getId(),
                $lessons
            ),
            $lessons
        );
        
        $statement = self::$connection->prepare('
            select g.* from grades g
            left join lessons l on l.id = g.id_lesson
            left join students s on g.id_student = s.id
            where s.id_student = :id_student and l.id_discipline = :id_discipline
        ');
        
        if ($statement !== false) {
            if ($statement->execute([
                ':id_student' => $idStudent,
                ':id_discipline' => $idDiscipline,
            ])) {
                while (($statementArray = $statement->fetch()) !== false) {
                    $lesson = $lessons[(int)$statementArray['id_lesson']];
                    
                    $grade = (new Grade())
                        ->setId((int)$statementArray['id'])
                        ->setLesson($lesson)
                        ->setStudent($student)
                        ->setGradeValue($statementArray['grade'])
                        ->setPresence((bool)$statementArray['presence']);
                    
                    $result[] = $grade;
                }
            }
        }
        
        return $result;
    }
    
    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }
    
    public static function getGradesForDiscipline(int $idDiscipline): array
    {
        self::load();
        $result = [];
        
        $discipline = WorkDistributionRepository::getRecordById($idDiscipline);
        $students = UserRepository::getStudentsByGroup($discipline->getGroup()->getId());
        $students = array_combine(
            array_map(
                fn($student) => $student->getId(),
                $students
            ),
            $students
        );
        $lessons = LessonRepository::getLessonsByDiscipline($idDiscipline);
        $lessons = array_combine(
            array_map(
                fn($lesson) => $lesson->getId(),
                $lessons
            ),
            $lessons
        );
        
        $statement = self::$connection->prepare('
            select g.id, id_lesson, s.id_student, grade, presence from grades g
            left join lessons l on l.id = g.id_lesson
            left join students s on g.id_student = s.id
            where l.id_discipline = :id_discipline
        ');
        
        if ($statement !== false) {
            if ($statement->execute([':id_discipline' => $idDiscipline])) {
                while (($statementArray = $statement->fetch()) !== false) {
                    $lesson = $lessons[$statementArray['id_lesson']];
                    $student = $students[$statementArray['id_student']];
                    
                    $grade = (new Grade())
                        ->setId((int)$statementArray['id'])
                        ->setLesson($lesson)
                        ->setStudent($student)
                        ->setGradeValue($statementArray['grade'])
                        ->setPresence((bool)$statementArray['presence']);;
                    
                    $result[] = $grade;
                }
            }
        }
        
        return $result;
    }
    
    public static function deleteGradeById(int $idGrade): bool
    {
        self::load();
        $result = false;
        
        $statement = self::$connection->prepare('delete from grades where id = :id');
        
        if ($statement !== false) {
            if ($statement->execute([':id' => $idGrade])) {
                $result = ($statement->rowCount() == 1);
            }
        }
        
        return $result;
    }
    
    public static function updateGrade(Grade $grade): bool
    {
        self::load();
        $result = false;
        
        $statement = self::$connection->prepare('
            update grades
                set grade = :grade, presence = :presence
                where id = :id
        ');
        
        if ($statement !== false) {
            if ($statement->execute([
                ':grade' => $grade->getGradeValue(),
                ':presence' => ($grade->isPresence() ? 1 : 0),
                ':id' => $grade->getId(),
            ])) {
                $result = ($statement->rowCount() === 1);
            }
        }
        
        return $result;
    }
    
    public static function addGrade(Grade $grade): bool
    {
        self::load();
        $result = false;
        
        $idStudent = UserRepository::getStudentIdByUserId($grade->getStudent()->getId());
        
        $statement = self::$connection->prepare('
            insert into grades (id_lesson, id_student, grade, presence)
            values (:id_lesson, :id_student, :grade, :presence)
        ');
        
        if ($statement !== false) {
            if ($statement->execute([
                ':id_lesson' => $grade->getLesson()->getId(),
                ':id_student' => $idStudent,
                ':grade' => $grade->getGradeValue(),
                ':presence' => ($grade->isPresence() ? 1 : 0),
            ])) {
                $result = ($statement->rowCount() === 1);
            }
        }
        
        return $result;
    }
    
    public static function getGradeByLessonAndStudentId(int $idLesson, int $idStudent): ?Grade
    {
        self::load();
        $result = null;
    
        $statement = self::$connection->prepare('
            select g.* from grades g
            left join students s on s.id = g.id_student
            where g.id_lesson = :id_lesson and s.id_student = :id_student
        ');
    
        if ($statement !== false) {
            if ($statement->execute([
                ':id_lesson' => $idLesson,
                ':id_student' => $idStudent,
            ])) {
                if (($statementArray = $statement->fetch()) !== false) {
                    $lesson = LessonRepository::getLessonById((int)$statementArray['id_lesson']);
                    $student = UserRepository::getStudentById($statementArray['id_student']);
                
                    $grade = (new Grade())
                        ->setId((int)$statementArray['id'])
                        ->setLesson($lesson)
                        ->setStudent($student)
                        ->setGradeValue($statementArray['grade'])
                        ->setPresence((bool)$statementArray['presence']);;
                
                    $result = $grade;
                }
            }
        }
    
        return $result;
    }
}