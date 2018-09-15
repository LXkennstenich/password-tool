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

include_once 'defines.php';

if (SYSTEM_MODE == 'DEV') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

require_once ROOT_DIR . 'vendor/autoload.php';

spl_autoload_register(function($class) {
    include_once CLASS_DIR . $class . '.php';
});

$factory = Factory::getInstance();
$sessionUID = $factory->getSessionUID();
$sessionUsername = $factory->getSessionUsername();
$sessionIP = $factory->getSessionIpaddress();
$sessionToken = $factory->getSessionToken();
$sessionTimestamp = $factory->getSessionExpires();
$sessionAccessLevel = $factory->getSessionAccessLevel();
$sessionExpires = $factory->getSessionExpires();
$sessionExpired = time() >= $sessionExpires && $sessionExpires != null ? true : false;
$searchTerm = isset($_POST['search']) ? filter_var($_POST['search'], FILTER_SANITIZE_STRING) : $standardProject;

$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $factory->getSessionUserAgent();
$isSearch = $searchTerm != $standardProject ? true : false;
$host = isset($_SERVER['SERVER_NAME']) ? filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL) : filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);

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




