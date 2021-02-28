<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class LessonScheduleRepository
{
    private static PDO $connection;
    
    private function __construct()
    {
    }
    
    public static function getLessonSchedules(): array
    {
        self::load();
        $result = [];
        
        $disciplines = WorkDistributionRepository::getUsedRecords();
        $disciplines = array_combine(
            array_map(
                fn($discipline) => $discipline->getId(),
                $disciplines
            ),
            $disciplines
        );
        $groups = GroupRepository::getGroups();
        $groups = array_combine(
            array_map(
                fn($group) => $group->getId(),
                $groups
            ),
            $groups
        );
        
        $statement = self::$connection->query('
            select ls.id, id_discipline, week_day, id_lesson_number, (variant + 0) as "variant", id_group
                from lesson_schedules ls
            left join work_distribution wd on wd.id = ls.id_discipline
            order by week_day, id_lesson_number, "variant"
        ');
        
        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                if (array_key_exists($statementArray['id_group'], $result)) {
                    $lessonSchedule = $result[$statementArray['id_group']];
                } else {
                    $lessonSchedule = [
                        'group' => $groups[$statementArray['id_group']],
                        'lessons' => []
                    ];
                }
                
                $discipline = $disciplines[$statementArray['id_discipline']];
                
                $lessonScheduleItem = new LessonScheduleItem();
                $lessonScheduleItem->setId((int)$statementArray['id'])
                    ->setLessonNumber((int)$statementArray['id_lesson_number'])
                    ->setDiscipline($discipline)
                    ->setWeekDay((int)$statementArray['week_day'])
                    ->setVariantNumber($statementArray['variant']);
                
                $lessonSchedule['lessons'][] = $lessonScheduleItem;
                
                $result[$statementArray['id_group']] = $lessonSchedule;
            }
        }
        
        $result = array_values($result);
        
        return $result;
    }
    
    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }
    
    public static function getStudentLessonSchedule(int $studentId): array
    {
        self::load();
        $result = [];
        
        $disciplines = WorkDistributionRepository::getUsedRecords();
        $disciplines = array_combine(
            array_map(
                fn($discipline) => $discipline->getId(),
                $disciplines
            ),
            $disciplines
        );
        
        $statement = self::$connection->prepare('
            select ls.id, id_discipline, week_day, id_lesson_number, (variant + 0) as "variant", wd.id_group
                from lesson_schedules ls 
            left join work_distribution wd on wd.id = ls.id_discipline
            left join students s on wd.id_group = s.id_group
            where s.id_student = :id_student
            order by week_day, id_lesson_number, "variant"');
        
        if ($statement !== false) {
            $statement->bindValue(':id_student', $studentId);
            if ($statement->execute()) {
                while (($statementArray = $statement->fetch()) !== false) {
                    $discipline = $disciplines[$statementArray['id_discipline']];
                    
                    $lessonScheduleItem = new LessonScheduleItem();
                    $lessonScheduleItem->setId((int)$statementArray['id'])
                        ->setLessonNumber((int)$statementArray['id_lesson_number'])
                        ->setDiscipline($discipline)
                        ->setWeekDay((int)$statementArray['week_day'])
                        ->setVariantNumber($statementArray['variant']);
                    
                    $result[] = $lessonScheduleItem;
                }
            }
        }
        
        return $result;
    }
    
    public static function getTeacherLessonSchedule(int $teacherId): array
    {
        self::load();
        $result = [];
        
        $disciplines = WorkDistributionRepository::getUsedRecords();
        $disciplines = array_combine(
            array_map(
                fn($discipline) => $discipline->getId(),
                $disciplines
            ),
            $disciplines
        );
        
        $statement = self::$connection->prepare('
            select ls.id, id_discipline, week_day, id_lesson_number, (variant + 0) as "variant", wd.id_group
                from lesson_schedules ls 
            left join work_distribution wd on wd.id = ls.id_discipline
            where id_teacher = :id_teacher
            order by week_day, id_lesson_number, "variant"
        ');
        
        if ($statement !== false) {
            $statement->bindValue(':id_teacher', $teacherId);
            if ($statement->execute()) {
                while (($statementArray = $statement->fetch()) !== false) {
                    $discipline = $disciplines[$statementArray['id_discipline']];
                    
                    $lessonScheduleItem = new LessonScheduleItem();
                    $lessonScheduleItem->setId((int)$statementArray['id'])
                        ->setLessonNumber((int)$statementArray['id_lesson_number'])
                        ->setDiscipline($discipline)
                        ->setWeekDay((int)$statementArray['week_day'])
                        ->setVariantNumber($statementArray['variant']);
                    
                    $result[] = $lessonScheduleItem;
                }
            }
        }
        
        return $result;
    }
    
    public static function updateItems(int $idGroup, array $lessonItemsArray): bool
    {
        self::load();
        $result = false;
        
        if (self::$connection->beginTransaction()) {
            $statement = self::$connection->prepare('
                with group_schedule_items(id) as (
                    select ls.id from lesson_schedules ls
                    left join work_distribution wd
                    on ls.id_discipline = wd.id
                    where wd.id_group = :id_group
                )
                delete from lesson_schedules
                using lesson_schedules
                left join group_schedule_items
                on lesson_schedules.id = group_schedule_items.id
                where group_schedule_items.id > -1;
            ');
            
            if ($statement !== false) {
                $deleteResult = $statement->execute([':id_group' => $idGroup]);
                if ($deleteResult === false) {
                    self::$connection->rollBack();
                } else {
                    $statement = self::$connection->prepare('
                    insert into lesson_schedules
                        (id_discipline, week_day, id_lesson_number, variant) values
                        (:id_discipline, :week_day, :id_lesson_number, :variant)
                ');
                    if ($statement === false) {
                        self::$connection->rollBack();
                    } else {
                        foreach ($lessonItemsArray as $lessonItem) {
                            $insertResult = $statement->execute([
                                ':id_discipline' => $lessonItem->getDiscipline()->getId(),
                                ':week_day' => $lessonItem->getWeekDay(),
                                ':id_lesson_number' => $lessonItem->getLessonNumber(),
                                ':variant' => $lessonItem->getVariantNumber()
                            ]);
                            
                            if ($insertResult === false) {
                                self::$connection->rollBack();
                                break;
                            }
                        }
                        if (self::$connection->inTransaction()) {
                            $result = true;
                            self::$connection->commit();
                        }
                    }
                }
            }
        }
        
        return $result;
    }
}