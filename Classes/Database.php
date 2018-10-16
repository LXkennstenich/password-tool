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
    private function getDebugger(): \Debug {
        return $this->debugger;
    }

    /**
     * 
     * @param \Debug $debugger
     */
    private function setDebugger(\Debug $debugger) {
        $this->debugger = $debugger;
    }

    /**
     * Liefert den DNS-String für die Datenbankverbindung
     * @param string $databaseServer
     * @param string $databaseName
     * @param int $databasePort
     * @return string
     */
    private function generateDNS(string $databaseServer, string $databaseName, int $databasePort) {
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
    private function setDatabaseServer(string $databaseServer) {
        $this->databaseServer = $databaseServer;
    }

    /**
     * Setzt den Datenbank-Namen
     * @param string $databaseName
     */
    private function setDatabaseName(string $databaseName) {
        $this->databaseName = $databaseName;
    }

    /**
     * Setzt den Datenbank-Benutzer
     * @param string $databaseUser
     */
    private function setDatabaseUser(string $databaseUser) {
        $this->databaseUser = $databaseUser;
    }

    /**
     * Setzt das Datenbank-Passwort
     * @param string $databasePassword
     */
    private function setDatabasePassword(string $databasePassword) {
        $this->databasePassword = $databasePassword;
    }

    /**
     * Setzt den Datenbank-Port
     * @param int $databasePort
     */
    private function setDatabasePort(int $databasePort) {
        $this->databasePort = $databasePort;
    }

    /**
     * Admin Email
     * @param string $email
     */
    private function setAdminEmail(string $email) {
        $this->adminEmail = $email;
    }

    /**
     * Gibt den Datenbank-Server zurück
     * @return string
     */
    private function getDatabaseServer(): string {
        return $this->databaseServer;
    }

    /**
     * Gibt den Datenbank-Namen zurück
     * @return string
     */
    private function getDatabaseName(): string {
        return $this->databaseName;
    }

    /**
     * Gibt den Datenbank-Benutzer zurück
     * @return string
     */
    private function getDatabaseUser(): string {
        return $this->databaseUser;
    }

    /**
     * Gibt das Datenbank-Passwort zurück
     * @return string
     */
    private function getDatabasePassword(): string {
        return $this->databasePassword;
    }

    /**
     * Gibt den Datenbank-Port zurück
     * @return int
     */
    private function getDatabasePort(): int {
        return $this->databasePort;
    }

    /**
     * Admin Email
     * @return string
     */
    public function getAdminEmail(): string {
        return $this->adminEmail;
    }

    /**
     * Gibt eine neue Datenbankverbindung zurück
     * @return \PDO
     */
    public function openConnection(): \PDO {
        try {
            $dbConnection = new PDO($this->generateDNS($this->getDatabaseServer(), $this->getDatabaseName(), $this->getDatabasePort()), $this->getDatabaseUser(), $this->getDatabasePassword());
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    public function closeConnection(\PDO &$connection) {
        try {
            unset($connection);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getUserID(string $username) {
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

    private function createSystemEncryptionKey(): bool {
        $file = KEY_DIR . 'system.key';
        $key = base64_encode(random_bytes(SODIUM_CRYPTO_BOX_SECRETKEYBYTES));

        if (file_put_contents($file, $key)) {
            return true;
        }

        return false;
    }

    public function setup(): bool {
        try {
            if ($this->createTables() && $this->createSystemEncryptionKey()) {
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
                'dataset',
                'system',
                'options'
            );

            foreach ($tables as $table) {
                $sql = '';

                switch ($table) {
                    case 'account':
                        $sql .= "CREATE TABLE IF NOT EXISTS `account` (
                                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                `username` text NOT NULL,
                                `password` text NOT NULL,
                                `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `access_level` int(10) UNSIGNED NOT NULL,
                                `secret_key` text NOT NULL,
                                `encryption_key` varchar(256) NOT NULL,
                                `validation_token` text NOT NULL,
                                `active` tinyint(1) NOT NULL DEFAULT '0',
                                `first_login_password_changed` tinyint(1) NOT NULL DEFAULT '0',
                                `authenticator_is_setup` tinyint(1) NOT NULL DEFAULT '0',
                                `locked` tinyint(1) NOT NULL DEFAULT '0',
                                `login_attempts` int(11) NOT NULL DEFAULT '0',
                                `locktime` int(11) NOT NULL DEFAULT '0',
                                PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;";
                        break;
                    case 'dataset':
                        $sql .= "CREATE TABLE IF NOT EXISTS `dataset` (
                                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                `user_id` int(10) UNSIGNED NOT NULL,
                                `title` text NOT NULL,
                                `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `date_edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                `login` text NOT NULL,
                                `password` text NOT NULL,
                                `url` text NOT NULL,
                                `project` text NOT NULL,
                                PRIMARY KEY (`id`)
                              ) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;";
                        break;
                    case 'session':
                        $sql .= "CREATE TABLE IF NOT EXISTS `session` (
                                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                `user_id` int(10) UNSIGNED NOT NULL,
                                `session_id` text NOT NULL,
                                `session_token` text NOT NULL,
                                `session_timestamp` text NOT NULL,
                                `session_ipaddress` text NOT NULL,
                                `session_useragent` text NOT NULL,
                                `session_authenticator` tinyint(1) NOT NULL DEFAULT '0',
                                `session_accesslevel` int(10) UNSIGNED NOT NULL,
                                PRIMARY KEY (`id`)
                              ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;";
                        break;
                    case 'options':
                        $sql .= "CREATE TABLE IF NOT EXISTS `options` (
                                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                `user_id` int(10) UNSIGNED NOT NULL,
                                `display_login` tinyint(1) NOT NULL DEFAULT '1',
                                `use_two_factor` tinyint(1) NOT NULL DEFAULT '1',
                                `regenerate_session_id` tinyint(1) NOT NULL DEFAULT '1',
                                `email_notification_login` tinyint(1) NOT NULL DEFAULT '1',
                                `email_notification_password_change` tinyint(1) NOT NULL DEFAULT '1',
                                PRIMARY KEY (`id`),
                                UNIQUE KEY `user_id` (`user_id`)
                              ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
                        break;
                    case 'system':
                        $sql .= "CREATE TABLE IF NOT EXISTS `system` (
                                `id` int(10) UNSIGNED NOT NULL DEFAULT '1',
                                `cron_clear_session_data` tinyint(1) NOT NULL DEFAULT '1',
                                `cron_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                `cron_last_success` tinyint(1) NOT NULL,
                                `cron_active` tinyint(1) NOT NULL DEFAULT '0',
                                `cron_token` text NOT NULL,
                                `installed` tinyint(1) NOT NULL DEFAULT '0',
                                `doing_cron` tinyint(1) NOT NULL DEFAULT '0',
                                `blocked_ip_addresses` text NOT NULL,
                                PRIMARY KEY (`id`)
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

    private function generateCronToken() {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    public function insertDefaultValues(int $userID, string $table) {
        try {
            $dbConnection = $this->openConnection();
            $statement = new PDOStatement;
            $success = false;

            switch ($table) {
                case 'system':
                    $cronLastSuccess = 0;
                    $cronToken = $this->generateCronToken();
                    $statement = $dbConnection->prepare("INSERT INTO system (cron_last_success,cron_token) VALUES (:cronLastSuccess,:cronToken)");
                    $statement->bindParam(':cronLastSuccess', $cronLastSuccess, PDO::PARAM_INT);
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

    public function systemIsInstalled(): bool {
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

    public function getSystemInstalled(): bool {
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
