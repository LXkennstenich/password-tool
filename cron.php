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

try {
    $cronToken = isset($_GET['CT']) ? filter_var($_GET['CT'], FILTER_SANITIZE_STRING) : false;

    if ($cronToken !== false) {
        $error = false;
        $system = $factory->getSystem();
        $system->load();
        $savedToken = $system->queryCronToken();
        $hash = password_hash($savedToken, PASSWORD_DEFAULT, ["cost" => 12]);

        if ($savedToken != null && $cronToken != null) {
            if (password_verify($cronToken, $hash)) {

                $system->doingCron();

                $IDs = $system->getUserIDs();

                $datasetError = false;

                foreach ($IDs as $ID) {
                    $datasets = $factory->getDatasets($ID);

                    foreach ($datasets as $dataset) {
                        $i = 0;
                        $dataset->load();
                        $dataset->decrypt();

                        if ($i === 0) {
                            $system->updateEncryptionKey($ID, $savedToken);
                        }

                        $dataset->encrypt();

                        if ($dataset->update()) {
                            $datasetError = $datasetError !== true ? false : true;
                        } else {
                            $datasetError = true;
                        }

                        $i++;
                    }
                }
            }
        }

        $system->finishedCron();
    }
} catch (Exception $ex) {
    if (SYSTEM_MODE == 'DEV') {
        $this->getDebugger()->printError($ex->getMessage());
    }

    $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
}
