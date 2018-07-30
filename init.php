<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */
/* @var $factory Factory */
/* @var $session Session */
/* @var $encryption Encryption */
/* @var $sessionUID int */
/* @var $sessionUsername string */
/* @var $sessionIP string */
/* @var $sessionToken string */
/* @var $sessionTimestamp int */
/* @var $searchTerm string */
/* @var $host string */
/* @var $userAgent string */
if (!defined('PASSTOOL')) {
    die();
}

$sessionUID = isset($_SESSION['UID']) ? filter_var($_SESSION['UID'], FILTER_VALIDATE_INT) : null;
$sessionUsername = isset($_SESSION['U']) ? filter_var($_SESSION['U'], FILTER_VALIDATE_EMAIL) : null;
$sessionIP = isset($_SESSION['IP']) ? filter_var($_SESSION['IP'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) : filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
$sessionToken = isset($_SESSION['TK']) ? filter_var($_SESSION['TK'], FILTER_SANITIZE_STRING) : null;
$sessionTimestamp = isset($_SESSION['TS']) ? filter_var($_SESSION['TS'], FILTER_VALIDATE_INT) : null;
$searchTerm = isset($_POST['search']) ? filter_var($_POST['search'], FILTER_SANITIZE_STRING) : '';

$isSearch = $searchTerm != '' ? true : false;
$host = isset($_SERVER['SERVER_NAME']) ? filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL) : filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

if (SYSTEM_MODE == 'DEV') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

include_once 'defines.php';

require_once ROOT_DIR . 'vendor/autoload.php';

spl_autoload_register(function($class) {
    include_once CLASS_DIR . $class . '.php';
});

$factory = new Factory;

$session = $factory->getSession();
$session->setIpaddress($sessionIP);
$session->setHost($host);
$session->setUseragent($userAgent);
$session->setUsername($sessionUsername);
$session->setUserID($sessionUID);
$account = $factory->getAccount();
$account->setUsername($sessionUsername);
$encryption = $factory->getEncryption();
$system = $factory->getSystem();
ob_start();


