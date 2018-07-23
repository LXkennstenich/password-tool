<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
class Factory {

    private static $databaseSettings;
    private static $database;
    private static $encryption;

    public function getDatabaseSettings() {
        if (static::$databaseSettings == null || !isset(static::$databaseSettings)) {
            static::$databaseSettings = new DatabaseSettings;
        }

        return static::$databaseSettings;
    }

    /**
     * 
     * @return Database
     */
    public function getDatabase() {
        if (static::$database == null || !isset(static::$database)) {
            static::$database = new Database($this->getDatabaseSettings());
        }

        return static::$database;
    }

    /**
     * 
     * @return Encryption
     */
    public function getEncryption() {
        if (static::$encryption == null || !isset(static::$encryption)) {
            static::$encryption = new Encryption($this->getDatabase(), $_SESSION['UID']);
        }

        return static::$encryption;
    }

    public function createDataset() {
        return new Dataset($this->getDatabase(), $this->getEncryption());
    }

    public function getDataset($ID) {
        $id = filter_var($ID, FILTER_SANITIZE_STRING);
        $userID = filter_var($_SESSION['UID'], FILTER_SANITIZE_STRING);

        $dataset = $this->createDataset();
        $dataset->setID($id);
        $dataset->setUserID($userID);
        $dataset->load();


        return $dataset;
    }

    public function getDatasets($user_id) {
        $userID = $user_id;
        $datasets = array();

        $dbConnection = $this->getDatabase()->openConnection();
        $statement = $dbConnection->prepare("SELECT id,user_id,title,date_created,date_edited,login,password,url,project FROM datasets WHERE user_id = :userID");
        $statement->bindParam(':userID', $userID, PDO::PARAM_STR);

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $dataset = $this->createDataset();

                $dataset->setID($object->id);
                $dataset->setUserID($object->user_id);
                $dataset->setTitle($object->title);
                $dataset->setDateCreated($object->date_created);
                $dataset->setDateEdited($object->date_edited);
                $dataset->setLogin($object->login);
                $dataset->setPassword($object->password);
                $dataset->setUrl($object->url);
                $dataset->setProject($object->project);

                $datasets[] = $dataset;
            }
        }

        return $datasets;
    }

    public function searchDatasets($user_id, $searchString) {
        $userID = $user_id;
        $datasets = array();
        $searchTerm = $searchString;

        $dbConnection = $this->getDatabase()->openConnection();



        $statement = $dbConnection->prepare("SELECT id,user_id,title,date_created,date_edited,login,password,url,project FROM datasets WHERE user_id = :userID");
        $statement->bindParam(':userID', $userID, PDO::PARAM_STR);

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $dataset = $this->createDataset();

                $dataset->setID($object->id);
                $dataset->setUserID($object->user_id);
                $dataset->setTitle($object->title);
                $dataset->setDateCreated($object->date_created);
                $dataset->setDateEdited($object->date_edited);
                $dataset->setLogin($object->login);
                $dataset->setPassword($object->password);
                $dataset->setUrl($object->url);
                $dataset->setProject($object->project);

                $datasets[] = $dataset;
            }
        }

        $filteredDatasets = array();

        /* @var $dataset Dataset */
        foreach ($datasets as $dataset) {
            $dataset->decrypt();

            $title = $dataset->getTitle();
            $login = $dataset->getLogin();

            if (strpos(strtolower($title), $searchTerm) !== false || strpos(strtolower($login), $searchTerm) !== false) {
                $dataset->encrypt();
                $filteredDatasets[] = $dataset;
            }
        }

        unset($datasets);

        return $filteredDatasets;
    }

    public function getSession() {
        return new Session($this->getDatabase(), $this->getEncryption());
    }

    public function getAccount() {
        return new Account($this->getDatabase());
    }

    public function redirect($page) {
        $host = $_SERVER['HTTP_HOST'];
        header("Location: https://{$host}/" . $page);
        die();
    }

}
