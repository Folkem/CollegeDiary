<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('general');
$logger->pushHandler(new StreamHandler(
    $_SERVER['DOCUMENT_ROOT'] . "/log/debug.log",
    Logger::DEBUG
));
$logger->pushHandler(new StreamHandler(
    $_SERVER['DOCUMENT_ROOT'] . "/log/info.log",
    Logger::INFO,
    false
));
$logger->pushHandler(new StreamHandler(
    $_SERVER['DOCUMENT_ROOT'] . "/log/error.log",
    Logger::WARNING,
    false
));
$logger->pushHandler(new StreamHandler(
    $_SERVER['DOCUMENT_ROOT'] . "/log/all.log",
    Logger::DEBUG
));

$GLOBALS['logger'] = $logger;