<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";


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

        $statement = self::$connection->query('select * from users');

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
                    ->setRole((int)$statementArray['id_role'])
                    ->setAvatarPath($statementArray['avatar_path']);
                $result[] = $user;
            }
        }

        return $result;
    }

    public static function getUsersWithRole(int $role): array
    {
        return self::getUsersByField('id_role', $role, false);
    }

    public static function getUserById(int $id): ?User
    {
        return self::getSingleUserByField('id', $id);
    }

    public static function getUserByEmail(string $email): ?User
    {
        return self::getSingleUserByField('email', $email);
    }

    public static function updateUser(User $user): bool
    {
        self::load();
        $result = true;

        $statement = self::$connection->prepare('update users
            set first_name = :first_name, middle_name = :middle_name,
                last_name = :last_name, email = :email, id_role = :id_role,
                password = :password, avatar_path = :avatar_path
            where users.id = :id');

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

    public static function deleteUser($userValue, string $userField = 'id'): bool
    {
        self::load();
        $result = false;

        $actualField = null;

        switch ($userField) {
            case 'id':
                $actualField = 'id';
                break;
            case 'email':
                $actualField = 'email';
                break;
        }

        $statement = self::$connection->prepare("delete from users 
            where $actualField = :value");

        if ($statement !== false) {
            $statement->bindValue(':value', $userValue);
            $result = $statement->execute();
        }

        return $result;
    }

    public static function addUser(User $user)
    {
        self::load();
        $result = false;

        $statement = self::$connection->prepare('insert into users 
            (first_name, middle_name, last_name, email, password, id_role) values 
            (:first_name, :middle_name, :last_name, :email, :password, :id_role)');

        if ($statement !== false) {
            $statement->bindValue(':first_name', $user->getFirstName());
            $statement->bindValue(':middle_name', $user->getMiddleName());
            $statement->bindValue(':last_name', $user->getLastName());
            $statement->bindValue(':email', $user->getEmail());
            $statement->bindValue(':password', $user->getPassword());
            $statement->bindValue(':id_role', $user->getRole());

            if ($result = $statement->execute()) {
                $result = self::$connection->lastInsertId();
            }
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

    private static function getSingleUserByField(string $field, string $value): ?User
    {
        return self::getUsersByField($field, $value, true)[0] ?? null;
    }

    private static function getUsersByField(string $field, $value, bool $single): array
    {
        self::load();

        $result = [];

        $actualField = null;
        switch ($field) {
            case 'id':
                $actualField = 'id';
                break;
            case 'email':
                $actualField = 'email';
                break;
            case 'id_role':
                $actualField = 'id_role';
                break;
        }

        if ($actualField !== null) {
            $statement = self::$connection->prepare("select * from users
                where $actualField = :value");

            if ($statement !== false) {
                $statement->bindValue(':value', $value);
                $statement->execute();

                while (($statementArray = $statement->fetch()) !== false) {
                    $user = new User();
                    $user->setId((int)$statementArray['id'])
                        ->setFirstName($statementArray['first_name'])
                        ->setMiddleName($statementArray['middle_name'])
                        ->setLastName($statementArray['last_name'])
                        ->setFullName($statementArray['full_name'])
                        ->setEmail($statementArray['email'])
                        ->setPassword($statementArray['password'])
                        ->setRole((int)$statementArray['id_role'])
                        ->setAvatarPath($statementArray['avatar_path']);

                    $result[] = $user;
                    if ($single) {
                        break;
                    }
                }
            }
        }

        return $result;
    }
    
    public static function getStudentForParent(int $idParent): ?User
    {
        self::load();
        $result = null;
        
        $statement = self::$connection->prepare('select u.*
            from users u
            right join students s on u.id = s.id_student
            right join parents p on s.id = p.id_student
            where p.id_parent = :id_parent');
        
        if ($statement !== false) {
            if ($statement->execute(['id_parent' => $idParent])) {
                if (($statementArray = $statement->fetch()) !== false) {
                    $student = new User();
                    
                    $student->setId((int)$statementArray['id'])
                        ->setFirstName($statementArray['first_name'])
                        ->setMiddleName($statementArray['middle_name'])
                        ->setLastName($statementArray['last_name'])
                        ->setFullName($statementArray['full_name'])
                        ->setEmail($statementArray['email'])
                        ->setPassword($statementArray['password'])
                        ->setRole((int)$statementArray['id_role'])
                        ->setAvatarPath($statementArray['avatar_path']);
                    
                    $result = $student;
                }
            }
        }
        
        return $result;
    }
    
    public static function getStudentsByGroup(int $idGroup): array
    {
        self::load();
        $result = [];
    
        $statement = self::$connection->prepare('
            select u.* from users u
            left join students s on u.id = s.id_student
            where s.id_group = :id_group
        ');
    
        if ($statement !== false) {
            if ($statement->execute([':id_group' => $idGroup])) {
                while (($statementArray = $statement->fetch()) !== false) {
                    $user = new User();
                    $user->setId((int)$statementArray['id'])
                        ->setFirstName($statementArray['first_name'])
                        ->setMiddleName($statementArray['middle_name'])
                        ->setLastName($statementArray['last_name'])
                        ->setFullName($statementArray['full_name'])
                        ->setEmail($statementArray['email'])
                        ->setPassword($statementArray['password'])
                        ->setRole((int)$statementArray['id_role'])
                        ->setAvatarPath($statementArray['avatar_path']);
                    $result[] = $user;
                }
            }
        }
        
        return $result;
    }
    
    public static function getStudentById(int $idStudent): ?User
    {
        self::load();
        $result = null;
    
        $statement = self::$connection->prepare('
            select u.* from users u
            left join students s on u.id = s.id_student
            where s.id = :id
        ');
        
        if ($statement !== false) {
            if ($statement->execute([':id' => $idStudent])) {
                if (($statementArray = $statement->fetch()) !== false) {
                    $user = (new User())
                        ->setId((int)$statementArray['id'])
                        ->setFirstName($statementArray['first_name'])
                        ->setMiddleName($statementArray['middle_name'])
                        ->setLastName($statementArray['last_name'])
                        ->setFullName($statementArray['full_name'])
                        ->setEmail($statementArray['email'])
                        ->setPassword($statementArray['password'])
                        ->setRole((int)$statementArray['id_role'])
                        ->setAvatarPath($statementArray['avatar_path']);
                    $result = $user;
                }
            }
        }
        
        return $result;
    }
    
    public static function getStudentsByDiscipline(int $idDiscipline): array
    {
        self::load();
        $result = [];
        
        $statement = self::$connection->prepare('
            select u.* from users u
            left join students s on u.id = s.id_student
            left join work_distribution wd on s.id_group = wd.id_group
            where wd.id = :id_discipline
        ');
        
        if ($statement !== false) {
            if ($statement->execute([':id_discipline' => $idDiscipline])) {
                while (($statementArray = $statement->fetch()) !== false) {
                    $user = new User();
                    $user->setId((int)$statementArray['id'])
                        ->setFirstName($statementArray['first_name'])
                        ->setMiddleName($statementArray['middle_name'])
                        ->setLastName($statementArray['last_name'])
                        ->setFullName($statementArray['full_name'])
                        ->setEmail($statementArray['email'])
                        ->setPassword($statementArray['password'])
                        ->setRole((int)$statementArray['id_role'])
                        ->setAvatarPath($statementArray['avatar_path']);
                    $result[] = $user;
                }
            }
        }
        
        return $result;
    }
    
    public static function getStudentIdByUserId(int $idUser): ?int
    {
        self::load();
        $result = null;
        
        $statement = self::$connection->prepare('
            select s.id from students s
            left join users u on s.id_student = u.id
            where u.id = :id_user
        ');
        
        if ($statement !== false) {
            if ($statement->execute([':id_user' => $idUser])) {
                if (($statementArray = $statement->fetch()) !== false) {
                    $result = (int) $statementArray['id'];
                }
            }
        }
        
        return $result;
    }
}