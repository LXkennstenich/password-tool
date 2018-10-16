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

$username = $request->username;
$oldPassword = $request->oldPassword;
$newPassword = $request->newPassword;
$host = $request->host;


$options = $factory->getOptions();
$options->setUserID($userID);
$options->load();
$system = $factory->getSystem();
$system->setID(1);
$system->load();


if ($account->updatePassword($userID, $username, $oldPassword, $newPassword)) {

    if ($options->getEmailNotificationPasswordChange() != false) {
        $system->sendMail("Passwort wurde aktualisiert", "Passwortänderung Passwort-Tool", $username, $host);
    }

    echo "1";
} else {
    echo "0";
}

