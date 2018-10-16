<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
final class Dataset extends Item {

    /**
     *
     * @var \Encryption 
     */
    protected $encryption;

    /**
     * 
     * @param \Database $database
     * @param \Encryption $encryption
     * @param \Debug $debugger
     */
    public function __construct(\Database $database, \Encryption $encryption, \Debug $debugger) {
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
    public function setEncryption(\Encryption $encryption) {
        $this->encryption = $encryption;
    }

    /**
     * 
     * @return Encryption
     */
    public function getEncryption(): \Encryption {
        return $this->encryption;
    }

    /**
     * 
     * @param int $ID
     */
    public function setID(int $ID) {
        $this->data['id'] = $ID;
    }

    /**
     * 
     * @param int $userID
     */
    public function setUserID(int $userID) {
        $this->data['user_id'] = $userID;
    }

    /**
     * 
     * @param string $title
     */
    public function setTitle(string $title) {
        $this->data['title'] = $title;
    }

    /**
     * 
     * @param string $dateCreated
     */
    public function setDateCreated(string $dateCreated) {
        $this->data['date_created'] = $dateCreated;
    }

    /**
     * 
     * @param string $dateEdited
     */
    public function setDateEdited(string $dateEdited) {
        $this->data['date_edited'] = $dateEdited;
    }

    /**
     * 
     * @param string $login
     */
    public function setLogin(string $login) {
        $this->data['login'] = $login;
    }

    /**
     * 
     * @param string $password
     */
    public function setPassword(string $password) {
        $this->data['password'] = $password;
    }

    /**
     * 
     * @param string $url
     */
    public function setUrl(string $url) {
        $this->data['url'] = $url;
    }

    /**
     * 
     * @param string $project
     */
    public function setProject(string $project) {
        $this->data['project'] = $project;
    }

    /**
     * 
     * @return int
     */
    public function getID(): int {
        return $this->data['id'];
    }

    /**
     * 
     * @return int
     */
    public function getUserID(): int {
        return $this->data['user_id'];
    }

    /**
     * 
     * @return string
     */
    public function getTitle(): string {
        return $this->data['title'];
    }

    /**
     * 
     * @return string
     */
    public function getDateCreated(): string {
        return $this->data['date_created'];
    }

    /**
     * 
     * @return string
     */
    public function getDateEdited(): string {
        return $this->data['date_edited'];
    }

    /**
     * 
     * @return string
     */
    public function getLogin(): string {
        return $this->data['login'];
    }

    /**
     * 
     * @return string
     */
    public function getPassword(): string {
        return $this->data['password'];
    }

    /**
     * 
     * @return string
     */
    public function getUrl(): string {
        return $this->data['url'];
    }

    /**
     * 
     * @return string
     */
    public function getProject(): string {
        return $this->data['project'];
    }

    /**
     * 
     */
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

    /**
     * 
     */
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
