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
/* @var $g GoogleAuthenticator */

/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */

if (!defined('PASSTOOL')) {
    die();
}

try {
    $secret = $account->querySecretKey($userID);
    $g = $factory->getGoogleAuthenticator();

    $codeInput = $request->code;

    if ($g->verifyCode($secret, $codeInput, 100)) {
        if ($session->updateAuthenticator(1, $userID)) {
            echo "1";
        } else {
            echo "0";
        }
    } else {
        echo "0";
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}


