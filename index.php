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

    include_once 'init.php';

    $file = ROOT_DIR . $page . '.php';

    if (file_exists($file)) {
        header("HTTP/1.1 200 OK");

        if ($page != 'Ajax' && $page != 'cron') {
            include_once ELEMENTS_DIR . 'header.php';
            include_once ELEMENTS_DIR . 'authCheck.php';
            include_once ELEMENTS_DIR . 'JsGlobals.php';
            include_once ELEMENTS_DIR . 'mainTemplate.php';
        } else {
            include_once System::getPage($page);
        }
    } else {
        $factory->redirect('login');
    }


    $html = ob_get_clean();

    echo $html;
} catch (Exception $ex) {
    if (SYSTEM_MODE == 'DEV') {
        $this->getDebugger()->printError($ex->getMessage());
    }

    $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
}


