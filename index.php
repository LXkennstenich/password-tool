<?php

/* @var $factory Factory */
/* @var $session Session */
if (!defined('PASSTOOL')) {
    define('PASSTOOL', true);
}

include_once 'init.php';

$requestUri = htmlspecialchars(strip_tags(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))));
$page = str_replace('/', '', $requestUri);

if ($page == '') {
    $factory->redirect('login');
}

$file = ROOT_DIR . $page . '.php';

include ELEMENTS_DIR . 'header.php';

if (file_exists($file)) {
    header("HTTP/1.1 200 OK");
    include $file;
} else {
    header("HTTP/1.1 404 Not Found");
    die();
}

include ELEMENTS_DIR . 'footer.php';

$html = ob_get_clean();

echo $html;
