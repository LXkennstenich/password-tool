<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Database {

    /**
     * Datenbank-Server
     * @var string 
     */
    protected $databaseServer;

    /**
     * Datenbank-Name
     * @var string 
     */
    protected $databaseName;

    /**
     * Datenbank-Benutzer
     * @var string 
     */
    protected $databaseUser;

    /**
     * Datenbank-Passwort
     * @var string 
     */
    protected $databasePassword;

    /**
     * Datenbank-Port
     * @var int 
     */
    protected $databasePort;

    /**
     * Admin Email
     * @var string 
     */
    protected $adminEmail;

    /**
     *
     * @var \Debug 
     */
    protected $debugger;

    /**
     * 
     * @param DatabaseSettings $databaseSettings
     */
    function __construct($databaseSettings, $debugger) {
        $this->setDatabaseServer($databaseSettings->getServer());
        $this->setDatabaseName($databaseSettings->getName());
        $this->setDatabaseUser($databaseSettings->getUser());
        $this->setDatabasePassword($databaseSettings->getPassword());
        $this->setDatabasePort($databaseSettings->getPort());
        $this->setAdminEmail($databaseSettings->getAdminEmail());
        $this->setDebugger($debugger);
    }

    /**
     * 
     * @return \Debug
     */
    private function getDebugger() {
        return $this->debugger;
    }

    /**
     * 
     * @param \Debug $debugger
     */
    private function setDebugger($debugger) {
        $this->debugger = $debugger;
    }

    /**
     * Liefert den DNS-String für die Datenbankverbindung
     * @param string $databaseServer
     * @param string $databaseName
     * @param int $databasePort
     * @return string
     */
    private function generateDNS($databaseServer, $databaseName, $databasePort) {
        try {
            return $dns = 'mysql:' . 'host=' . $databaseServer . ';' . 'port=' . $databasePort . ';' . 'dbname=' . $databaseName;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * Setzt den Datenbank-Server
     * @param string $databaseServer
     */
    private function setDatabaseServer($databaseServer) {
        $this->databaseServer = $databaseServer;
    }

    /**
     * Setzt den Datenbank-Namen
     * @param string $databaseName
     */
    private function setDatabaseName($databaseName) {
        $this->databaseName = $databaseName;
    }

    /**
     * Setzt den Datenbank-Benutzer
     * @param string $databaseUser
     */
    private function setDatabaseUser($databaseUser) {
        $this->databaseUser = $databaseUser;
    }

    /**
     * Setzt das Datenbank-Passwort
     * @param string $databasePassword
     */
    private function setDatabasePassword($databasePassword) {
        $this->databasePassword = $databasePassword;
    }

    /**
     * Setzt den Datenbank-Port
     * @param int $databasePort
     */
    private function setDatabasePort($databasePort) {
        $this->databasePort = $databasePort;
    }

    /**
     * Admin Email
     * @param string $email
     */
    private function setAdminEmail($email) {
        $this->adminEmail = $email;
    }

    /**
     * Gibt den Datenbank-Server zurück
     * @return string
     */
    private function getDatabaseServer() {
        return $this->databaseServer;
    }

    /**
     * Gibt den Datenbank-Namen zurück
     * @return string
     */
    private function getDatabaseName() {
        return $this->databaseName;
    }

    /**
     * Gibt den Datenbank-Benutzer zurück
     * @return string
     */
    private function getDatabaseUser() {
        return $this->databaseUser;
    }

    /**
     * Gibt das Datenbank-Passwort zurück
     * @return string
     */
    private function getDatabasePassword() {
        return $this->databasePassword;
    }

    /**
     * Gibt den Datenbank-Port zurück
     * @return int
     */
    private function getDatabasePort() {
        return $this->databasePort;
    }

    /**
     * Admin Email
     * @return string
     */
    public function getAdminEmail() {
        return $this->adminEmail;
    }

    /**
     * Gibt eine neue Datenbankverbindung zurück
     * @return \PDO
     */
    public function openConnection() {
        try {
            $dbConnection = new PDO($this->generateDNS($this->getDatabaseServer(), $this->getDatabaseName(), $this->getDatabasePort()), $this->getDatabaseUser(), $this->getDatabasePassword());

            if (SYSTEM_MODE == 'DEV') {
                $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }

            return $dbConnection;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaseLog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * schliesst eine Datenbankverbindung
     * @param \PDO $connection
     */
    public function closeConnection(&$connection) {
        try {
            unset($connection);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getUserID($username) {
        try {
            $dbConnection = $this->openConnection();

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

    public function setup() {
        try {
            if ($this->createTables()) {
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

    private function createTables() {
        try {
            $dbConnection = $this->openConnection();

            $tables = array(
                'account',
                'session',
                'datasets',
                'system',
                'options'
            );

            foreach ($tables as $table) {
                $sql = '';

                switch ($table) {
                    case 'account':
                        $sql .= "DROP TABLE IF EXISTS `account`;CREATE TABLE IF NOT EXISTS `account` (
                            `id` int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            `username` text NOT NULL,
                            `password` text NOT NULL,
                            `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            `access_level` int(10) unsigned NOT NULL,
                            `secret_key` text NOT NULL,
                            `encryption_key` text NOT NULL,
                            `cypher_mode` varchar(256) NOT NULL DEFAULT 'AES-256-CBC',
                            `validation_token` text NOT NULL,
                            `active` tinyint(1) NOT NULL DEFAULT '0',
                            `first_login_password_changed` tinyint(1) NOT NULL DEFAULT '0',
                            `authenticator_is_setup` tinyint(1) NOT NULL DEFAULT '0'
                            ) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;";

                        break;
                    case 'datasets':
                        $sql .= "DROP TABLE IF EXISTS `datasets`;CREATE TABLE IF NOT EXISTS `datasets` (
                            `id` int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            `user_id` int(10) unsigned NOT NULL,
                            `title` text NOT NULL,
                            `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            `date_edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            `login` text NOT NULL,
                            `password` text NOT NULL,
                            `url` text NOT NULL,
                            `project` text NOT NULL
                            ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;";
                        break;
                    case 'session':
                        $sql .= "DROP TABLE IF EXISTS `session`;CREATE TABLE IF NOT EXISTS `session` (
                            `id` int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            `user_id` int(10) unsigned NOT NULL,
                            `session_id` text NOT NULL,
                            `session_token` text NOT NULL,
                            `session_timestamp` text NOT NULL,
                            `session_ipaddress` text NOT NULL,
                            `session_useragent` text NOT NULL,
                            `session_authenticator` tinyint(1) NOT NULL DEFAULT '0',
                            `session_accesslevel` int(10) unsigned NOT NULL
                            ) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;";
                        break;
                    case 'options':
                        $sql .= "DROP TABLE IF EXISTS `options`;CREATE TABLE IF NOT EXISTS `options` (
                            `id` int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            `user_id` int(10) unsigned NOT NULL UNIQUE KEY,
                            `display_login` tinyint(1) NOT NULL DEFAULT '1',
                            `use_two_factor` tinyint(1) NOT NULL DEFAULT '1',
                            `regenerate_session_id` tinyint(1) NOT NULL DEFAULT '1',
                            `email_notification_login` tinyint(1) NOT NULL DEFAULT '1',
                            `email_notification_password_change` tinyint(1) NOT NULL DEFAULT '1'
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                        break;
                    case 'system':
                        $sql .= "DROP TABLE IF EXISTS `system`;CREATE TABLE IF NOT EXISTS `system` (
                            `cron_recrypt` tinyint(1) NOT NULL DEFAULT '1',
                            `cron_clear_session_data` tinyint(1) NOT NULL DEFAULT '1',
                            `cron_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            `cron_last_success` tinyint(1) NOT NULL,
                            `cron_active` tinyint(1) NOT NULL DEFAULT '0',
                            `cron_url` text NOT NULL,
                            `cron_token` text NOT NULL,
                            `doing_cron` tinyint(1) NOT NULL DEFAULT '0',
                            `installed` tinyint(1) NOT NULL DEFAULT '0'
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                        break;
                }
                $dbConnection->exec($sql);
            }

            return true;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);

            return false;
        }
    }

    private function linkCheck($url) {
        $http = false;
        $https = false;

        if (strpos($url, 'http') === false) {
            $http = true;
        }

        if (strpos($url, 'https') === false) {
            $https = true;
        }

        if ($http && $https) {
            if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                $url = 'https://' . $url;
            } else {
                $url = 'http://' . $url;
            }
        }

        return $url;
    }

    private function generateCronToken() {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    public function insertDefaultValues($user_id, $tableName) {
        try {
            $dbConnection = $this->openConnection();
            $statement = new PDOStatement;
            $success = false;
            $table = filter_var($tableName, FILTER_SANITIZE_STRING);
            $userID = filter_var($user_id, FILTER_VALIDATE_INT);

            switch ($table) {
                case 'system':
                    $cronLastSuccess = 0;
                    $cronToken = $this->generateCronToken();
                    $cronUrl = $this->linkCheck($_SERVER['HTTP_HOST'] . '/cron?CT=' . $cronToken);
                    $statement = $dbConnection->prepare("INSERT INTO system (cron_last_success,cron_url,cron_token) VALUES (:cronLastSuccess,:cronUrl,:cronToken)");
                    $statement->bindParam(':cronLastSuccess', $cronLastSuccess, PDO::PARAM_INT);
                    $statement->bindParam(':cronUrl', $cronUrl, PDO::PARAM_STR);
                    $statement->bindParam(':cronToken', $cronToken, PDO::PARAM_STR);
                    break;
                case 'options':
                    $statement = $dbConnection->prepare("INSERT INTO options (user_id) VALUES (:userID)");
                    $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
                    break;
            }

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

            return false;
        }
    }

    public function systemIsInstalled() {
        try {
            $dbConnection = $this->openConnection();
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

    public function getSystemInstalled() {
        try {
            $dbConnection = $this->openConnection();
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

}
