<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";


class NotificationRepository
{
    private static PDO $connection;

    private function __construct() {}

    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }

    public static function getNotificationsForUser(int $idUser): array
    {
        self::load();
        $result = [];

        $statement = self::$connection->prepare("select * from notifications 
            where id_user = :id_user
            order by `read`, date desc");

        if ($statement !== false) {
            $statement->bindValue(':id_user', $idUser);
            $statement->execute();
            while (($statementArray = $statement->fetch()) !== false) {
                $notification = new Notification();
                $date = DateTimeImmutable::createFromFormat(
                    'Y-m-d H:i:s',
                    $statementArray['date']
                );
                $notification
                    ->setId((int)$statementArray['id'])
                    ->setUserId((int)$statementArray['id_user'])
                    ->setComment($statementArray['comment'])
                    ->setLink($statementArray['link'])
                    ->setRead((bool)$statementArray['read'])
                    ->setPublishDate($date);
                $result[] = $notification;
            }
        }

        return $result;
    }

    public static function getUnreadNotificationCountForUser(int $idUser): int
    {
        self::load();
        $result = 0;

        $statement = self::$connection->prepare("select count(*) as 'count' 
            from notifications where id_user = :id_user and `read` = 0");

        if ($statement !== false) {
            $statement->bindValue(':id_user', $idUser);
            $statement->execute();
            if (($statementArray = $statement->fetch()) !== false) {
                $result = $statementArray['count'];
            }
        }

        return $result;
    }

    /** @noinspection DuplicatedCode */
    public static function markReadNotifications(int $idUser, array $notificationIds): array
    {
        self::load();
        $result = [];

        $statement = self::$connection->prepare("update notifications
            set `read` = 1 where id = :id and id_user = :id_user");

        if ($statement !== false) {
            $id = $notificationIds[0];
            $statement->bindValue(':id_user', $idUser);
            $statement->bindParam(':id', $id);

            foreach ($notificationIds as $id) {
                $updated = $statement->execute();

                if ($updated) {
                    $result[] = $id;
                }
            }
        }

        return $result;
    }

    /** @noinspection DuplicatedCode */
    public static function deleteNotifications(int $idUser, array $notificationIds): array
    {
        self::load();
        $result = [];

        $statement = self::$connection->prepare("delete from notifications
            where id = :id and id_user = :id_user");

        if ($statement !== false) {
            $id = $notificationIds[0];
            $statement->bindValue(':id_user', $idUser);
            $statement->bindParam(':id', $id);

            foreach ($notificationIds as $id) {
                $updated = $statement->execute();

                if ($updated) {
                    $result[] = $id;
                }
            }
        }

        return $result;
    }
}