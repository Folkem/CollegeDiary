<?php

class StorageRepository
{
    use DatabaseSettings;

    private static string $dsn;
    private static PDO $connection;

    private function __construct()
    {
    }

    // public methods

    public static function load(): void
    {
        self::connect();
    }

    public static function getNews(): array
    {
        $result = [];

        $statement = self::$connection->query("select * from news");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $newsItem = new NewsItem();
                $date = $statementArray['date'];
                $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);
                $newsItem->setId((int)$statementArray['id'])
                    ->setHeader($statementArray['header'])
                    ->setText($statementArray['text'])
                    ->setDate($date);
                $result[] = $newsItem;
            }
        }

        return $result;
    }

    public static function getUsers(): array
    {
        $result = [];

        $statement = self::$connection->query("select u.id as 'id', first_name, middle_name, 
                last_name, full_name, email, password, r.user_role as 'role', avatar_path from users u
                left join roles r on u.id_role = r.id");

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
                    ->setRole($statementArray['role'])
                    ->setAvatarPath($statementArray['avatar_path']);
                $result[] = $user;
            }
        }

        return $result;
    }

    // private methods

    private static function connect(): void
    {
        self::$dsn = 'mysql:host=' . self::$HOST . ';dbname=' . self::$DB_NAME;
        self::$connection = new PDO(self::$dsn, self::$USER, self::$PASSWORD);
    }
}