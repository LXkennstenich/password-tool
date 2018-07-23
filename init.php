<?php

/**
 * init.php
 * Initialisiert Standard-Objekte und included die defines.php
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
if (!defined('PASSTOOL')) {
    die();
}



$host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);


include_once 'defines.php';

spl_autoload_register(function($class) {
    include_once CLASS_DIR . $class . '.php';
});

$factory = new Factory;

$session = $factory->getSession();
$ipAddress = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
$session->setIpaddress($ipAddress);
$session->setHost($host);
$session->setUseragent($userAgent);
$session->setUsername(isset($_SESSION['U']) ? $_SESSION['U'] : null);
ob_start();


