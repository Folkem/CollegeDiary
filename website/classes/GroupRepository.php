<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class GroupRepository
{
    private static PDO $connection;

    private function __construct()
    {
    }

    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }

    public static function getGroups(): array
    {
        self::load();

        $specialities = self::getSpecialities();
        $specialityMap = array_combine(
            array_map(
                fn($speciality) => $speciality->getId(),
                $specialities
            ),
            $specialities
        );

        $result = [];

        $statement = self::$connection->query("select * from `groups`");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $group = new Group();
                $group->setId($statementArray['id'])
                    ->setGroupNumber($statementArray['group_number'])
                    ->setGroupYear($statementArray['group_year'])
                    ->setSpeciality($specialityMap[$statementArray['id_speciality']]);
                $result[] = $group;
            }
        }

        return $result;
    }

    public static function getStudentGroupDistribution(): array
    {
        self::load();

        $result = [];

        $statement = self::$connection->query("select * from `students`");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $studentId = $statementArray['id_student'];
                $groupId = $statementArray['id_group'];
                $result[$studentId] = $groupId;
            }
        }

        return $result;
    }

    public static function getSpecialities(): array
    {
        self::load();

        $result = [];

        $statement = self::$connection->query("select * from `specialities`");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $speciality = new Speciality();
                $speciality->setId($statementArray['id'])
                    ->setFullName($statementArray['full_name'])
                    ->setCode($statementArray['code'])
                    ->setShortName($statementArray['short_name']);
                $result[] = $speciality;
            }
        }

        return $result;
    }

    public static function updateStudentGroup(int $idStudent, int $idGroup): bool
    {
        self::load();
        $result = false;

        $statement = self::$connection->prepare('replace into students 
            (id_group, id_student) values (:id_group, :id_student)');

        if ($statement !== false) {
            $statement->bindValue(':id_group', $idGroup);
            $statement->bindValue(':id_student', $idStudent);
            $result = $statement->execute();
        }

        return $result;
    }

    public static function removeStudentGroup(int $idStudent): bool
    {
        self::load();
        $result = false;

        $statement = self::$connection->prepare('delete from students where id_student = :id');

        if ($statement !== false) {
            $result = $statement->execute(['id' => $idStudent]);
        }

        return $result;
    }

    public static function getGroupById(int $id): ?Group
    {
        self::load();
        $result = null;

        $statement = self::$connection->prepare('select * from `groups` where id = :id');

        if ($statement !== false) {
            $statement->bindValue(':id', $id);
            if ($statement->execute() !== false) {
                $statementArray = $statement->fetch();

                $speciality = array_filter(
                    self::getSpecialities(),
                    fn($speciality) => $speciality->getId()
                )[0];

                $result = new Group();
                $result->setId($statementArray['id']);
                $result->setSpeciality($speciality);
                $result->setGroupYear($statementArray['group_year']);
                $result->setGroupNumber($statementArray['group_number']);
            }
        }

        return $result;
    }
}