<?php

/**
 * Enthält die Datenbank-Settings. Liefert und schliesst die Datenbankverbindung
 *
 * @author alexw
 */
class Database {

    /**
     * Datenbank-Einstellungen
     * @var DatabaseSettings
     */
    protected $databaseSettings;

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

    function __construct() {
        $this->setDatabaseSettings(DatabaseSettings::getInstance());
        $this->setDatabaseServer($this->getDatabaseSettings()->getServer());
        $this->setDatabaseName($this->getDatabaseSettings()->getName());
        $this->setDatabaseUser($this->getDatabaseSettings()->getUser());
        $this->setDatabasePassword($this->getDatabaseSettings()->getPassword());
        $this->setDatabasePort($this->getDatabaseSettings()->getPort());
    }

    /**
     * Liefert den DNS-String für die Datenbankverbindung
     * @param string $databaseServer
     * @param string $databaseName
     * @param int $databasePort
     * @return string
     */
    private function generateDNS($databaseServer, $databaseName, $databasePort) {
        return $dns = 'mysql:' . 'host=' . $databaseServer . ';' . 'port=' . $databasePort . ';' . 'dbname=' . $databaseName;
    }

    /**
     * Setzt die Datenbank-Settings
     * @param DatabaseSettings $settings
     */
    private function setDatabaseSettings($settings) {
        $this->databaseSettings = $settings;
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
     * Gibt die Datenbank-Settings zurück
     * @return DatabaseSettings
     */
    private function getDatabaseSettings() {
        return $this->databaseSettings;
    }

    /**
     * Gibt eine neue Datenbankverbindung zurück
     * @return \PDO
     */
    public function openConnection() {
        return new PDO($this->generateDNS($this->getDatabaseServer(), $this->getDatabaseName(), $this->getDatabasePort()), $this->getDatabaseUser(), $this->getDatabasePassword());
    }

    /**
     * schliesst eine Datenbankverbindung
     * @param \PDO $connection
     */
    public function closeConnection(&$connection) {
        unset($connection);
    }

}
