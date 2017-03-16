<?php 
session_start();

function __autoload($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require_once('./' . $path . '.php');
}

use controller\basic;

$basic = new basic();
echo $basic->index();