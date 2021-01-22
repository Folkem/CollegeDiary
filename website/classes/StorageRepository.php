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

        $statement = self::$connection->query("call get_news();");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $newsItem = new NewsItem();
                $date = $statementArray['date'];
                $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);
                $keywords = explode(' ', $statementArray['keywords']);
                $keywords = array_filter($keywords);
                $newsItem->setId((int)$statementArray['id'])
                    ->setHeader($statementArray['header'])
                    ->setText($statementArray['text'])
                    ->setDate($date)
                    ->setImagePath($statementArray['image_path'])
                    ->setKeywords($keywords);
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

    public static function getNewsCommentsForItem(int $itemId): array
    {
        $result = [];
        $users = self::getUsers();
        $users = array_combine(
            array_map(fn($user) => $user->getId(), $users),
            $users
        );

        $statement = self::$connection->prepare("select * from news_comments nc 
            where nc.id_item = :id_item");

        if ($statement !== false) {
            $statement->bindParam(":id_item", $itemId);
            $statement->execute();
            while (($statementArray = $statement->fetch()) !== false) {
                $user = $users[(int)$statementArray['id_user']];
                $date = $statementArray['date'];
                $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);

                $comment = new NewsComment();
                $comment->setId((int)$statementArray['id'])
                    ->setNewsId((int)$statementArray['id_item'])
                    ->setUser($user)
                    ->setPostDate($date)
                    ->setComment($statementArray['comment']);

                $result[] = $comment;
            }
        }

        return $result;
    }

    public static function updateUser(User $updatedUser): bool
    {
        $result = true;

        $statement = self::$connection->prepare("update users 
            set password = :password, avatar_path = :avatar_path
            where users.id = :id");

        if ($statement !== false) {
            $newPassword = $updatedUser->getPassword();
            $newAvatarPath = $updatedUser->getAvatarPath();
            $id = $updatedUser->getId();
            $statement->bindParam(':password', $newPassword);
            $statement->bindParam(':avatar_path', $newAvatarPath);
            $statement->bindParam(':id', $id);

            $result = $statement->execute();
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