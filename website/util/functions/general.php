<?php

function getRandomString(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string
{
    if ($length < 1) {
        throw new RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        /** @noinspection PhpUnhandledExceptionInspection */
        $pieces[] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

function decomposeUserFullName($fullName): array
{
    $result = [];

    $parts = explode(" ", $fullName);
    if (count($parts) == 2) {
        $result[0] = $parts[0];
        $result[1] = null;
        $result[2] = $parts[1];
    } else {
        $result = [...$parts];
    }

    return $result;
}