<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/configs.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class StorageRepository
{
    private static PDO $connection;

    private function __construct()
    {
    }

    // public methods

    public static function load(): void
    {
        if (!isset(self::$connection)) {
            self::connect();
        }
    }

    public static function getNews(): array
    {
        self::load();
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

    public static function getCallSchedule(): array
    {
        self::load();
        $result = [];

        $statement = self::$connection->query("select * from call_schedule");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $timeStart = DateTimeImmutable::createFromFormat('H:i:s', $statementArray['start']);
                $timeEnd = DateTimeImmutable::createFromFormat('H:i:s', $statementArray['end']);

                $callScheduleItem = new CallScheduleItem();

                $callScheduleItem->setLessonNumber((int)$statementArray['number'])
                    ->setTimeStart($timeStart)
                    ->setTimeEnd($timeEnd);

                $result[] = $callScheduleItem;
            }
        }

        return $result;
    }

    public static function getNewsCommentsForItem(int $itemId): array
    {
        self::load();
        $result = [];
        $users = self::getUsers();
        $users = array_combine(
            array_map(fn($user) => $user->getId(), $users),
            $users
        );

        $statement = self::$connection->prepare("select * from news_comments nc 
            where nc.id_item = :id_item");

        if ($statement !== false) {
            $statement->bindValue(":id_item", $itemId);
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

    public static function getUsers(): array
    {
        self::load();
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

    public static function addNewsComment(NewsComment $newsComment): bool
    {
        self::load();
        $result = true;

        $statement = self::$connection->prepare('insert into news_comments 
            (id_item, id_user, comment, `date`) values (:id_item, :id_user, :comment_text, :post_date)');

        if ($statement !== false) {
            $idItem = $newsComment->getNewsId();
            $idUser = $newsComment->getUser()->getId();
            $commentText = $newsComment->getComment();
            $postDate = $newsComment->getPostDate();
            $formattedPostDate = $postDate->format('Y-m-d H:m:s');
            $statement->bindValue(':id_item', $idItem);
            $statement->bindValue(':id_user', $idUser);
            $statement->bindValue(':comment_text', $commentText);
            $statement->bindValue(':post_date', $formattedPostDate);

            $result = $statement->execute();
            $lastInsertId = self::$connection->lastInsertId();
            $newsComment->setId($lastInsertId);
        }

        return $result;
    }

    // private methods

    private static function connect(): void
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        self::$connection = new PDO($dsn, DB_USER, DB_PASS);
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}