<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class UserRepository
{
    private static PDO $connection;

    private function __construct()
    {
    }

    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }

    public static function getUsers(): array
    {
        self::load();
        $result = [];

        $statement = self::$connection->query("select id, first_name, middle_name,
                last_name, full_name, email, password, id_role as 'role', avatar_path from users");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $user = new User();
                $user->setId((int)$statementArray['id'])
                    ->setFirstName($statementArray['first_name'])
                    ->setMiddleName($statementArray['middle_name'])
                    ->setLastName($statementArray['last_name'])
                    ->setFullName($statementArray['full_name'])
                    ->setEmail($statementArray['email'])
                    ->setPassword($statementArray['password'])
                    ->setRole((int)$statementArray['role'])
                    ->setAvatarPath($statementArray['avatar_path']);
                $result[] = $user;
            }
        }

        return $result;
    }

    public static function getUserById(int $id): ?User
    {
        return self::getUserByField('id', $id);
    }

    public static function getUserByEmail(string $email): ?User
    {
        return self::getUserByField('email', $email);
    }

    public static function updateUser(User $user): bool
    {
        self::load();
        $result = true;

        $statement = self::$connection->prepare("update users 
            set first_name = :first_name, middle_name = :middle_name,
                last_name = :last_name, email = :email, id_role = :id_role,
                password = :password, avatar_path = :avatar_path
            where users.id = :id");

        if ($statement !== false) {
            $statement->bindValue(':id', $user->getId());
            $statement->bindValue(':first_name', $user->getFirstName());
            $statement->bindValue(':middle_name', $user->getMiddleName());
            $statement->bindValue(':last_name', $user->getLastName());
            $statement->bindValue(':email', $user->getEmail());
            $statement->bindValue(':id_role', $user->getRole());
            $statement->bindValue(':password', $user->getPassword());
            $statement->bindValue(':avatar_path', $user->getAvatarPath());

            $result = $statement->execute();
        }

        return $result;
    }

    public static function deleteUser(int $userId): bool
    {
        self::load();
        $result = false;

        $statement = self::$connection->prepare('delete from users where users.id = :id');

        if ($statement !== false) {
            $statement->bindValue(':id', $userId);
            $result = $statement->execute();
        }

        return $result;
    }

    public static function addUsers(array $usersToAdd): array
    {
        self::load();

        $result = [
            'addedCount' => 0,
            'error_messages' => []
        ];

        $statement = self::$connection->prepare('insert into users 
            (first_name, middle_name, last_name, email, password, id_role) 
            values (:first_name, :middle_name, :last_name, :email, :password, :id_role)');

        $firstName = null;
        $middleName = null;
        $lastName = null;
        $email = null;
        $password = null;
        $idRole = null;
        $statement->bindParam(':first_name', $firstName);
        $statement->bindParam(':middle_name', $middleName);
        $statement->bindParam(':last_name', $lastName);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':id_role', $idRole);

        foreach ($usersToAdd as $user) {
            try {
                $firstName = $user->getFirstName();
                $middleName = $user->getMiddleName();
                $lastName = $user->getLastName();
                $email = $user->getEmail();
                $password = $user->getPassword();
                $idRole = $user->getRole();

                $added = $statement->execute();
                if ($added) {
                    $result['addedCount']++;
                }
            } catch (Exception $e) {
                if ($e->getCode() == DatabaseErrors::DUPLICATE_ENTRY) {
                    $result['error_messages'][] = 'Запис ' . $user->getEmail() . ' вже є';
                } else {
                    $result['error_messages'][] = 'Помилка ' . $e->getCode();
                }
            }
        }

        return $result;
    }

    private static function getUserByField(string $field, string $value): ?User
    {
        self::load();
        $result = null;

        $actualField = null;
        switch ($field) {
            case 'id':
                $actualField = 'id';
                break;
            case 'email':
                $actualField = 'email';
                break;
        }

        if ($actualField !== null) {
            $statement = self::$connection->prepare("select id, first_name, middle_name,
                last_name, full_name, email, password, id_role as 'role', avatar_path from users
                where $actualField = :value");

            if ($statement !== false) {
                $statement->bindValue(':value', $value);
                $statement->execute();

                if (($statementArray = $statement->fetch()) !== false) {
                    $user = new User();
                    $user->setId((int)$statementArray['id'])
                        ->setFirstName($statementArray['first_name'])
                        ->setMiddleName($statementArray['middle_name'])
                        ->setLastName($statementArray['last_name'])
                        ->setFullName($statementArray['full_name'])
                        ->setEmail($statementArray['email'])
                        ->setPassword($statementArray['password'])
                        ->setRole((int)$statementArray['role'])
                        ->setAvatarPath($statementArray['avatar_path']);

                    $result = $user;
                }
            }
        }

        return $result;
    }
}