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
    protected $blockedIpAddresses;
    private static $bitbucketUsername = "LXkennstenich";
    private static $bitbucketRepoSlug = "password-tool";
    private static $bitbucketAPI_BaseURL = "https://api.bitbucket.org/2.0";
    private static $bitbucket_BaseURL = "https://bitbucket.org/";

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
        $this->cron_clear_session_data = filter_var($cronClearSessionData, FILTER_VALIDATE_INT);
    }

    public function setCronLast($cronLast) {
        $this->cron_last = $cronLast;
    }

    public function setLastSuccess($conLastSuccess) {
        $this->cron_last_success = filter_var($conLastSuccess, FILTER_VALIDATE_INT);
    }

    public function setCronActive($cronActive) {
        $this->cron_active = filter_var($cronActive, FILTER_VALIDATE_INT);
    }

    public function setCronUrl($cronURL) {
        $this->cron_url = $cronURL;
    }

    public function setCronRecrypt($cronReCrypt) {
        $this->cron_recrypt = filter_var($cronReCrypt, FILTER_VALIDATE_INT);
    }

    public function setCronToken($cronToken) {
        $this->cron_token = $cronToken;
    }

    public function setInstalled($installed) {
        $this->installed = $installed;
    }

    public function setBlockedIpAddresses($ipAddresses) {
        $this->blockedIpAddresses = $ipAddresses;
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

    public function getBlockedIpAddresses() {
        return $this->blockedIpAddresses;
    }

    public function updateBlockedIpAddresses($ipAddresses) {
        try {
            $dbConnetion = $this->getDatabase()->openConnection();

            $ipAddressesSerialized = is_array($ipAddresses) ? serialize($ipAddresses) : serialize(array());
            $statement = $dbConnetion->prepare("UPDATE system SET blocked_ip_addresses = :ipAddresses");
            $statement->bindParam(':ipAddresses', $ipAddressesSerialized);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    return true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return false;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function queryBlockedIpAddresses() {
        try {
            $dbConnetion = $this->getDatabase()->openConnection();

            $statement = $dbConnetion->prepare("SELECT blocked_ip_addresses FROM system");

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $this->setBlockedIpAddresses(unserialize($object->blocked_ip_addresses));
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function load() {
        try {
            $dbConnetion = $this->getDatabase()->openConnection();

            $statement = $dbConnetion->prepare("SELECT cron_recrypt,cron_clear_session_data,cron_last,cron_last_success,cron_active,cron_url,blocked_ip_addresses FROM system");

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
                    $this->setBlockedIpAddresses(unserialize($object->blocked_ip_addresses));
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);
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

            $this->getDatabase()->closeConnection($dbConnection);

            return $exists;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getUserIDs() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $statement = $dbConnection->prepare("SELECT DISTINCT id FROM account");
            $IDs = array();

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $IDs[] = $object->id;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $IDs;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function queryCronToken() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $statement = $dbConnection->prepare("SELECT cron_token FROM system");
            $token = null;

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $token = $object->cron_token;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $token;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    private function getEncryptionKey($userID) {
        try {
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

            $this->getDatabase()->closeConnection($dbConnection);

            return $key;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function updateEncryptionKey($id, $cronToken) {
        try {
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

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function isCronEnabled() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $enabled = false;
            $statement = $dbConnection->prepare("SELECT cron_active FROM system");

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $enabled = $object->cron_active;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return boolval($enabled);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function update($cronActive, $clearSessionData, $cronRecrypt) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $success = false;
            $active = filter_var($cronActive, FILTER_VALIDATE_INT);
            $clear = filter_var($clearSessionData, FILTER_VALIDATE_INT);
            $recrypt = filter_var($cronRecrypt, FILTER_VALIDATE_INT);
            $statement = $dbConnection->prepare("UPDATE system SET cron_active = :cronActive, cron_clear_session_data = :clearSessionData, cron_recrypt = :cronRecrypt");
            $statement->bindParam(':cronActive', $active, PDO::PARAM_INT);
            $statement->bindParam(':clearSessionData', $clear, PDO::PARAM_INT);
            $statement->bindParam(':cronRecrypt', $recrypt, PDO::PARAM_INT);

            if ($statement->execute()) {
                if ($statement->rowCount > 0) {
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function cronUrl() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $cronUrl = '';
            $statement = $dbConnection->prepare("SELECT cron_url FROM system");

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $cronUrl = $object->cron_url;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $cronUrl;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function cronLastSuccess() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $lastSuccess = false;
            $statement = $dbConnection->prepare("SELECT cron_last_success FROM system");

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $lastSuccess = $object->cron_last_success;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return boolval($lastSuccess);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function cronRecrypt() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $recrypt = false;
            $statement = $dbConnection->prepare("SELECT cron_recrypt FROM system");

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $recrypt = $object->cron_recrypt;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return boolval($recrypt);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function cronClearSessionData() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $clearSessionData = false;
            $statement = $dbConnection->prepare("SELECT cron_clear_session_data FROM system");

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $clearSessionData = $object->cron_clear_session_data;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return boolval($clearSessionData);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function doingCron() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $statement = $dbConnection->prepare("UPDATE system SET doing_cron = 1");
            $success = false;

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function cronLast() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $statement = $dbConnection->prepare("SELECT cron_last FROM system");
            $cronLast = null;

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $cronLast = $object->cron_last;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $cronLast;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function finishedCron() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $cronLast = date('Y-m-d H:i:s');
            $statement = $dbConnection->prepare("UPDATE system SET doing_cron = 0 , cron_last_success = 1, cron_last = :cronLast");
            $statement->bindParam(':cronLast', $cronLast, PDO::PARAM_STR);
            $success = false;

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function isDoingCron() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $statement = $dbConnection->prepare("SELECT doing_cron FROM system");
            $doingCron = false;

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $doingCron = $object->doing_cron == 1 ? true : false;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $doingCron;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function updateAvailable() {
        try {
            set_time_limit(0);

            $updateAvailable = false;
            $url = static::$bitbucketAPI_BaseURL . '/repositories/' . static::$bitbucketUsername . '/' . static::$bitbucketRepoSlug . '/downloads';
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);

            $resultObject = null;

            if ($result) {
                $resultObject = json_decode($result);

                $filePath = UPDATE_DIR . 'version.dat';

                $file = fopen($filePath, 'w+');

                $downloadName = $resultObject->values[0]->name;

                $downloadUrl = null;

                if ($downloadName == 'version.dat') {
                    $downloadUrl = $resultObject->values[0]->links->self->href;
                }

                if ($downloadUrl !== null) {
                    $curlDownload = curl_init($downloadUrl);


                    curl_setopt_array($curlDownload, [
                        CURLOPT_URL => $downloadUrl,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_FILE => $file,
                        CURLOPT_TIMEOUT => 300,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
                    ]);
                }

                $response = curl_exec($curlDownload);

                fclose($file);

                if ($response !== false) {

                    $filePathCurrent = ROOT_DIR . 'version.dat';

                    $serverVersion = "";
                    $currentVersion = "";

                    $currentVersionArray = array();
                    $versionServerArray = array();

                    if ($versionCurrent !== false && $versionServer !== false) {

                        $serverVersion = file_get_contents($filePath);
                        $serverVersion = str_replace("\n", "|", $serverVersion);

                        $index = strlen($serverVersion) - 1;

                        if (strpos($serverVersion, "|", $index) !== false) {
                            $serverVersion = substr($serverVersion, 0, $index);
                        }

                        $serverVersion = preg_replace('/\s+/', '', $serverVersion);

                        $versionServerArray = explode("|", $serverVersion);

                        $currentVersion = file_get_contents($filePathCurrent);
                        $currentVersion = str_replace("\n", "|", $currentVersion);

                        $index = strlen($currentVersion) - 1;

                        if (strpos($currentVersion, "|", $index) !== false) {
                            $currentVersion = substr($currentVersion, 0, $index);
                        }

                        $currentVersion = preg_replace('/\s+/', '', $currentVersion);

                        $currentVersionArray = explode("|", $currentVersion);

                        array_filter($versionServerArray);
                        array_filter($currentVersionArray);
                        array_unique($versionServerArray);
                        array_unique($currentVersionArray);

                        if ($currentVersionArray !== false && is_array($currentVersionArray)) {
                            $maxIndex = sizeof($currentVersionArray) - 1;
                            $currentVersion = $currentVersionArray[$maxIndex];
                            $this->getDebugger()->log("Current Version: " . $currentVersion);
                        }

                        if ($versionServerArray !== false && is_array($versionServerArray)) {
                            $maxIndex = sizeof($versionServerArray) - 1;
                            $serverVersion = $versionServerArray[$maxIndex];
                            $this->getDebugger()->log("Server Version: " . $serverVersion);
                        }

                        if ($currentVersion != $serverVersion && $currentVersion != null && $serverVersion != null) {
                            $this->getDebugger()->log("Update verfügbar " . $serverVersion);
                            curl_close($curlDownload);
                            curl_close($curl);

                            return $serverVersion;
                        }
                    }
                }
            }

            curl_close($curlDownload);
            curl_close($curl);

            return $updateAvailable;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function downloadUpdate($serverVersion) {
        try {

            $this->getDebugger()->log("server-version: " . $serverVersion);

            $commitArray = explode('-', $serverVersion);
            $commit = $commitArray[1];
            $this->getDebugger()->log("commit-hash: " . $commit);

            $downloaded = false;
            $filename = static::$bitbucketUsername . '-' . static::$bitbucketRepoSlug . '-' . $commit . '.zip';
            $url = static::$bitbucketAPI_BaseURL . '/repositories/' . static::$bitbucketUsername . '/' . static::$bitbucketRepoSlug . '/src';
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);

            var_dump($result);

            return $downloaded;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function installUpdate($path) {
        try {
            $zip = new ZipArchive;
            $res = $zip->open($path);
            if ($res === true) {
                $this->getDebugger()->log("Zip Archiv " . $path . ' geöffnet');
                $this->getDebugger()->log("Entpacke nach: " . dirname(dirname(dirname(__FILE__))));
                if ($zip->extractTo(dirname(__FILE__))) {
                    $this->getDebugger()->log("Archiv entpackt");
                    if ($zip->close()) {
                        $this->getDebugger()->log("Zip Archiv geschlossen");
                        unlink($path);
                        return true;
                    }
                }
            }

            return false;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

}
