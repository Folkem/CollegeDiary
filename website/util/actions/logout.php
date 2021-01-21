<?php

session_start();

if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    session_destroy();
}

header('Location: http://college-diary.edu/pages/news.php');