<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class DatabaseSettings {

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

    /**
     * Admin-Email
     * @var string
     */
    protected $adminEmail;

    function __construct() {
        $config = require CONFIG_DIR . 'Config.php';
        $this->setServer($config['databaseServer']);
        $this->setName($config['databaseName']);
        $this->setUser($config['databaseUser']);
        $this->setPassword($config['databasePassword']);
        $this->setPort($config['databasePort']);
        $this->setAdminEmail($config['adminEmail']);
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
     * Setzt die Admin Email
     * @param string $email
     */
    private function setAdminEmail($email) {
        $this->adminEmail = $email;
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

    /**
     * Liefert den Datenbank-Port
     * @return string
     */
    public function getAdminEmail() {
        return $this->adminEmail;
    }

}
