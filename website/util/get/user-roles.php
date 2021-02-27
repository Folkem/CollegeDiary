<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';

$availableUserRoles = UserRoles::getValues();

echo json_encode($availableUserRoles);