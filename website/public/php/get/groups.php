<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

$groups = GroupRepository::getGroups();
$groups = array_combine(
    array_map(
        fn($group) => $group->getId(),
        $groups
    ),
    array_map(
        fn($group) => $group->getReadableName(true),
        $groups
    )
);

echo json_encode($groups);