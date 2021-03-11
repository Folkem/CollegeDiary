<?php

session_start();

$currentUser = null;

if (isset($_SESSION['user'])) {
    $currentUser = $_SESSION['user'];
}

$GLOBALS['currentUser'] = $currentUser;