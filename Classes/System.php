<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class System extends Item {

    private static $bitbucketUsername = "LXkennstenich";
    private static $bitbucketRepoSlug = "password-tool";
    private static $bitbucketAPI_BaseURL = "https://api.bitbucket.org/2.0";
    private static $bitbucket_BaseURL = "https://bitbucket.org/";

    public function __construct($database, $encryption, $debugger, $account) {
        parent::__construct(strtolower(__CLASS__));
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

    private function setEncryption($encryption) {
        $this->encryption = $encryption;
    }

    public function setID($id) {
        $this->data['id'] = $id;
    }

    public function setCronClearSessionData($cronClearSessionData) {
        $this->data['cron_clear_session_data'] = $cronClearSessionData;
    }

    public function setCronLast($cronLast) {
        $this->data['cron_last'] = $cronLast;
    }

    public function setLastSuccess($conLastSuccess) {
        $this->data['cron_last_success'] = $conLastSuccess;
    }

    public function setCronActive($cronActive) {
        $this->data['cron_active'] = $cronActive;
    }

    public function setCronToken($cronToken) {
        $this->data['cron_token'] = $cronToken;
    }

    public function setInstalled($installed) {
        $this->data['installed'] = $installed;
    }

    public function setBlockedIpAddresses($ipAddresses) {
        $this->data['blocked_ip_addresses'] = $ipAddresses;
    }

    /**
     * 
     * @return \Encryption
     */
    private function getEncryption() {
        return $this->encryption;
    }

    public function getID() {
        return $this->data['id'];
    }

    public function getCronClearSessionData() {
        $this->data['cron_clear_session_data'];
    }

    public function getCronLast() {
        $this->data['cron_last'];
    }

    public function getLastSuccess() {
        $this->data['cron_last_success'];
    }

    public function getCronActive() {
        $this->data['cron_active'];
    }

    public function getCronRecrypt() {
        $this->data['cron_recrypt'];
    }

    public function getCronToken() {
        return $this->data['cron_token'];
    }

    public function isEnabled() {
        return $this->data['cron_active'] == true ? true : false;
    }

    public function getInstalled() {
        return $this->data['installed'] != null ? $this->installed : false;
    }

    public function getBlockedIpAddresses() {
        return $this->data['blocked_ip_addresses'];
    }

    public function updateBlockedIpAddresses($ipAddresses) {
        try {
            $dbConnetion = $this->getDatabase()->openConnection();
            $success = false;

            $ipAddressesSerialized = is_array($ipAddresses) ? serialize($ipAddresses) : serialize(array());
            $statement = $dbConnetion->prepare("UPDATE system SET blocked_ip_addresses = :ipAddresses");
            $statement->bindParam(':ipAddresses', $ipAddressesSerialized);

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

            if (!apcu_exists('updateAvailable')) {

                $url = static::$bitbucketAPI_BaseURL . '/repositories/' . static::$bitbucketUsername . '/' . static::$bitbucketRepoSlug . '/downloads';
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                $result = curl_exec($curl);

                $resultObject = json_decode($result);

                if ($result) {

                    $fileName = 'version.dat';
                    $filePath = UPDATE_DIR . $fileName;

                    $valueArray = $resultObject->values;

                    $downloadName = "";

                    $i = 0;
                    $index = null;

                    foreach ($valueArray as $value) {
                        if ($value->name == $fileName) {
                            $downloadName = $value->name;
                            $index = $i;
                        }

                        $i++;
                    }

                    $downloadUrl = $valueArray[$index]->links->self->href;

                    if ($downloadUrl !== null) {
                        if ($this->downloadFile($downloadUrl, $filePath) !== false) {
                            $filePathCurrent = ROOT_DIR . 'version.dat';

                            $serverVersion = "";
                            $currentVersion = "";


                            $versionServerArray = $this->getVersionArray($filePath);
                            $currentVersionArray = $this->getVersionArray($filePathCurrent);


                            if ($currentVersionArray !== false && is_array($currentVersionArray)) {
                                $maxIndex = sizeof($currentVersionArray) - 1;
                                $currentVersion = $currentVersionArray[$maxIndex];
                            }

                            if ($versionServerArray !== false && is_array($versionServerArray)) {
                                $maxIndex = sizeof($versionServerArray) - 1;
                                $serverVersion = $versionServerArray[$maxIndex];
                            }

                            if ($serverVersion == '' || $serverVersion == null || $serverVersion == false) {
                                return false;
                            }

                            if ($currentVersion != $serverVersion && $currentVersion != null && $serverVersion != null) {
                                $updateAvailable = $serverVersion;
                            }
                        }
                    }
                }

                curl_close($curl);
                apcu_store('updateAvailable', $updateAvailable, 1800);
            } else {
                $updateAvailable = apcu_fetch('updateAvailable');
            }

            return $updateAvailable;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getVersionArray($path) {
        $version = file_get_contents($path);
        $version = str_replace("\n", "|", $version);
        $versionArray = array();

        $index = strlen($version) - 1;

        if ($index < 0) {
            return $versionArray[] = file_get_contents($path);
        }

        if (strpos($version, "|", $index) !== false) {
            $version = substr($version, 0, $index);
        }

        $version = preg_replace('/\s+/', '', $version);

        $versionArray = explode("|", $version);

        array_filter($versionArray);
        array_unique($versionArray);
        unset($version);

        return $versionArray;
    }

    public function downloadUpdate($serverVersion) {
        try {

            $updateDownloaded = false;
            $url = static::$bitbucketAPI_BaseURL . '/repositories/' . static::$bitbucketUsername . '/' . static::$bitbucketRepoSlug . '/downloads';
            $curl = curl_init($url);
            $fileName = $serverVersion . '.zip';

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = json_decode(curl_exec($curl));

            $resultArray = $result->values;

            $i = 0;
            $index = null;

            foreach ($resultArray as $value) {

                if ($value->name == $fileName) {
                    $index = $i;
                }

                $i++;
            }

            $downloadUrl = $result->values[$index]->links->self->href;

            $filePath = UPDATE_DIR . $fileName;

            if ($this->downloadFile($downloadUrl, $filePath)) {
                $updateDownloaded = $filePath;
            }

            curl_close($curl);

            return $updateDownloaded;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function downloadFile($url, $filepath) {
        $curlDownload = curl_init($url);

        $file = fopen($filepath, "w+");

        curl_setopt_array($curlDownload, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FILE => $file,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
        ]);

        $result = curl_exec($curlDownload);

        curl_close($curlDownload);
        fclose($file);

        return $result !== false ? true : false;
    }

    public function unzip($src, $destination) {
        try {
            $unzipped = false;
            $zip = new ZipArchive;

            $result = $zip->open($src);

            if ($result === true) {
                $zip->extractTo($destination);
                $zip->close();
                $unzipped = true;
            }

            return $unzipped;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function installUpdate($path) {
        $destination = ROOT_DIR;

        if ($this->unzip($path, $destination) !== false) {
            if (unlink($path)) {
                return true;
            }
        }

        return false;
    }

    public function sendMail($message, $subject, $mailAddress, $host) {
        $hostAddress = 'https://' . $host;

        $subjectFiltered = filter_var($subject, FILTER_SANITIZE_STRING);
        $messageFiltered = filter_var($message, FILTER_SANITIZE_STRING);
        $address = filter_var($mailAddress, FILTER_VALIDATE_EMAIL);

        $header = 'From: no-reply@' . $host . "\r\n" .
                'X-Sender: ' . $hostAddress . "\r\n" .
                'X-Mailer: PHP/' . phpversion();



        if (mail($address, $subjectFiltered, $messageFiltered, $header) !== false) {
            $this->getDebugger()->log("Nachricht an " . $address . ' versendet');
            return true;
        }

        $this->getDebugger()->log("Versenden der Nachricht an " . $address . ' fehlgeschlagen');

        return false;
    }

    public static function getView($view) {
        try {
            $file = VIEW_DIR . $view . '.view.php';

            if (file_exists($file)) {
                return $file;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public static function getConfig($config) {
        try {
            $file = CONFIG_DIR . $config . '.php';

            if (file_exists($file)) {
                return $file;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public static function getController($controller) {
        try {
            $file = CONTROLLER_DIR . $controller . 'Controller.php';

            if (file_exists($file)) {
                return $file;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public static function getSystemPage($page) {
        try {
            $file = ROOT_DIR . $page . '.php';

            if (file_exists($file)) {
                return $file;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public static function getPage($page) {
        try {
            $file = PAGE_DIR . $page . '.php';

            if (file_exists($file)) {
                return $file;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

}
