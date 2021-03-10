<?php


class DatabaseErrors
{
    private function __construct()
    {
    }

    /* todo: 23000 sqlstate is for constraint violation
        so you can't define, which error do you actually get here
        (duplicate entry, foreign key violation etc.)
    */

    /**
     * @var int error code for constraint violation
     */
    public const DUPLICATE_ENTRY = 23000;
}