<?php


class LessonScheduleVariant
{
    private function __construct() {}

    public static function getValues(): array
    {
        return [
            1 => 'Чисельник',
            2 => 'Знаменник',
            3 => 'Постійно'
        ];
    }
}