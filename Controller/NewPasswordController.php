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
/* @var $account Account */
/* @var $g \Sonata\GoogleAuthenticator\GoogleAuthenticator */
if (!defined('PASSTOOL')) {
    die();
}

$honeypot = $request->honeypot;

if (!empty($honeypot) || $honeypot != '') {
    exit('Benutzername nicht vorhanden');
}

$username = $request->username;

$account = $factory->getAccount();

$account->setUsername($username);

if ($account->exists()) {
    if ($account->requestNewPassword()) {
        echo '1';
    } else {
        echo 'fehlgeschlagen';
    }
}
