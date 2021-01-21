<?php

function loadClass($className) {
    require "../classes/$className.php";
}

spl_autoload_register('loadClass');
?>