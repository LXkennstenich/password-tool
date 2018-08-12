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
/* @var $system System */
/* @var $debugger Debug */
/* @var $encryption Encryption */
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

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    die();
}

if (!isset($_GET['CT'])) {
    die("No Authentication Token provided");
}

try {

    $debugger = $factory->getDebugger();
    $system = $factory->getSystem();
    $system->load();

    if ($system->isCronEnabled() != true) {
        die();
    }

    $error = false;
    $savedToken = (string) $system->queryCronToken();
    $token = (string) filter_var($_GET['CT'], FILTER_SANITIZE_STRING);

    if ($savedToken === $token) {

        $system->doingCron();

        $IDs = $system->getUserIDs();

        $datasetError = false;

        foreach ($IDs as $ID) {

            if ($system->cronRecrypt()) {
                $datasets = $factory->getDatasets($ID);

                $encryptionKeyUpdated = false;
                $datasetsDecrypted = array();

                foreach ($datasets as $dataset) {
                    $dataset->decrypt();
                    $datasetsDecrypted[] = $dataset;
                }

                unset($datasets);

                if ($system->updateEncryptionKey($ID, $savedToken)) {
                    $encryptionKeyUpdated = true;
                }

                if ($encryptionKeyUpdated === true) {

                    foreach ($datasetsDecrypted as $dataset) {

                        $dataset->encrypt();

                        if ($dataset->update()) {
                            $datasetError = $datasetError !== true ? false : true;
                            $debugger->cronlog('dataset geupdated ');
                        } else {
                            $datasetError = true;
                            $debugger->cronlog('dataseterror id: ' . $dataset->getID());
                        }
                    }
                }

                unset($datasetsDecrypted);
            }



            $account = $factory->getAccount();

            $account->setID($ID);
            $account->load();

            $username = $account->getUsername();
            $session = $factory->getSession();
            $session->setUsername($username);

            $lockTime = $session->getLockTime($username);
            $locked = $session->isAccountLocked($username);

            if ($lockTime <= time() && $locked === true) {
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
