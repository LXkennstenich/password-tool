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


/* kein token ? ziemlich blöd ! */
if (!isset($_GET['CT'])) {
    die("No Authentication Token provided");
}

try {

    $debugger = $factory->getDebugger();
    $system = $factory->getSystem();
    $system->setID(1);
    $system->load();

    if ($system->isCronEnabled() != true) {
        exit("Bitte aktivieren Sie den Cron-Job in den Einstellungen");
    }

    $error = false;
    $savedToken = (string) $system->queryCronToken();
    $token = (string) filter_var($_GET['CT'], FILTER_SANITIZE_STRING);

    if ($savedToken === $token) {

        $system->doingCron();

        $IDs = $system->getUserIDs();

        foreach ($IDs as $ID) {

            $account = $factory->getAccount();

            $account->setID($ID);
            $account->load();

            $username = $account->getUsername();
            $session = $factory->getSession();
            $session->setUsername($username);

            //bullshit wieso in session -> gehört zu account :D
            $lockTime = $session->getLockTime($username);
            $locked = $session->isAccountLocked($username);

            if ($lockTime <= time() && $locked === true) {
                //wird auch von account übernommen
                if ($session->unlockAccount($username)) {
                    $debugger->cronlog('Account mit der ID ' . (string) $ID . ' wurde entsperrt ' . date('d-m-Y H:m:i'));
                }
            }

            if ($system->cronClearSessionData()) {
                $sessionData = $session->queryAjaxSessionData($ID);

                if (isset($sessionData['session_token']) && isset($sessionData['session_timestamp']) && isset($sessionData['session_ipaddress'])) {
                    $timestamp = $sessionData['session_timestamp'];
                    $timeNow = time();
                    $maxTimestamp = $timestamp + (60 * 60 * 2);

                    if ($timeNow > $maxTimestamp) {
                        if ($session->deleteSessionDataFromDatabase($ID)) {
                            $debugger->cronlog('Abgelaufene Session von Account ' . (string) $ID . ' wurde aus der Datenbank entfernt ' . date('d-m-Y H:m:i'));
                        }
                    }
                }
            }
        }

        $system->finishedCron();
    }
} catch (Exception $ex) {
    $debugger->cronlog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
}
