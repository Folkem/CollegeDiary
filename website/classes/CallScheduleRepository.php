<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

class CallScheduleRepository
{
    private static PDO $connection;

    private function __construct() {}

    private static function load(): void
    {
        self::$connection = StorageRepository::getConnection();
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
}