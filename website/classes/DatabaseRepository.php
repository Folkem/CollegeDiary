<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";


class DatabaseRepository
{
    private static PDO $connection;

    private function __construct()
    {
    }

    // public methods

    /**
     * @return PDO database connection
     */
    public static function &getConnection(): PDO
    {
        self::load();
        return self::$connection;
    }

    // private methods

    private static function load(): void
    {
        if (!isset(self::$connection)) {
            self::connect();
        }
    }

    private static function connect(): void
    {
        $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
        self::$connection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}