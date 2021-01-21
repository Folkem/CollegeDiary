<?php

function loadClass($className) {
    require $_SERVER['DOCUMENT_ROOT'] . "/classes/$className.php";
}

spl_autoload_register('loadClass');
?>