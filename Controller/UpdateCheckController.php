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

set_time_limit(0);
$debugger = $factory->getDebugger();

try {
    $system = $factory->getSystem();

    if (!apcu_exists('updateAvailable')) {
        $commitHash = $system->updateAvailable();
    } else {
        $commitHash = apcu_fetch('updateAvailable');
    }

    if ($commitHash !== false) {

        $path = $system->downloadUpdate($commitHash);

        if ($path !== false) {

            if ($system->installUpdate($path)) {
                echo 'Erfolgreich aktualisiert';
            } else {
                echo 'Installation schief gelaufen';
            }
        }
    } else {
        echo 'no update available';
    }

    apcu_clear_cache();
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}
