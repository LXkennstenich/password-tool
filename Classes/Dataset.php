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
class Dataset {

    /**
     *
     * @var type 
     */
    protected $ID;

    /**
     *
     * @var int 
     */
    protected $user_id;

    /**
     *
     * @var type 
     */
    protected $title;

    /**
     *
     * @var type 
     */
    protected $dateCreated;

    /**
     *
     * @var type 
     */
    protected $dateEdited;

    /**
     *
     * @var type 
     */
    protected $login;

    /**
     *
     * @var type 
     */
    protected $password;

    /**
     *
     * @var type 
     */
    protected $url;

    /**
     *
     * @var type 
     */
    protected $project;

    /**
     *
     * @var Encryption 
     */
    protected $encryption;

    /**
     *
     * @var Database
     */
    protected $database;

    public function __construct($database, $encryption) {
        $this->setDatabase($database);
        $this->setEncryption($encryption);
        $this->setUserID($_SESSION['UID']);
    }

    /**
     * 
     * @param Database $database
     */
    private function setDatabase($database) {
        $this->database = $database;
    }

    /**
     * 
     * @return Database
     */
    private function getDatabase() {
        return $this->database;
    }

    /**
     * 
     * @param Encryption $encryption
     */
    private function setEncryption($encryption) {
        $this->encryption = $encryption;
    }

    /**
     * 
     * @return Encryption
     */
    private function getEncryption() {
        return $this->encryption;
    }

    public function setUserID($userID) {
        $this->user_id = $userID;
    }

    /**
     * 
     * @return int
     */
    public function getUserID() {
        return (int) $this->user_id;
    }

    /**
     * 
     * @param int $ID
     */
    public function setID($ID) {
        $this->ID = (int) $ID;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    public function setDateEdited($dateEdited) {
        $this->dateEdited = $dateEdited;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setProject($project) {
        $this->project = $project;
    }

    public function getID() {
        return $this->ID;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getDateEdited() {
        return $this->dateEdited;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getProject() {
        return $this->project;
    }

    public function encrypt() {
        $userID = $this->getUserID();
        $login = $this->getLogin();
        $password = $this->getPassword();
        $title = $this->getTitle();
        $url = $this->getUrl();
        $project = $this->getProject();

        $this->setLogin($this->getEncryption()->encrypt($login, $userID));
        $this->setPassword($this->getEncryption()->encrypt($password, $userID));
        $this->setTitle($this->getEncryption()->encrypt($title, $userID));
        $this->setUrl($this->getEncryption()->encrypt($url, $userID));
        $this->setProject($this->getEncryption()->encrypt($project, $userID));
    }

    public function decrypt() {
        $userID = $this->getUserID();
        $login = $this->getLogin();
        $password = $this->getPassword();
        $title = $this->getTitle();
        $url = $this->getUrl();
        $project = $this->getProject();

        $this->setLogin($this->getEncryption()->decrypt($login, $userID));
        $this->setPassword($this->getEncryption()->decrypt($password, $userID));
        $this->setTitle($this->getEncryption()->decrypt($title, $userID));
        $this->setUrl($this->getEncryption()->decrypt($url, $userID));
        $this->setProject($this->getEncryption()->decrypt($project, $userID));
    }

    public function load() {
        $userID = $this->getUserID();
        $id = $this->getID();
        $dbConnetion = $this->getDatabase()->openConnection();

        $statement = $dbConnetion->prepare("SELECT title,date_created,date_edited,login,password,url,project FROM datasets WHERE id = :id AND user_id = :userID");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $this->setTitle($object->title);
                $this->setDateCreated($object->date_created);
                $this->setDateCreated($object->date_edited);
                $this->setLogin($object->login);
                $this->setPassword($object->password);
                $this->setUrl($object->url);
                $this->setProject($object->project);
            }
        }
    }

    public function insert() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $success = false;

            $userID = $this->getUserID();
            $title = $this->getTitle();
            $login = $this->getLogin();
            $password = $this->getPassword();
            $url = $this->getUrl();
            $project = $this->getProject();

            $statement = $dbConnection->prepare("INSERT INTO datasets (user_id,title,login,password,url,project) VALUES (:userID,:title,:login,:password,:url,:project)");
            $statement->bindParam(':userID', $userID, PDO::PARAM_INT);
            $statement->bindParam(':title', $title, PDO::PARAM_STR);
            $statement->bindParam(':login', $login, PDO::PARAM_STR);
            $statement->bindParam(':password', $password, PDO::PARAM_STR);
            $statement->bindParam(':url', $url, PDO::PARAM_STR);
            $statement->bindParam(':project', $project, PDO::PARAM_STR);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            return $success;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function update() {
        
    }

}
