<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class CallScheduleRepository
{
    private static PDO $connection;

    private function __construct() {}

    private static function load(): void
    {
        self::$connection = DatabaseRepository::getConnection();
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

                $callScheduleItem->setId((int)$statementArray['id'])
                    ->setLessonNumber((int)$statementArray['number'])
                    ->setTimeStart($timeStart)
                    ->setTimeEnd($timeEnd);

                $result[] = $callScheduleItem;
            }
        }

        return $result;
    }

    public static function getCallScheduleItemById(int $id): ?CallScheduleItem
    {
        self::load();
        $result = null;

        $statement = self::$connection->prepare('select * from call_schedule 
            where id = :id');

        if ($statement !== false) {
            $statement->bindValue(':id', $id);
            if ($statement->execute()) {
                $statementArray = $statement->fetch();

                $timeStart = DateTimeImmutable::createFromFormat('H:i:s', $statementArray['start']);
                $timeEnd = DateTimeImmutable::createFromFormat('H:i:s', $statementArray['end']);

                $callScheduleItem = new CallScheduleItem();
                $callScheduleItem->setId((int)$statementArray['id'])
                    ->setLessonNumber((int)$statementArray['number'])
                    ->setTimeStart($timeStart)
                    ->setTimeEnd($timeEnd);

                $result = $callScheduleItem;
            }
        }

        return $result;
    }

    public static function updateCallScheduleItem(CallScheduleItem $item): bool
    {
        self::load();
        $result = false;

        $statement = self::$connection->prepare('update call_schedule 
            set start = :time_start, end = :time_end
            where id = :id');

        if ($statement !== false) {
            $statement->bindValue(':time_start', $item->getTimeStart()->format('H:i:s'));
            $statement->bindValue(':time_end', $item->getTimeEnd()->format('H:i:s'));
            $statement->bindValue(':id', $item->getId());

            if ($statement->execute() && $statement->rowCount() > 0) {
                $result = true;
            }
        }

        return $result;
    }
}