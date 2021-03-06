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

$debugger = $factory->getDebugger();

try {
    $honeypot = $request->honeypot;

    if (!empty($honeypot) || $honeypot != '') {
        exit('Benutzername nicht vorhanden');
    }

    $username = $request->username;

    $account = $factory->getAccount();

    $account->setUsername($username);

    if ($account->exists()) {
        if ($account->requestNewPassword()) {
            echo 'Es wurde ein neues Passwort an die hinterlegte E-Mail Adresse gesendet';
        }
    } else {
        echo 'Der angegebene Benutzername existiert nicht';
    }
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}


