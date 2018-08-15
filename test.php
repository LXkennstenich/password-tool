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
/* @var $account Account */
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

$debugger = $factory->getDebugger();

if ($session->isAuthenticated() !== true) {
    $factory->redirect('login');
}

if ($session->needAuthenticator() !== false) {
    $factory->redirect('authenticator');
}

if ($account->needPasswordChange($sessionUID) === true) {
    $factory->redirect('updatepassword');
}



try {



    $result = shell_exec("git clone https://LXkennstenich@bitbucket.org/LXkennstenich/password-tool.git" . ' ' . ROOT_DIR);
    var_dump($result);
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}

