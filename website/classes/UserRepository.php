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

    public static function getUserByEmail(string $email): ?User
    {
        self::load();
        $result = null;

        $statement = self::$connection->prepare("select u.id as 'id', first_name, middle_name,
                last_name, full_name, email, password, r.user_role as 'role', avatar_path from users u
                left join roles r on u.id_role = r.id
                where email = :email");

        if ($statement !== false) {
            $statement->bindValue(':email', $email);
            $statement->execute();

            while (($statementArray = $statement->fetch()) !== false) {
                if (isset($result)) {

                    break;
                }

                $user = new User();
                $user->setId((int)$statementArray['id'])
                    ->setFirstName($statementArray['first_name'])
                    ->setMiddleName($statementArray['middle_name'])
                    ->setLastName($statementArray['last_name'])
                    ->setFullName($statementArray['full_name'])
                    ->setEmail($statementArray['email'])
                    ->setPassword($statementArray['password'])
                    ->setRole($statementArray['role'])
                    ->setAvatarPath($statementArray['avatar_path']);

                $result = $user;
            }
        }

        return $result;
    }

    public static function updateUser(User $updatedUser): bool
    {
        self::load();
        $result = true;

        $statement = self::$connection->prepare("update users 
            set password = :password, avatar_path = :avatar_path
            where users.id = :id");

        if ($statement !== false) {
            $newPassword = $updatedUser->getPassword();
            $newAvatarPath = $updatedUser->getAvatarPath();
            $id = $updatedUser->getId();
            $statement->bindValue(':password', $newPassword);
            $statement->bindValue(':avatar_path', $newAvatarPath);
            $statement->bindValue(':id', $id);

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
                if ($e->getCode() == 23000) {
                    $result['error_messages'][] = 'Запис ' . $user->getEmail() . ' вже є';
                } else {
                    $result['error_messages'][] = 'Помилка ' . $e->getCode();
                }
            }
        }

        return $result;
    }
}