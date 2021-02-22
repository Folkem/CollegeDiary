<?php


class WeekDay
{
    private function __construct() {}

    public static function getValues(): array
    {
        return [
            1 => 'Понеділок',
            2 => 'Вівторок',
            3 => 'Середа',
            4 => 'Четверг',
            5 => 'П\'ятниця'
        ];
    }
}