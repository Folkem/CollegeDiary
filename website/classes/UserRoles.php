<?php


/**
 * Доступні ролі користувачів
 */
class UserRoles
{
    private function __construct() {}

    /**
     * Адміністратор (администратор)
     */
    public const ADMINISTRATOR = 1;
    /**
     * Студент
     */
    public const STUDENT = 2;
    /**
     * Батько (родитель)
     */
    public const PARENT = 3;
    /**
     * Викладач (преподаватель)
     */
    public const TEACHER = 4;
    /**
     * Завідуючий відділенням (заведующий отделением)
     */
    public const DEPARTMENT_HEAD = 5;

    public static function getValues(): array
    {
        return [
            1 => 'Адміністратор',
            2 => 'Студент',
            3 => 'Батько',
            4 => 'Вчитель',
            5 => 'Зав. відділенням'
        ];
    }
}