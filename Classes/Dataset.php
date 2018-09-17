<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Dataset extends Item {

    /**
     *
     * @var Encryption 
     */
    protected $encryption;

    public function __construct($database, $encryption, $debugger) {
        parent::__construct(strtolower(__CLASS__));
        $this->setDatabase($database);
        $this->setDebugger($debugger);
        $this->setEncryption($encryption);
    }

    public function __destruct() {
        
    }

    /**
     * 
     * @param Encryption $encryption
     */
    public function setEncryption($encryption) {
        $this->encryption = $encryption;
    }

    /**
     * 
     * @return Encryption
     */
    public function getEncryption() {
        return $this->encryption;
    }

    /**
     * 
     * @param int $ID
     */
    public function setID($ID) {
        $this->data['id'] = $ID;
    }

    public function setUserID($userID) {
        $this->data['user_id'] = $userID;
    }

    public function setTitle($title) {
        $this->data['title'] = $title;
    }

    public function setDateCreated($dateCreated) {
        $this->data['date_created'] = $dateCreated;
    }

    public function setDateEdited($dateEdited) {
        $this->data['date_edited'] = $dateEdited;
    }

    public function setLogin($login) {
        $this->data['login'] = $login;
    }

    public function setPassword($password) {
        $this->data['password'] = $password;
    }

    public function setUrl($url) {
        $this->data['url'] = $url;
    }

    public function setProject($project) {
        $this->data['project'] = $project;
    }

    public function getID() {
        return $this->data['id'];
    }

    /**
     * 
     * @return int
     */
    public function getUserID() {
        return $this->data['user_id'];
    }

    public function getTitle() {
        return $this->data['title'];
    }

    public function getDateCreated() {
        return $this->data['date_created'];
    }

    public function getDateEdited() {
        return $this->data['date_edited'];
    }

    public function getLogin() {
        return $this->data['login'];
    }

    public function getPassword() {
        return $this->data['password'];
    }

    public function getUrl() {
        return $this->data['url'];
    }

    public function getProject() {
        return $this->data['project'];
    }

    public function encrypt() {
        try {
            $userID = $this->getUserID();
            $login = $this->getLogin();
            $password = $this->getPassword();
            $url = $this->getUrl();

            $this->setLogin($this->getEncryption()->encrypt($login, $userID));
            $this->setPassword($this->getEncryption()->encrypt($password, $userID));
            $this->setUrl($this->getEncryption()->encrypt($url, $userID));
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function decrypt() {
        try {
            $userID = $this->getUserID();
            $login = $this->getLogin();
            $password = $this->getPassword();
            $url = $this->getUrl();

            $this->setLogin($this->getEncryption()->decrypt($login, $userID));
            $this->setPassword($this->getEncryption()->decrypt($password, $userID));
            $this->setUrl($this->getEncryption()->decrypt($url, $userID));
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

}
