<?php

require_once "loader.php";

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('general');
$logger->pushHandler(new StreamHandler(__DIR__ . "/logs/debug.log", Logger::DEBUG));
$logger->pushHandler(new StreamHandler(__DIR__ . "/logs/info.log", Logger::INFO, false));
$logger->pushHandler(new StreamHandler(__DIR__ . "/logs/error.log", Logger::WARNING, false));
$logger->pushHandler(new StreamHandler(__DIR__ . "/logs/all.log", Logger::DEBUG));