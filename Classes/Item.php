<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Item implements ItemInterface {

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
    public function __construct(string $tableName) {

        $this->setData(array());
        $this->setTableName($tableName);
    }

    /**
     * 
     * @param string $tableName
     */
    protected function setTableName(string $tableName) {
        $this->tableName = $tableName;
    }

    /**
     * 
     * @return string
     */
    protected function getTableName(): string {
        return $this->tableName;
    }

    /**
     * 
     * @param array $array
     */
    public function setData(array $array) {
        $this->data = $array;
    }

    /**
     * 
     * @return array
     */
    public function getData(): array {
        return $this->data;
    }

    /**
     * 
     * @return boolean
     */
    public function exists(): bool {
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
    public function insert(): bool {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;
            $columnString = '';
            $valueString = '';
            $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);
            $data = $this->getData();

            $maxIndex = sizeof($data) - 1;

            $i = 0;

            foreach ($data as $key => $value) {

                if ($i < $maxIndex) {
                    $columnString .= $key . ', ';
                    $valueString .= "'" . $value . "'" . ', ';
                } else {
                    $columnString .= $key;
                    $valueString .= "'" . $value . "'";
                }



                $i++;
            }

            $statement = $dbConnection->prepare("INSERT INTO $tableName ( $columnString ) VALUES ( $valueString )");

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

    public function getColumnNames(): array {
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
    public function update(): bool {
        $dbConnection = $this->getDatabase()->openConnection();

        $ID = filter_var($this->get('id'), FILTER_VALIDATE_INT);
        $success = false;
        $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);
        $data = $this->getData();
        $updateString = '';

        $maxIndex = sizeof($data) - 1;

        $i = 0;

        foreach ($data as $key => $value) {
            if ($i < $maxIndex) {
                $updateString .= $key . ' = ' . "'" . $value . "'" . ', ';
            } else {
                $updateString .= $key . ' = ' . "'" . $value . "'";
            }

            $i++;
        }

        $statement = $dbConnection->prepare("UPDATE $tableName SET $updateString WHERE id = :ID");
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
    public function delete(): bool {
        $dbConnection = $this->getDatabase()->openConnection();

        $ID = $this->get('id');
        $success = false;
        $tableName = filter_var($this->getTableName(), FILTER_SANITIZE_STRING);
        $statement = $dbConnection->prepare("DELETE FROM $tableName WHERE id = :ID");
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


        if ($ID !== false && $userID !== false) {
            $statement = $dbConnection->prepare("SELECT $columnString FROM $tableName WHERE id = :ID AND user_id = :userID");
            $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
            $statement->bindParam(':ID', $ID, PDO::PARAM_INT);
        } else if ($userID === false) {
            $statement = $dbConnection->prepare("SELECT $columnString FROM $tableName WHERE id = :ID");
            $statement->bindParam(':ID', $ID, PDO::PARAM_INT);
        } else if ($ID === false) {
            $statement = $dbConnection->prepare("SELECT $columnString FROM $tableName WHERE user_id = :userID");
            $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
        }



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
     * @param string $key
     * @return type
     */
    public function get(string $key) {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * 
     * @param string $key
     * @param type $value
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * 
     * @param \Database $database
     */
    protected function setDatabase(\Database $database) {
        $this->database = $database;
    }

    /**
     * 
     * @param \Debug $debug
     */
    protected function setDebugger(\Debug $debug) {
        $this->debug = $debug;
    }

    /**
     * 
     * @return \Database
     */
    protected function getDatabase(): \Database {
        return $this->database;
    }

    /**
     * 
     * @return \Debug
     */
    protected function getDebugger(): \Debug {
        return $this->debug;
    }

}
