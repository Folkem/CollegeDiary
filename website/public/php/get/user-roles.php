<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

$availableUserRoles = UserRoles::getValues();

echo json_encode($availableUserRoles);