<?php

/**
 * Liefert uns Objekte
 *
 * @author alexw
 */
class Factory {

    private static $system;
    private static $databaseSettings;
    private static $database;

    public function getDatabaseSettings() {
        if (static::$databaseSettings == null || !isset(static::$databaseSettings)) {
            static::$databaseSettings = new DatabaseSettings;
        }

        return static::$databaseSettings;
    }

    public function getDatabase() {
        if (static::$database == null || !isset(static::$database)) {
            static::$database = new Database($this->getDatabaseSettings());
        }

        return static::$database;
    }

    public function createDataset() {
        return new Dataset;
    }

    public function getDataset() {
        
    }

    public function getSession() {
        return new Session($this->getDatabase());
    }

}
