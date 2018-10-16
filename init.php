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

include_once 'defines.php';

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
    exit('Kein SSL Zertifikat vorhanden! Diese Software setzt das vorhanden sein eines SSL-Zertifikats voraus, um höchstmögliche Sicherheit zu bieten.');
}



$pageArray = array();

if (!apcu_exists('pageArray')) {
    $pageArray = scandir('Pages');
    apcu_store('pageArray', serialize($pageArray), 3600);
} else {
    $pageArray = unserialize(apcu_fetch('pageArray'));
}

$file = $page . '.php';
$isPage = false;

if (in_array($file, $pageArray)) {
    $filePath = ROOT_DIR . 'Pages/' . $file;
    $isPage = true;
} else {
    $filePath = ROOT_DIR . $file;
}

$standardProject = '';



if (SYSTEM_MODE == 'DEV') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

spl_autoload_register(function($class) {
    include_once CLASS_DIR . $class . '.php';
});

$factory = Factory::getInstance();

$sessionUID = $factory->getSessionUID();
$sessionUsername = $factory->getSessionUsername();
$sessionIP = $factory->getSessionIpaddress() !== null ? $factory->getSessionIpaddress() : $_SERVER['REMOTE_ADDR'];
$sessionToken = $factory->getSessionToken();
$sessionTimestamp = $factory->getSessionTimestamp();
$sessionAccessLevel = $factory->getSessionAccessLevel();
$sessionExpires = $factory->getSessionExpires();
$sessionExpired = time() >= $sessionExpires && $sessionExpires != null ? true : false;

if ($page != 'logout' && $sessionExpired == true) {
    $factory->redirect('logout');
}

$searchTerm = isset($_POST['search']) ? filter_var($_POST['search'], FILTER_SANITIZE_STRING) : $standardProject;

$userAgent = $factory->getSessionUserAgent() !== null ? $factory->getSessionUserAgent() : $_SERVER['HTTP_USER_AGENT'];
$isSearch = $searchTerm != $standardProject ? true : false;


$session = $factory->getSession();
$session->setIpaddress($sessionIP);
$session->setHost($host);
$session->setUseragent($userAgent);
$session->setUsername($sessionUsername);
$session->setUserID($sessionUID);

$encryption = $factory->getEncryption();

if ($page != 'installation') {

    $account = $factory->getAccount();
    $account->setUsername($sessionUsername);

    $system = $factory->getSystem();
    $system->setID(1);
    $system->load();
    $options = $factory->getOptions();
    $options->setUserID($sessionUID);
    $options->load();
    $debugger = $factory->getDebugger();

    if ($session->isAuthenticated() !== false) {
        if ($userAgent != $_SERVER['HTTP_USER_AGENT'] || $sessionIP != $_SERVER['REMOTE_ADDR'] || $_SERVER['REMOTE_ADDR'] == '' || $_SERVER['HTTP_USER_AGENT'] == '') {
            $factory->redirect('logout');
        }
    }


    include_once ELEMENTS_DIR . 'authCheck.php';

    if ($session->cookiesSet() !== true && $session->isAuthenticated() !== true && $session->isValid()) {
        $cookieTimestamp = $sessionTimestamp + (60 * 60 * 2);
        $cookieTimestampEncrypted = $encryption->systemEncrypt($cookieTimestamp);
        $cookieToken = $encryption->systemEncrypt($sessionToken);
        $sessionID = $_SESSION['PHPSESSID'];

        setcookie('PHPSESSID', $sessionID, $cookieTimestamp, '/', $host, true, true);
        setcookie('TK', $cookieToken, $cookieTimestamp, '/', $host, true, true);
        setcookie('TS', $cookieTimestampEncrypted, $cookieTimestamp, '/', $host, true, true);
        $factory->redirect('login');
    }
}



