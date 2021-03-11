<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";


class LessonRepository
{
    private static PDO $connection;
    
    private function __construct()
    {
    }
    
    public static function getLessonsByDiscipline(int $idDiscipline): array
    {
        self::load();
        $result = [];
        
        $discipline = WorkDistributionRepository::getRecordById($idDiscipline);
        
        $statement = self::$connection->prepare('select * from lessons
            where id_discipline = :id_discipline');
        
        if ($statement !== false && isset($discipline)) {
            if ($statement->execute(['id_discipline' => $idDiscipline])) {
                while (($statementArray = $statement->fetch()) !== false) {
                    $date = DateTimeImmutable::createFromFormat('Y-m-d', $statementArray['date']);
                    
                    $lesson = (new Lesson())
                        ->setId((int)$statementArray['id'])
                        ->setComment($statementArray['comment'])
                        ->setDiscipline($discipline)
                        ->setDate($date)
                        ->setType($statementArray['id_lesson_type']);
                    
                    $result[] = $lesson;
                }
            }
        }
        
        return $result;
    }
    
    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }
    
    public static function deleteLesson(int $idLesson): bool
    {
        self::load();
        $result = false;
        
        $statement = self::$connection->prepare('delete from lessons where id = :id');
        
        if ($statement !== false) {
            if ($statement->execute(['id' => $idLesson])) {
                $result = $statement->rowCount() === 1;
            }
        }
        
        return $result;
    }
    
    public static function addLesson(Lesson $lesson)
    {
        self::load();
        $result = false;
        
        $statement = self::$connection->prepare('insert into lessons
            (id_discipline, comment, date, id_lesson_type) values
            (:id_discipline, :comment, :date, :id_lesson_type)');
        
        if ($statement !== false) {
            if ($statement->execute([
                    ':id_discipline' => $lesson->getDiscipline()->getId(),
                    ':comment' => $lesson->getComment(),
                    ':date' => $lesson->getDate()->format('Y-m-d'),
                    ':id_lesson_type' => $lesson->getType()
                ]) && $statement->rowCount() === 1) {
                $result = self::$connection->lastInsertId();
            }
        }
        
        return $result;
    }
    
    public static function getLessonById(int $idLesson): ?Lesson
    {
        self::load();
        $result = null;
        
        $statement = self::$connection->prepare('select * from lessons where id = :id');
        
        if ($statement !== false) {
            if ($statement->execute([':id' => $idLesson])) {
                if (($statementArray = $statement->fetch()) !== false) {
                    $date = DateTimeImmutable::createFromFormat('Y-m-d', $statementArray['date']);
                    $discipline = WorkDistributionRepository::getRecordById($statementArray['id_discipline']);
                    
                    $lesson = (new Lesson())
                        ->setId((int)$statementArray['id'])
                        ->setComment($statementArray['comment'])
                        ->setDiscipline($discipline)
                        ->setDate($date)
                        ->setType($statementArray['id_lesson_type']);
    
                    $result = $lesson;
                }
            }
        }
        
        return $result;
    }
}