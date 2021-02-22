<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class LessonScheduleRepository
{
    private static PDO $connection;

    private function __construct() {}

    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }

    public static function getLessonSchedules(): array
    {
        self::load();
        $result = [];

        $callScheduleItems = CallScheduleRepository::getCallSchedule();
        $callScheduleItems = array_combine(
            array_map(
                fn($callScheduleItem) => $callScheduleItem->getId(),
                $callScheduleItems
            ),
            $callScheduleItems
        );
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
        ');

        if ($statement !== false) {
            $lessonSchedule = [
                'group' => null,
                'items' => []
            ];

            while (($statementArray = $statement->fetch()) !== false) {
                if (is_null($lessonSchedule['group'])) {
                    $group = $groups[$statementArray['id_group']];
                    $lessonSchedule['group'] = $group;
                }

                $discipline = $disciplines[$statementArray['id_discipline']];
                $lessonNumber = $callScheduleItems[$statementArray['id_lesson_number']];

                $lessonScheduleItem = new LessonScheduleItem();
                $lessonScheduleItem->setId((int)$statementArray['id'])
                    ->setLessonNumber($lessonNumber)
                    ->setDiscipline($discipline)
                    ->setWeekDay((int)$statementArray['week_day'])
                    ->setVariantNumber($statementArray['variant']);

                $lessonSchedule['items'][] = $lessonScheduleItem;
            }

            $result[] = $lessonSchedule;
        }

        return $result;
    }

    public static function getStudentLessonSchedule(int $studentId): array
    {
        self::load();
        $result = [];

        $callScheduleItems = CallScheduleRepository::getCallSchedule();
        $callScheduleItems = array_combine(
            array_map(
                fn($callScheduleItem) => $callScheduleItem->getId(),
                $callScheduleItems
            ),
            $callScheduleItems
        );
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
                    $lessonNumber = $callScheduleItems[$statementArray['id_lesson_number']];

                    $lessonScheduleItem = new LessonScheduleItem();
                    $lessonScheduleItem->setId((int)$statementArray['id'])
                        ->setLessonNumber($lessonNumber)
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

        $callScheduleItems = CallScheduleRepository::getCallSchedule();
        $callScheduleItems = array_combine(
            array_map(
                fn($callScheduleItem) => $callScheduleItem->getId(),
                $callScheduleItems
            ),
            $callScheduleItems
        );
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
                    $lessonNumber = $callScheduleItems[$statementArray['id_lesson_number']];

                    $lessonScheduleItem = new LessonScheduleItem();
                    $lessonScheduleItem->setId((int)$statementArray['id'])
                        ->setLessonNumber($lessonNumber)
                        ->setDiscipline($discipline)
                        ->setWeekDay((int)$statementArray['week_day'])
                        ->setVariantNumber($statementArray['variant']);

                    $result[] = $lessonScheduleItem;
                }
            }
        }

        return $result;
    }
}