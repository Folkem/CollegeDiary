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
}