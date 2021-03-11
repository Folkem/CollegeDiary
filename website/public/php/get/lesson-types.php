<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

$lessonTypes = LessonType::getValues();

echo json_encode($lessonTypes);