<?php

/* @var $factory Factory */
/* @var $session Session */
if (!defined('PASSTOOL')) {
    die();
}

if (!$session->isAuthenticated()) {
    $factory->redirect('login');
}

$domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
$session->deleteSessionData($_SESSION['UID']);


session_start();
$_SESSION = array();
$_SESSION['OBSOLETE'] = true;
$_SESSION['EXPIRES'] = time() + 10;

setcookie('PHPSESSID', '', time() - 80000, '/', $domain, true, true);
setcookie('TK', '', time() - 80000, '/', $domain, true, true);
setcookie('TS', '', time() - 80000, '/', $domain, true, true);

session_destroy();

if (!$session->isAuthenticated()) {
    $factory->redirect('login');
}

die();


