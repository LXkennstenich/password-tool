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
try {

    if (!defined('PASSTOOL')) {
        define('PASSTOOL', true);
    }

    if (session_status() == PHP_SESSION_NONE) {
        session_save_path('/tmp');
        session_start();
    }

    ob_start();

    $requestUri = htmlspecialchars(strip_tags(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))));
    $page = str_replace('/', '', $requestUri);

    $pageArray = scandir('Pages');

    include_once 'init.php';

    $file = $page . '.php';
    $isPage = false;

    if (in_array($file, $pageArray)) {
        $filePath = ROOT_DIR . 'Pages/' . $file;
        $isPage = true;
    } else {
        $filePath = ROOT_DIR . $file;
    }

    if (file_exists($filePath) && is_dir($filePath) === false) {
        header("HTTP/1.1 200 OK");
        if ($isPage) {
            include_once ELEMENTS_DIR . 'header.php';
            include_once ELEMENTS_DIR . 'authCheck.php';
            include_once ELEMENTS_DIR . 'JsGlobals.php';
            include_once ELEMENTS_DIR . 'mainTemplate.php';
        } else {
            include_once System::getSystemPage($page);
        }
    } else {
        header("HTTP/1.1 404 NOT FOUND");
        $factory->redirect('login');
    }

    $html = ob_get_clean();

    echo $html;
} catch (Exception $ex) {
    $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
}


