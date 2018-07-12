<?php

if (!defined('PASSTOOL')) {
    die();
}

ini_set('display_errors', 1);

include_once 'defines.php';

spl_autoload_register(function($class) {
    include_once CLASS_DIR . $class . '.php';
});

$factory = new Factory;
$session = $factory->getSession();
$sessionStarted = $session->sessionStarted() == true ? true : false;



