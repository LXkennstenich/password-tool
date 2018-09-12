<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ------------------------------------------------- Verfügbare Objekte / Variablen ------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */

/* @var $factory Factory */
/* @var $session Session */
/* @var $system System */
/* @var $account Account */
/* @var $encryption Encryption */
/* @var $options Options */
/* @var $sessionUID string */
/* @var $sessionUsername string */
/* @var $sessionIP string */
/* @var $sessionToken string */
/* @var $sessionTimestamp string */
/* @var $sessionAccessLevel string */
/* @var $searchTerm string */
/* @var $isSearch string */
/* @var $host string */
/* @var $userAgent string */

/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
if (!defined('PASSTOOL')) {
    die();
}

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
    exit('Kein SSL Zertifikat vorhanden! Diese Software setzt das vorhanden sein eines SSL-Zertifikats voraus, um höchstmögliche Sicherheit zu bieten.');
}

$standardProject = '';

$sessionUID = isset($_SESSION['UID']) ? filter_var($_SESSION['UID'], FILTER_VALIDATE_INT) : null;
$sessionUsername = isset($_SESSION['U']) ? filter_var($_SESSION['U'], FILTER_VALIDATE_EMAIL) : null;
$sessionIP = filter_var($_SESSION['IP'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false ? filter_var($_SESSION['IP'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) : filter_var($_SESSION['IP'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
$sessionToken = isset($_SESSION['TK']) ? filter_var($_SESSION['TK'], FILTER_SANITIZE_STRING) : null;
$sessionTimestamp = isset($_SESSION['TS']) ? filter_var($_SESSION['TS'], FILTER_VALIDATE_INT) : null;
$sessionAccessLevel = isset($_SESSION['AL']) ? filter_var($_SESSION['AL'], FILTER_VALIDATE_INT) : 0;
$searchTerm = isset($_POST['search']) ? filter_var($_POST['search'], FILTER_SANITIZE_STRING) : $standardProject;

$isSearch = $searchTerm != $standardProject ? true : false;
$host = isset($_SERVER['SERVER_NAME']) ? filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL) : filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

include_once 'defines.php';

if (SYSTEM_MODE == 'DEV') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

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
$system->setID(1);
$system->load();
$options = $factory->getOptions();
$options->setUserID($sessionUID);
$options->load();
$debugger = $factory->getDebugger();




