<?php

/**
 * Führt allgemeine Aufgaben wie Error Logs durch
 *
 * @author alexw
 */
abstract class System implements SystemInterface {

    /**
     * Database Objekt
     * @var Database
     */
    var $database;

    function __construct() {
        $this->setDatabase(new Database());
    }

    /**
     * Setzt das Database-Objekt
     * @param Database $database
     */
    private function setDatabase($database) {
        $this->database = $database;
    }

    /**
     * Gibt das Database-Objekt zurück
     * @return Database
     */
    private function getDatabase() {
        return $this->database;
    }

}
