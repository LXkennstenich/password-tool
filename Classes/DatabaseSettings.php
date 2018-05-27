<?php

/**
 * Liest die Datenbank-Settings aus der Config.php
 *
 * @author alexw
 */
class DatabaseSettings {

    /**
     *
     * @var DatabaseSettings 
     */
    private static $instance = null;

    /**
     * Datenbank-Server
     * @var string
     */
    protected $server;

    /**
     * Datenbank-Name
     * @var string
     */
    protected $name;

    /**
     * Datenbank-Benutzer
     * @var string
     */
    protected $user;

    /**
     * Datenbank-Passwort
     * @var string
     */
    protected $password;

    /**
     * Datenbank-Port
     * @var int
     */
    protected $port;

    function __construct() {
        $config = require dirname(dirname(__FILE__)) . '/Config/Config.php';
        $this->setServer($config['databaseServer']);
        $this->setName($config['databaseName']);
        $this->setUser($config['databaseUser']);
        $this->setPassword($config['databasePassword']);
        $this->setPort($config['databasePort']);
    }

    /**
     * Liefert die Instanz von DatabaseSettings
     * @return DatabaseSettings
     */
    public function getInstance() {
        if (self::$instance === null || $this->detectChanges() === true) {
            self::$instance = new DatabaseSettings();
        }

        return self::$instance;
    }

    /**
     * Überpüft den Config-Array auf Veränderungen
     * @return bool
     */
    private function detectChanges() {
        $config = require dirname(dirname(__FILE__)) . '/Config/Config.php';

        $changes = false;

        foreach ($config as $settingname => $settingvalue) {
            switch ($settingname) {
                case 'databaseServer':
                    $changes = $this->getServer() != $settingvalue || $changes === true ? true : false;
                    break;
                case 'databaseName':
                    $changes = $this->getName() != $settingvalue || $changes === true ? true : false;
                    break;
                case 'databaseUser':
                    $changes = $this->getUser() != $settingvalue || $changes === true ? true : false;
                    break;
                case 'databasePassword':
                    $changes = $this->getPassword() != $settingvalue || $changes === true ? true : false;
                    break;
                case 'databasePort':
                    $changes = $this->getPort() != $settingvalue || $changes === true ? true : false;
                    break;
            }
        }

        return $changes;
    }

    /**
     * Setzt den Datenbank-Server
     * @param string $server
     */
    private function setServer($server) {
        $this->server = $server;
    }

    /**
     * Setzt den Datenbank-Namen
     * @param string $name
     */
    private function setName($name) {
        $this->name = $name;
    }

    /**
     * Setzt den Datenbank-Benutzer
     * @param string $user
     */
    private function setUser($user) {
        $this->user = $user;
    }

    /**
     * Setzt das Datenbank-Passwort
     * @param string $password
     */
    private function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Setzt den Datenbank-Port
     * @param int $port
     */
    private function setPort($port) {
        $this->port = $port;
    }

    /**
     * Liefert den Datenbank-Server
     * @return string
     */
    public function getServer() {
        return $this->server;
    }

    /**
     * Liefert den Datenbank-Namen
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Liefert den Datenbank-Benutzer
     * @return string
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Liefert das Datenbank-Passwort
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Liefert den Datenbank-Port
     * @return int
     */
    public function getPort() {
        return $this->port;
    }

}
