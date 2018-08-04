<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */
$debugger = $factory->getDebugger();



try {

    if (true) {

        $error = false;
        $system = $factory->getSystem();
        $system->load();
        $savedToken = $system->queryCronToken();

        $system->doingCron();

        $IDs = $system->getUserIDs();

        $datasetError = false;

        foreach ($IDs as $ID) {
            $datasets = $factory->getDatasets($ID);

            $encryptionKeyUpdated = false;

            foreach ($datasets as $dataset) {

                $i = 0;

                if ($i === 0 || $encryptionKeyUpdated === true) {
                    $dataset->decrypt();


                    if ($i === 0) {
                        if ($system->updateEncryptionKey($ID, $savedToken)) {
                            $encryptionKeyUpdated = true;
                        }
                    }

                    if ($encryptionKeyUpdated === true) {
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

                $i++;
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

            if ($datasetError === true) {
                $debugger->cronlog('dataseterror');
            }
        }

        $system->finishedCron();
    }
} catch (Exception $ex) {
    $debugger->cronlog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
}
