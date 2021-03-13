<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('general');
$logger->pushHandler(new StreamHandler(
    $_SERVER['DOCUMENT_ROOT'] . '/../log/debug.log',
    Logger::DEBUG
));
$logger->pushHandler(new StreamHandler(
    $_SERVER['DOCUMENT_ROOT'] . '/../log/info.log',
    Logger::INFO,
    false
));
$logger->pushHandler(new StreamHandler(
    $_SERVER['DOCUMENT_ROOT'] . '/../log/error.log',
    Logger::WARNING,
    false
));
$logger->pushHandler(new StreamHandler(
    $_SERVER['DOCUMENT_ROOT'] . '/../log/all.log',
    Logger::DEBUG
));

set_exception_handler(function ($exception) use ($logger) {
    $logger->error(
        "UNHANDLED: {$exception->getMessage()} at " .
        "{$exception->getFile()}:{$exception->getLine()}"
    );
});
$errorMap = [
    E_ERROR => 'E_ERROR',
    E_WARNING => 'E_WARNING',
    E_PARSE => 'E_PARSE',
    E_NOTICE => 'E_NOTICE',
];
set_error_handler(function ($errorCode, $errorDescription, $errorFile, $errorLine) use ($logger, $errorMap) {
    $errorName = $errorMap[$errorCode] ?? $errorCode;
    $logger->error("ERROR $errorName occurred on $errorFile:$errorLine — $errorDescription");
});

$GLOBALS['logger'] = $logger;