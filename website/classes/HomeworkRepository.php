<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

class HomeworkRepository
{
    private static PDO $connection;
    
    private function __construct()
    {
    }
    
    public static function getHomeworkByDiscipline(int $idDiscipline): array
    {
        self::load();
        $result = [];
        
        $discipline = WorkDistributionRepository::getRecordById($idDiscipline);
        
        $statement = self::$connection->prepare('select * from homework
            where id_discipline = :id_discipline');
        
        if ($statement !== false && isset($discipline)) {
            if ($statement->execute(['id_discipline' => $idDiscipline])) {
                while (($statementArray = $statement->fetch()) !== false) {
                    $createdDate = DateTimeImmutable::createFromFormat('Y-m-d', $statementArray['created_at']);
                    $scheduledDate = DateTimeImmutable::createFromFormat('Y-m-d', $statementArray['scheduled_to']);
                    
                    $homework = (new Homework())
                        ->setId((int) $statementArray['id'])
                        ->setText($statementArray['text'])
                        ->setDiscipline($discipline)
                        ->setCreatedDate($createdDate)
                        ->setScheduledDate($scheduledDate);
                    
                    $result[] = $homework;
                }
            }
        }
        
        return $result;
    }
    
    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }
    
    public static function deleteHomework(int $idHomework): bool
    {
        self::load();
        $result = false;
        
        $statement = self::$connection->prepare('delete from homework where id = :id');
        
        if ($statement !== false) {
            if ($statement->execute(['id' => $idHomework])) {
                $result = $statement->rowCount() === 1;
            }
        }
        
        return $result;
    }
    
    public static function addHomework(Homework $homework)
    {
        self::load();
        $result = false;
        
        $statement = self::$connection->prepare('insert into homework
            (id_discipline, text, created_at, scheduled_to) values
            (:id_discipline, :text, :created_at, :scheduled_to)');
        
        if ($statement !== false) {
            if ($statement->execute([
                    ':id_discipline' => $homework->getDiscipline()->getId(),
                    ':text' => $homework->getText(),
                    ':created_at' => $homework->getCreatedDate()->format('Y-m-d'),
                    ':scheduled_to' => $homework->getScheduledDate()->format('Y-m-d'),
                ]) && $statement->rowCount() === 1) {
                $result = self::$connection->lastInsertId();
            }
        }
        
        return $result;
    }
    
    public static function getHomeworkById(int $idHomework): ?Homework
    {
        self::load();
        $result = null;
        
        $statement = self::$connection->prepare('select * from homework where id = :id');
        
        if ($statement !== false) {
            if ($statement->execute([':id' => $idHomework])) {
                if (($statementArray = $statement->fetch()) !== false) {
                    $discipline = WorkDistributionRepository::getRecordById($statementArray['id_discipline']);
                    $createdDate = DateTimeImmutable::createFromFormat('Y-m-d', $statementArray['created_at']);
                    $scheduledDate = DateTimeImmutable::createFromFormat('Y-m-d', $statementArray['scheduled_to']);
    
                    $homework = (new Homework())
                        ->setId((int) $statementArray['id'])
                        ->setText($statementArray['text'])
                        ->setDiscipline($discipline)
                        ->setCreatedDate($createdDate)
                        ->setScheduledDate($scheduledDate);
    
                    $result = $homework;
                }
            }
        }
        
        return $result;
    }
}