<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class WorkDistributionRepository
{
    private static PDO $connection;

    private function __construct()
    {
    }

    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }

    public static function updateRecord(WorkDistributionRecord $record): bool
    {
        self::load();
        $result = true;

        $statement = self::$connection->prepare("update work_distribution 
            set subject = :subject, id_group = :id_group, id_teacher = :id_teacher
            where id = :id");

        if ($statement !== false) {
            $statement->bindValue(':id', $record->getId());
            $statement->bindValue(':subject', $record->getSubject());
            $statement->bindValue(':id_group', $record->getGroup()->getId());
            $statement->bindValue(':id_teacher', $record->getTeacher()->getId());

            $result = $statement->execute();
        }

        return $result;
    }

    public static function deleteRecord(int $id): bool
    {
        self::load();
        $result = false;

        $statement = self::$connection->prepare('delete from work_distribution 
            where id = :id');

        if ($statement !== false) {
            $statement->bindValue(':id', $id);

            $result = $statement->execute();
        }

        return $result;
    }

    public static function addRecord(WorkDistributionRecord $record)
    {
        self::load();
        $result = false;

        $statement = self::$connection->prepare('insert into work_distribution 
            (subject, id_group, id_teacher) values (:subject, :id_group, :id_teacher)');

        if ($statement !== false) {
            $statement->bindValue(':subject', $record->getSubject());
            $statement->bindValue(':id_group', $record->getGroup()->getId());
            $statement->bindValue(':id_teacher', $record->getTeacher()->getId());

            if ($result = $statement->execute()) {
                $result = self::$connection->lastInsertId();
            }
        }

        return $result;
    }

    public static function addRecords(array $recordsToAdd): array
    {
        self::load();

        $result = [
            'addedCount' => 0,
            'error_messages' => []
        ];

        $statement = self::$connection->prepare('insert into work_distribution 
            (subject, id_group, id_teacher) values (:subject, :id_group, :id_teacher)');

        $statement->bindParam(':subject', $subject);
        $statement->bindParam(':id_group', $idGroup);
        $statement->bindParam(':id_teacher', $idTeacher);

        foreach ($recordsToAdd as $record) {
            try {
                $subject = $record->getSubject();
                $idGroup = $record->getGroup()->getId();
                $idTeacher = $record->getTeacher()->getId();

                $added = $statement->execute();
                if ($added) {
                    $result['addedCount']++;
                }
            } catch (Exception $e) {
                if ($e->getCode() == DatabaseErrors::DUPLICATE_ENTRY) {
                    $result['error_messages'][] = 'Запис "' . $record->getFullName() . '" вже є';
                } else {
                    $result['error_messages'][] = 'Помилка ' . $e->getCode();
                }
            }
        }

        return $result;
    }

    public static function getRecords(): array
    {
        self::load();
        $result = [];

        $teachers = UserRepository::getUsersWithRole(UserRoles::TEACHER);
        $teachers = array_combine(
            array_map(
                fn($teacher) => $teacher->getId(),
                $teachers
            ),
            $teachers
        );
        $groups = GroupRepository::getGroups();
        $groups = array_combine(
            array_map(
                fn($group) => $group->getId(),
                $groups
            ),
            $groups
        );

        $statement = self::$connection->query("select * from work_distribution");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $teacher = $teachers[$statementArray['id_teacher']];
                $group = $groups[$statementArray['id_group']];

                $record = (new WorkDistributionRecord())
                    ->setId((int)$statementArray['id'])
                    ->setSubject($statementArray['subject'])
                    ->setTeacher($teacher)
                    ->setGroup($group);
                $result[] = $record;
            }
        }

        return $result;
    }

    public static function getRecordById(int $id): ?WorkDistributionRecord
    {
        self::load();
        $result = null;

        $statement = self::$connection->prepare('select * from work_distribution where id = :id');

        if ($statement !== false) {
            $statement->bindValue(':id', $id);

            if ($statement->execute() !== false) {
                $statementArray = $statement->fetch();

                $teacher = UserRepository::getUserById($statementArray['id_teacher']);
                $group = GroupRepository::getGroupById($statementArray['id_group']);

                $result = new WorkDistributionRecord();
                $result->setId($statementArray['id']);
                $result->setSubject($statementArray['subject']);
                $result->setTeacher($teacher);
                $result->setGroup($group);
            }
        }

        return $result;
    }

    public static function getUsedRecords(): array
    {
        self::load();
        $result = [];

        $teachers = UserRepository::getUsersWithRole(UserRoles::TEACHER);
        $teachers = array_combine(
            array_map(
                fn($teacher) => $teacher->getId(),
                $teachers
            ),
            $teachers
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
            select work_distribution.id, subject, id_group, id_teacher
            from work_distribution 
                inner join lesson_schedules ls on work_distribution.id = ls.id_discipline
            group by work_distribution.id
        ');

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $teacher = $teachers[$statementArray['id_teacher']];
                $group = $groups[$statementArray['id_group']];

                $record = (new WorkDistributionRecord())
                    ->setId((int)$statementArray['id'])
                    ->setSubject($statementArray['subject'])
                    ->setTeacher($teacher)
                    ->setGroup($group);
                $result[] = $record;
            }
        }

        return $result;
    }
}