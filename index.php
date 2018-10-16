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
/* @var $debugger Debug */
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
    define('PASSTOOL', true);
}

$host = isset($_SERVER['SERVER_NAME']) ? filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL) : filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);

if (session_status() == PHP_SESSION_NONE) {
    session_save_path('/tmp');
    session_start();
}

ob_start();

$requestUri = htmlspecialchars(strip_tags(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))));
$page = str_replace('/', '', $requestUri);

include_once 'init.php';

if (file_exists($filePath) && is_dir($filePath) === false) {
    header("HTTP/1.1 200 OK");
    if ($isPage) {
        include_once ELEMENTS_DIR . 'mainTemplate.php';
    } else {
        include_once System::getSystemPage($page);
    }
} else {
    header("HTTP/1.1 404 NOT FOUND");
    $factory->redirect('login');
}


//$time = microtime(true) - $starttime;
//$debugger->log('Aufruf der Seite ' . $page . ' benötigte : ' . (string) $time);

$html = ob_get_clean();

echo $html;



