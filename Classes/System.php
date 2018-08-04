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

class System {

    protected $cron_recrypt;
    protected $cron_clear_session_data;
    protected $cron_last;
    protected $cron_last_success;
    protected $cron_active;
    protected $cron_url;
    protected $cron_token;
    protected $installed;
    protected $database;
    protected $debugger;
    protected $encryption;
    protected $account;

    public function __construct($database, $encryption, $debugger, $account) {
        $this->setDatabase($database);
        $this->setEncryption($encryption);
        $this->setDebugger($debugger);
        $this->setAccount($account);
    }

    private function setAccount($account) {
        $this->account = $account;
    }

    /**
     * 
     * @return \Account
     */
    private function getAccount() {
        return $this->account;
    }

    private function setDebugger($debugger) {
        $this->debugger = $debugger;
    }

    private function setDatabase($database) {
        $this->database = $database;
    }

    private function setEncryption($encryption) {
        $this->encryption = $encryption;
    }

    public function setCronClearSessionData($cronClearSessionData) {
        $value = filter_var(intval($cronClearSessionData, 10), FILTER_VALIDATE_INT);
        $this->cron_clear_session_data = $value === 1 ? true : false;
    }

    public function setCronLast($cronLast) {
        $this->cron_last = $cronLast;
    }

    public function setLastSuccess($conLastSuccess) {
        $value = filter_var(intval($conLastSuccess, 10), FILTER_VALIDATE_INT);
        $this->cron_last_success = $value === 1 ? true : false;
    }

    public function setCronActive($cronActive) {
        $value = filter_var(intval($cronActive, 10), FILTER_VALIDATE_INT);
        $this->cron_active = $value === 1 ? true : false;
    }

    public function setCronUrl($cronURL) {
        $this->cron_url = filter_var($cronURL, FILTER_VALIDATE_URL);
    }

    public function setCronRecrypt($cronReCrypt) {
        $value = filter_var(intval($cronReCrypt, 10), FILTER_VALIDATE_INT);
        $this->cron_recrypt = $value === 1 ? true : false;
    }

    public function setCronToken($cronToken) {
        $this->cron_token = $cronToken;
    }

    public function setInstalled($installed) {
        $value = filter_var(intval($installed, 10), FILTER_VALIDATE_INT);
        $this->installed = $value === 1 ? true : false;
    }

    /**
     * 
     * @return \Database
     */
    private function getDatabase() {
        return $this->database;
    }

    private function getDebugger() {
        return $this->debugger;
    }

    /**
     * 
     * @return \Encryption
     */
    private function getEncryption() {
        return $this->encryption;
    }

    public function getCronClearSessionData() {
        $this->cron_clear_session_data;
    }

    public function getCronLast() {
        $this->cron_last;
    }

    public function getLastSuccess() {
        $this->cron_last_success;
    }

    public function getCronActive() {
        $this->cron_active;
    }

    public function getCronUrl() {
        $this->cron_url;
    }

    public function getCronRecrypt() {
        $this->cron_recrypt;
    }

    public function getCronToken() {
        return $this->cron_token;
    }

    public function isEnabled() {
        return $this->cron_active == true ? true : false;
    }

    public function getInstalled() {
        return $this->installed != null ? $this->installed : false;
    }

    public function load() {
        try {
            $dbConnetion = $this->getDatabase()->openConnection();

            $statement = $dbConnetion->prepare("SELECT cron_recrypt,cron_clear_session_data,cron_last,cron_last_success,cron_active,cron_url FROM system");

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $this->setCronRecrypt($object->cron_recrypt);
                    $this->setCronClearSessionData($object->cron_clear_session_data);
                    $this->setCronLast($object->cron_last);
                    $this->setCronToken($object->cron_token);
                    $this->setLastSuccess($object->cron_last_success);
                    $this->setCronActive($object->cron_active);
                    $this->setCronUrl($object->cron_url);
                    $this->setInstalled($object->installed == 1 ? true : false);
                }

                return true;
            }

            return false;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function exist() {
        try {
            $dbConnetion = $this->getDatabase()->openConnection();
            $exists = false;

            $statement = $dbConnetion->prepare("SELECT cron_recrypt FROM system");

            if ($statement->execute()) {
                $exists = $statement->rowCount > 0 ? true : false;
            }

            return $exists;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getUserIDs() {
        $dbConnection = $this->getDatabase()->openConnection();
        $statement = $dbConnection->prepare("SELECT DISTINCT id FROM account");
        $IDs = array();

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $IDs[] = $object->id;
            }
        }

        return $IDs;
    }

    public function queryCronToken() {
        $dbConnection = $this->getDatabase()->openConnection();
        $statement = $dbConnection->prepare("SELECT cron_token FROM system");
        $token = null;

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $token = $object->cron_token;
            }
        }

        return $token;
    }

    private function getEncryptionKey($userID) {

        $dbConnection = $this->getDatabase()->openConnection();
        $key = null;
        $ID = filter_var($userID, FILTER_VALIDATE_INT);
        $statement = $dbConnection->prepare("SELECT encryption_key FROM account WHERE id = :ID");
        $statement->bindParam(':ID', $ID, PDO::PARAM_INT);

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $key = $object->encryption_key;
            }
        }

        return $key;
    }

    public function updateEncryptionKey($id, $cronToken) {
        $token = (string) filter_var($cronToken, FILTER_SANITIZE_STRING);
        $savedToken = (string) $this->queryCronToken();
        $success = false;
        if ($token === $savedToken) {

            $newKey = $this->getAccount()->generateEncryptionKey();
            $dbConnection = $this->getDatabase()->openConnection();
            $ID = filter_var($id, FILTER_VALIDATE_INT);

            $statement = $dbConnection->prepare("UPDATE account SET encryption_key = :encryptionKey WHERE id = :ID");
            $statement->bindParam(':encryptionKey', $newKey, PDO::PARAM_STR);
            $statement->bindParam(':ID', $ID, PDO::PARAM_INT);

            $oldKey = $this->getEncryptionKey($ID);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $newKey = $this->getEncryptionKey($ID);
                    if ($newKey != $oldKey) {
                        $success = true;
                    }
                }
            }
        }

        return $success;
    }

    public function doingCron() {
        $dbConnection = $this->getDatabase()->openConnection();
        $statement = $dbConnection->prepare("UPDATE system SET doing_cron = 1");
        $success = false;

        if ($statement->execute()) {
            if ($statement->rowCount() > 0) {
                $success = true;
            }
        }

        return $success;
    }

    public function finishedCron() {
        $dbConnection = $this->getDatabase()->openConnection();
        $statement = $dbConnection->prepare("UPDATE system SET doing_cron = 0");
        $success = false;

        if ($statement->execute()) {
            if ($statement->rowCount() > 0) {
                $success = true;
            }
        }

        return $success;
    }

    public function isDoingCron() {
        $dbConnection = $this->getDatabase()->openConnection();
        $statement = $dbConnection->prepare("SELECT doing_cron FROM system");
        $doingCron = false;

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $doingCron = $object->doing_cron == 1 ? true : false;
            }
        }

        return $doingCron;
    }

}
