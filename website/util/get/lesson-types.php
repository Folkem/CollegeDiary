<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';

$lessonTypes = LessonType::getValues();

echo json_encode($lessonTypes);