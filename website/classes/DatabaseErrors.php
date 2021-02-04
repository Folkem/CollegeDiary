<?php

class DatabaseErrors
{
    private function __construct()
    {
    }

    /**
     * @var int error code for duplicate entry constraint violation
     */
    public const DUPLICATE_ENTRY = 23000;
}