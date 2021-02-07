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

    public static function addRecords(array $recordsToAdd): array
    {
        self::load();

        $result = [
            'addedCount' => 0,
            'error_messages' => []
        ];

        $statement = self::$connection->prepare('insert into work_distribution 
            (subject, id_group, id_teacher) values (:subject, :id_group, :id_teacher)');

        $subject = null;
        $idGroup = null;
        $idTeacher = null;
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
        $groups = GroupRepository::getGroupList();
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
}