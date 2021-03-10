<?php


class LessonType
{
    private function __construct() {}
    
    public static function getValues(): array
    {
        return [
            1 => 'Лекція',
            2 => 'Практична робота',
            3 => 'Лабораторна робота',
            4 => 'Самостійна робота',
            5 => 'Контрольна робота',
            6 => 'Залік',
            7 => 'Екзамен'
        ];
    }
}