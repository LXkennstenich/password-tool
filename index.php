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
/* @var $system System */
/* @var $sessionUID int */
/* @var $sessionUsername string */
/* @var $sessionIP string */
/* @var $sessionToken string */
/* @var $sessionTimestamp int */
/* @var $searchTerm string */
/* @var $host string */
/* @var $userAgent string */
if (!defined('PASSTOOL')) {
    define('PASSTOOL', true);
}

if (session_status() == PHP_SESSION_NONE) {
    session_save_path('/tmp');
    session_start();
}

$requestUri = htmlspecialchars(strip_tags(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))));
$page = str_replace('/', '', $requestUri);

include 'init.php';

$file = ROOT_DIR . $page . '.php';

if ($page != 'Ajax' && $page != 'cron') {
    include_once ELEMENTS_DIR . 'header.php';
    include ELEMENTS_DIR . 'JsGlobals.php';

    if ($session->isAuthenticated() && $session->needAuthenticator() === false) {
        include_once ELEMENTS_DIR . 'navbar.php';
    }
}

if (file_exists($file)) {
    header("HTTP/1.1 200 OK");
    include $file;
} else {
    header("HTTP/1.1 404 Not Found");
    $factory->redirect('login');
}

if ($page != 'Ajax' && $page != 'cron') {

    if ($page == 'account') {
        include_once VIEW_DIR . 'newDataset.view.php';
    }

    if ($sessionAccessLevel === SESSION_ADMIN) {
        include_once VIEW_DIR . 'newUser.view.php';
    }

    include_once ELEMENTS_DIR . 'ajaxLoader.php';

    include ELEMENTS_DIR . 'footer.php';
}

$html = ob_get_clean();

echo $html;
