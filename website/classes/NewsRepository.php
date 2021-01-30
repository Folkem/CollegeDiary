<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class NewsRepository
{
    private static PDO $connection;

    private function __construct()
    {
    }

    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
    }

    public static function getNews(): array
    {
        self::load();
        $result = [];

        $statement = self::$connection->query("select n.*,
            ifnull(group_concat(k.name separator ' '), '') as 'keywords'
            from news n
            left join news_keywords nk on n.id = nk.id_news
            left join keywords k on k.id = nk.id_keyword
            group by n.id;");

        if ($statement !== false) {
            while (($statementArray = $statement->fetch()) !== false) {
                $newsItem = new NewsItem();

                $date = $statementArray['publish_date'];
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

    public static function getNewsCommentsForItem(int $itemId): array
    {
        self::load();
        $result = [];
        $users = UserRepository::getUsers();
        $users = array_combine(
            array_map(fn($user) => $user->getId(), $users),
            $users
        );

        $statement = self::$connection->prepare("select * from news_comments 
            where id_item = :id_item");

        if ($statement !== false) {
            $statement->bindValue(":id_item", $itemId);
            $statement->execute();
            while (($statementArray = $statement->fetch()) !== false) {
                $user = $users[(int)$statementArray['id_user']];
                $date = $statementArray['publish_date'];
                $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);

                $comment = new NewsComment();
                $comment->setId((int)$statementArray['id'])
                    ->setNewsId((int)$statementArray['id_item'])
                    ->setUser($user)
                    ->setPublishDate($date)
                    ->setComment($statementArray['comment']);

                $result[] = $comment;
            }
        }

        return $result;
    }

    public static function addNewsComment(NewsComment $newsComment): bool
    {
        self::load();
        $result = true;

        $statement = self::$connection->prepare('insert into news_comments 
            (id_item, id_user, comment, publish_date) 
            values (:id_item, :id_user, :comment_text, :publish_date)');

        if ($statement !== false) {
            $idItem = $newsComment->getNewsId();
            $idUser = $newsComment->getUser()->getId();
            $commentText = $newsComment->getComment();
            $publishDate = $newsComment->getPublishDate();
            $formattedPublishDate = $publishDate->format('Y-m-d H:m:s');
            $statement->bindValue(':id_item', $idItem);
            $statement->bindValue(':id_user', $idUser);
            $statement->bindValue(':comment_text', $commentText);
            $statement->bindValue(':publish_date', $formattedPublishDate);

            $result = $statement->execute();
            $lastInsertId = self::$connection->lastInsertId();
            $newsComment->setId($lastInsertId);
        }

        return $result;
    }
}