<?php

/**
 * Description of Session
 *
 * @author alexw
 */
class Session {

    /**
     *
     * @var type 
     */
    protected $database;

    /**
     *
     * @var type 
     */
    protected $username;

    /**
     *
     * @var type 
     */
    protected $password;

    /**
     *
     * @var type 
     */
    protected $timestamp;

    /**
     *
     * @var type 
     */
    protected $token;

    /**
     * 
     * @param Database $database
     */
    public function __construct($database) {
        $this->setDatabase($database);
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
     * @param type $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * 
     * @return type
     */
    private function getUsername() {
        return $this->username;
    }

    /**
     * 
     * @param type $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * 
     * @return type
     */
    private function getPassword() {
        return $this->password;
    }

    /**
     * 
     * @param type $password
     * @return type
     */
    private function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT, ["cost" => $this->getMaxCost()]);
    }

    /**
     * 
     * @return boolean
     */
    private function issetUsername() {

        $username = $this->getUsername();

        if (isset($username) && $username != null) {
            return true;
        }

        return false;
    }

    /**
     * 
     * @return boolean
     */
    private function issetPassword() {
        $password = $this->getPassword();

        if (isset($password) && $password != null) {
            return true;
        }

        return false;
    }

    /**
     * 
     * @return boolean
     */
    private function validCredentials() {
        if ($this->issetPassword() == true && $this->issetUsername() == true) {
            return true;
        }

        return false;
    }

    /**
     * 
     * @return int
     */
    protected function getMaxCost() {
        $timeTarget = 0.05;
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_DEFAULT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        return $cost;
    }

    /**
     * 
     * @return boolean
     */
    public function sessionStarted() {
        if (!isset($_SESSION['username']) || $_SESSION['username'] == null) {
            return false;
        }

        if (!isset($_SESSION['token']) || $_SESSION['token'] == null) {
            return false;
        }

        if (!isset($_SESSION['timestamp']) || $_SESSION['timestamp'] == null) {
            return false;
        }

        if (!isset($_COOKIE['tk']) || filter_input(INPUT_COOKIE, $_COOKIE['tk']) == null) {
            return false;
        }

        if (!isset($_COOKIE['ts']) || filter_input(INPUT_COOKIE, $_COOKIE['ts']) == null) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @return string
     */
    private function generateSessionToken() {
        return bin2hex(openssl_random_pseudo_bytes(256));
    }

    /**
     * 
     * @return int
     */
    private function generateSessionTimestamp() {
        return time();
    }

    /**
     * 
     * @param int $time
     * @return int
     */
    private function generateCookieTimestamp($time) {
        return $timestamp = $time + (2 * 3600);
    }

    private function queryUserdata() {
        $dbConnection = $this->getDatabase()->openConnection();

        $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

        $statement = $dbConnection->prepare("SELECT Username,Password FROM Account WHERE Username:username");
        $statement->bindParam(":username", $username);

        if ($statement->execute()) {
            
        }
    }

    /**
     * 
     * @return boolean
     */
    public function validate() {
        if ($this->validCredentials() == false) {
            return false;
        }
    }

    /**
     * 
     * @return boolean
     */
    private function startSession() {
        if ($this->validCredentials() == false) {
            return false;
        }
    }

}
