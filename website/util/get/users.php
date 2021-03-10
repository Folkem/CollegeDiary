<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

$roleId = (int)($_POST['roleId'] ?? -1);

$users = [];

if ($roleId === -1) {
    $users = UserRepository::getUsers();
} else {
    $users = UserRepository::getUsersWithRole($roleId);
}

$users = array_combine(
    array_map(
        fn($user) => $user->getId(),
        $users
    ),
    array_map(
        fn($user) => $user->getFullName(),
        $users
    )
);

echo json_encode($users);