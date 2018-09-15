<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Factory {

    private static $instance;

    /**
     *
     * @var \Sonata\GoogleAuthenticator\GoogleAuthenticator
     */
    private static $googleAuthenticator;

    /**
     *
     * @var DatabaseSettings 
     */
    private static $databaseSettings;

    /**
     *
     * @var Database 
     */
    private static $database;

    /**
     *
     * @var Encryption 
     */
    private static $encryption;

    /**
     *
     * @var string 
     */
    private static $sessionToken;

    /**
     *
     * @var string 
     */
    private static $sessionIpAddress;

    /**
     *
     * @var string 
     */
    private static $sessionTimestamp;

    /**
     *
     * @var string 
     */
    private static $sessionUID;
    private static $sessionUserAgent;
    private static $sessionAccessLevel;
    private static $sessionUsername;
    private static $sessionExpires;

    /**
     *
     * @var \System 
     */
    private static $system;

    /**
     *
     * @var \Options
     */
    private static $options;

    /**
     *
     * @var \Debug
     */
    private static $debug;

    public static function getInstance() {
        if (static::$instance == null || !isset(static::$instance)) {
            static::$instance = new Factory;
        }

        return static::$instance;
    }

    /**
     * 
     * @return \Sonata\GoogleAuthenticator\GoogleAuthenticator
     */
    public function getGoogleAuthenticator() {
        if (static::$googleAuthenticator == null || !isset(static::$googleAuthenticator)) {
            static::$googleAuthenticator = new Sonata\GoogleAuthenticator\GoogleAuthenticator();
        }

        return static::$googleAuthenticator;
    }

    public function getDebugger() {
        if (static::$debug == null || !isset(static::$debug)) {
            static::$debug = new Debug();
        }

        return static::$debug;
    }

    /**
     * 
     * @return string
     */
    public function getSessionUID() {
        if (static::$sessionUID == null || !isset(static::$sessionUID)) {
            static::$sessionUID = isset($_SESSION['UID']) ? $_SESSION['UID'] : null;
        }

        return static::$sessionUID;
    }

    /**
     * 
     * @return string
     */
    public function getSessionToken() {
        if (static::$sessionToken == null || !isset(static::$sessionToken)) {
            if (isset($_SESSION['TK']) && isset($_SESSION['UID'])) {
                $sessionToken = $_SESSION['TK'];
                static::$sessionToken = $sessionToken;
            }
        }

        return static::$sessionToken;
    }

    /**
     * 
     * @return string
     */
    public function getSessionIpaddress() {
        if (static::$sessionIpAddress == null || !isset(static::$sessionIpAddress)) {
            static::$sessionIpAddress = isset($_SESSION['IP']) ? $_SESSION['IP'] : null;
        }

        return static::$sessionIpAddress;
    }

    /**
     * 
     * @return string
     */
    public function getSessionTimestamp() {
        if (static::$sessionTimestamp == null || !isset(static::$sessionTimestamp)) {
            static::$sessionTimestamp = isset($_SESSION['TS']) ? $_SESSION['TS'] : null;
        }

        return static::$sessionTimestamp;
    }

    public function getSessionUsername() {
        if (static::$sessionUsername == null || !isset(static::$sessionUsername)) {
            static::$sessionUsername = isset($_SESSION['U']) ? $_SESSION['U'] : null;
        }

        return static::$sessionUsername;
    }

    public function getSessionUserAgent() {
        if (static::$sessionUserAgent == null || !isset(static::$sessionUserAgent)) {
            static::$sessionUserAgent = isset($_SESSION['UA']) ? $_SESSION['UA'] : null;
        }

        return static::$sessionUserAgent;
    }

    public function getSessionAccessLevel() {
        if (static::$sessionAccessLevel == null || !isset(static::$sessionAccessLevel)) {
            static::$sessionAccessLevel = isset($_SESSION['AL']) ? $_SESSION['AL'] : null;
        }

        return static::$sessionAccessLevel;
    }

    public function getSessionExpires() {
        if (static::$sessionExpires == null || !isset(static::$sessionExpires)) {
            static::$sessionExpires = isset($_SESSION['EXPIRES']) ? $_SESSION['EXPIRES'] : null;
        }

        return static::$sessionExpires;
    }

    /**
     * 
     * @return DatabaseSettings
     */
    public function getDatabaseSettings() {

        if (static::$databaseSettings == null || !isset(static::$databaseSettings)) {
            static::$databaseSettings = new DatabaseSettings();
        }

        return static::$databaseSettings;
    }

    /**
     * 
     * @return Database
     */
    public function getDatabase() {
        if (static::$database == null || !isset(static::$database)) {
            static::$database = new Database($this->getDatabaseSettings(), $this->getDebugger());
        }

        return static::$database;
    }

    /**
     * 
     * @return Encryption
     */
    public function getEncryption() {
        $sessionUID = isset($_SESSION['UID']) ? $_SESSION['UID'] : null;

        if (static::$encryption == null || !isset(static::$encryption)) {
            static::$encryption = new Encryption($this->getDatabase(), $sessionUID, $this->getDebugger());
        }

        return static::$encryption;
    }

    /**
     * 
     * @return \Dataset
     */
    public function createDataset() {
        return new Dataset($this->getDatabase(), $this->getEncryption(), $this->getDebugger());
    }

    /**
     * 
     * @param string $ID
     * @return Dataset
     */
    public function getDataset($ID, $user_id) {
        $id = filter_var($ID, FILTER_VALIDATE_INT);
        $userID = filter_var($user_id, FILTER_VALIDATE_INT);

        $dataset = $this->createDataset();
        $dataset->setID($id);
        $dataset->setUserID($userID);
        $dataset->load();


        return $dataset;
    }

    public function getProjects($user_id) {
        try {
            $userID = filter_var($user_id, FILTER_VALIDATE_INT);
            $projectArray = array();

            if (function_exists('apcu_store')) {
                if (!apcu_exists('projects')) {
                    $dbConnection = $this->getDatabase()->openConnection();
                    $statement = $dbConnection->prepare("SELECT DISTINCT project FROM dataset WHERE user_id = :userID");
                    $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                    if ($statement->execute()) {
                        while ($object = $statement->fetchObject()) {
                            $projectArray[] = $object->project;
                        }
                    }

                    apcu_store('projects', serialize($projectArray), 3600);

                    $this->getDatabase()->closeConnection($dbConnection);
                } else {
                    $projectArray = unserialize(apcu_fetch('projects'));
                }
            } else {
                $dbConnection = $this->getDatabase()->openConnection();
                $statement = $dbConnection->prepare("SELECT DISTINCT project FROM dataset WHERE user_id = :userID");
                $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                if ($statement->execute()) {
                    while ($object = $statement->fetchObject()) {
                        $projectArray[] = $object->project;
                    }
                }

                $this->getDatabase()->closeConnection($dbConnection);
            }

            return $projectArray;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getUserID($username) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $name = filter_var($username, FILTER_VALIDATE_EMAIL);
            $userID = null;

            $statement = $dbConnection->prepare("SELECT id FROM account WHERE username = :username");
            $statement->bindParam(':username', $name);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $userID = $object->id;
                }
            }

            return $userID;
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
            while ($object = $statement->fetch(PDO::FETCH_UNIQUE)) {
                $IDs[] = $object->id;
            }
        }

        return $IDs;
    }

    /**
     * 
     * @param string $user_id
     * @return array
     */
    public function countDatasets($user_id) {

        try {
            $amount = 0;

            $userID = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
            $apcuKey = 'datasetAmount_' . $userID;

            if (function_exists('apcu_store')) {
                if (!apcu_exists($apcuKey)) {
                    $dbConnection = $this->getDatabase()->openConnection();
                    $statement = $dbConnection->prepare("SELECT user_id  FROM dataset WHERE user_id = :userID");
                    $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                    if ($statement->execute()) {
                        $amount = $statement->rowCount();
                        apcu_store($apcuKey, $amount, 3600);
                    }
                } else {
                    $amount = apcu_fetch($apcuKey);
                }
            } else {
                $dbConnection = $this->getDatabase()->openConnection();
                $statement = $dbConnection->prepare("SELECT user_id  FROM dataset WHERE user_id = :userID");
                $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                if ($statement->execute()) {
                    $amount = $statement->rowCount();
                }
            }

            return $amount;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getDatasets($user_id) {
        try {
            $userID = filter_var($user_id, FILTER_VALIDATE_INT);
            $datasets = array();

            if (function_exists('apcu_store')) {
                if (!apcu_exists('datasets')) {
                    $dbConnection = $this->getDatabase()->openConnection();
                    $statement = $dbConnection->prepare("SELECT id,user_id FROM dataset WHERE user_id = :userID");
                    $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                    if ($statement->execute()) {
                        while ($object = $statement->fetchObject()) {
                            $dataset = $this->createDataset();

                            $dataset->setID($object->id);
                            $dataset->setUserID($object->user_id);
                            $dataset->load();

                            $this->getDebugger()->log('dataset: ' . serialize($dataset));
                            $datasets[] = $dataset;
                        }
                    }

                    apcu_store('datasets', serialize($datasets), 10);
                } else {
                    $datasets = unserialize(apcu_fetch('datasets'));
                }
            } else {
                $dbConnection = $this->getDatabase()->openConnection();
                $statement = $dbConnection->prepare("SELECT id,user_id FROM dataset WHERE user_id = :userID");
                $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                if ($statement->execute()) {
                    while ($object = $statement->fetchObject()) {
                        $dataset = $this->createDataset();

                        $dataset->setID($object->id);
                        $dataset->setUserID($object->user_id);
                        $dataset->load();

                        $this->getDebugger()->log('dataset: ' . serialize($dataset));
                        $datasets[] = $dataset;
                    }
                }
            }

            return $datasets;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param string $user_id
     * @param string $searchString
     * @return array
     */
    public function searchDatasets($user_id, $searchString) {
        try {
            $userID = filter_var($user_id, FILTER_VALIDATE_INT);
            $datasets = array();
            $searchTerm = "%" . filter_var($searchString, FILTER_SANITIZE_STRING) . "%";

            if (function_exists('apcu_store')) {
                $key = 'searchedDatasets' . $userID . md5($searchString);
                if (!apcu_exists($key)) {
                    $dbConnection = $this->getDatabase()->openConnection();

                    $statement = $dbConnection->prepare("SELECT id,user_id,title,date_created,date_edited,login,password,url,project FROM dataset WHERE user_id = :userID AND title LIKE :searchTerm OR project LIKE :searchTerm");
                    $statement->bindParam(':userID', $userID, PDO::PARAM_STR);
                    $statement->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);

                    if ($statement->execute()) {
                        while ($object = $statement->fetchObject()) {
                            $dataset = $this->createDataset();
                            $dataset->setID($object->id);
                            $dataset->setUserID($object->user_id);
                            $dataset->getEncryption()->setUserID($object->user_id);
                            $dataset->load();
                            $datasets[] = $dataset;
                        }
                    }

                    $this->getDatabase()->closeConnection($dbConnection);

                    apcu_store($key, serialize($datasets), 180);
                } else {
                    $datasets = unserialize(apcu_fetch($key));
                }
            } else {
                $dbConnection = $this->getDatabase()->openConnection();

                $statement = $dbConnection->prepare("SELECT id,user_id,title,date_created,date_edited,login,password,url,project FROM dataset WHERE user_id = :userID AND title LIKE :searchTerm OR project LIKE :searchTerm");
                $statement->bindParam(':userID', $userID, PDO::PARAM_STR);
                $statement->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);

                if ($statement->execute()) {
                    while ($object = $statement->fetchObject()) {
                        $dataset = $this->createDataset();
                        $dataset->setID($object->id);
                        $dataset->setUserID($object->user_id);
                        $dataset->getEncryption()->setUserID($object->user_id);
                        $dataset->load();
                        $datasets[] = $dataset;
                    }
                }

                $this->getDatabase()->closeConnection($dbConnection);
            }

            return $datasets;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @return \Session
     */
    public function getSession() {
        try {
            return new Session($this->getDatabase(), $this->getDebugger());
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @return \Account
     */
    public function getAccount() {
        try {
            return new Account($this->getDatabase(), $this->getDebugger());
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getSystem() {
        if (static::$system == null || !isset(static::$system)) {
            static::$system = new System($this->getDatabase(), $this->getEncryption(), $this->getDebugger(), $this->getAccount());
        }

        return static::$system;
    }

    public function getOptions() {
        if (static::$options == null || !isset(static::$options)) {
            static::$options = new Options($this->getDatabase(), $this->getDebugger());
        }

        return static::$options;
    }

    /**
     * 
     * @param string $page
     */
    public function redirect($page) {
        $host = $_SERVER['HTTP_HOST'];
        header("Location: https://{$host}/" . $page);
        die();
    }

    public function isSystemInstalled() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $installed = false;

            $statement = $dbConnection->prepare("SELECT installed FROM system");

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $installed = $object->installed == 1 ? true : false;
                }
            }

            return $installed;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function systemInstalled() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;

            $statement = $dbConnection->prepare("UPDATE system SET installed = 1");

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

}
