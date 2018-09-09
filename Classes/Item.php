<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Item {

    /**
     * Tabellen-Name -> wird in der Child-Klasse an den Parent-Konstruktor übergeben
     * @var string 
     */
    protected $tableName;

    /**
     * Enthält die Daten der Tabelle. Assoziatives Array.
     * @var array 
     */
    protected $data;

    /**
     * Datenbank-Objekt
     * @var \Database 
     */
    protected $database;

    /**
     * Debugger-Objekt
     * @var \Debug 
     */
    protected $debug;

    /**
     * Initialisiert ein neues Item-Objekt
     * @param string $tableName
     */
    public function __construct($tableName) {
        $this->setData(array());
        $this->setTableName($tableName);
    }

    /**
     * 
     * @param string $tableName
     */
    protected function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    /**
     * 
     * @return string
     */
    protected function getTableName() {
        return $this->tableName;
    }

    /**
     * 
     * @param array $array
     */
    protected function setData($array) {
        $this->data = $array;
    }

    /**
     * 
     * @return array
     */
    protected function getData() {
        return $this->data;
    }

    /**
     * 
     * @return boolean
     */
    public function exists() {
        $dbConnection = $this->getDatabase()->openConnection();

        $ID = filter_var($this->get('id'), FILTER_VALIDATE_INT);
        $exists = false;
        $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);

        $statement = $dbConnection->prepare("SELECT id FROM :table WHERE id = :ID");
        $statement->bindParam(':table', $tableName);
        $statement->bindParam(':ID', $ID);

        if ($statement->execute()) {
            if ($statement->rowCount() > 0) {
                $exists = true;
            }
        }

        return $exists;
    }

    /**
     * 
     * @return boolean
     */
    public function insert() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;
            $columnString = '';
            $valueString = '';
            $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);
            $data = $this->getData();

            foreach ($data as $key => $value) {
                $columnString .= $key . ', ';
                $valueString .= $value . ', ';
            }

            $statement = $dbConnection->prepare("INSERT INTO $tableName (:columnString) VALUES (:valueString)");
            $statement->bindParam(':columnString', $columnString, PDO::PARAM_STR);
            $statement->bindParam(':valueString', $valueString, PDO::PARAM_STR);

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getColumnNames() {
        $dbConnection = $this->getDatabase()->openConnection();
        $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);

        $statement = $dbConnection->query("SELECT * FROM $tableName LIMIT 0");

        $columns = array();

        for ($i = 0; $i < $statement->columnCount(); $i++) {
            $col = $statement->getColumnMeta($i);
            $columns[] = $col['name'];
        }

        $columnNames = array();

        foreach ($columns as $key => $value) {
            $columnNames[$value] = $value;
        }


        $this->getDatabase()->closeConnection($dbConnection);

        return $columnNames;
    }

    /**
     * 
     * @return boolean
     */
    public function update() {
        $dbConnection = $this->getDatabase()->openConnection();

        $ID = filter_var($this->get('id'), FILTER_VALIDATE_INT);
        $success = false;
        $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);
        $data = $this->getData();
        $updateString = '';

        foreach ($data as $key => $value) {
            $updateString .= $key . ' = ' . $value . ',';
        }

        $statement = $dbConnection->prepare("UPDATE :table SET :updateString WHERE id = :ID");
        $statement->bindParam(':table', $tableName, PDO::PARAM_STR);
        $statement->bindParam(':ID', $ID, PDO::PARAM_INT);
        $statement->bindParam(':updateString', $updateString, PDO::PARAM_STR);

        if ($statement->execute()) {
            if ($statement->rowCount() > 0) {
                $success = true;
            }
        }

        $this->getDatabase()->closeConnection($dbConnection);

        return $success;
    }

    /**
     * 
     * @return boolean
     */
    public function delete() {
        $dbConnection = $this->getDatabase()->openConnection();

        $ID = filter_var($this->get('id'), FILTER_VALIDATE_INT);
        $success = false;
        $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);
        $statement = $dbConnection->prepare("DELETE FROM :table WHERE id = :ID");
        $statement->bindParam(':table', $tableName, PDO::PARAM_STR);
        $statement->bindParam(':ID', $ID, PDO::PARAM_INT);

        if ($statement->execute()) {
            if ($statement->rowCount() > 0) {
                $success = true;
            }
        }

        $this->getDatabase()->closeConnection($dbConnection);

        return $success;
    }

    /**
     * 
     * @return boolean
     */
    public function load() {
        $dbConnection = $this->getDatabase()->openConnection();

        $ID = filter_var($this->get('id'), FILTER_VALIDATE_INT);
        $userID = filter_var($this->get('user_id'), FILTER_VALIDATE_INT);

        $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);
        $columnNames = $this->getColumnNames();
        $columnString = '';

        $i = 1;

        foreach ($columnNames as $column) {
            if ($i < sizeof($columnNames)) {
                $columnString .= $column . ', ';
            } else {
                $columnString .= $column;
            }

            $i++;
        }

        if ($userID !== false) {
            $statement = $dbConnection->prepare("SELECT $columnString FROM $tableName WHERE id = :ID AND user_id = :userID");
            $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
        } else {
            $statement = $dbConnection->prepare("SELECT $columnString FROM $tableName WHERE id = :ID");
        }

        $statement->bindParam(':ID', $ID, PDO::PARAM_INT);

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                foreach ($columnNames as $column) {
                    $this->set($column, $object->$column);
                }
            }
        }

        $this->getDatabase()->closeConnection($dbConnection);
    }

    /**
     * 
     * @param type $key
     * @return type
     */
    public function get($key) {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * 
     * @param type $key
     * @param type $value
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * 
     * @param \Database $database
     */
    protected function setDatabase($database) {
        $this->database = $database;
    }

    /**
     * 
     * @param \Debug $debug
     */
    protected function setDebugger($debug) {
        $this->debug = $debug;
    }

    /**
     * 
     * @return \Database
     */
    protected function getDatabase() {
        return $this->database;
    }

    /**
     * 
     * @return \Debug
     */
    protected function getDebugger() {
        return $this->debug;
    }

}
