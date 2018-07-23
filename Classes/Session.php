<?php

/**
 * Description of Session
 *
 * @author alexw
 */
class Session {

    /**
     *
     * @var Database 
     */
    protected $database;

    /**
     *
     * @var Encryption 
     */
    protected $encryption;

    /**
     *
     * @var string 
     */
    protected $username;

    /**
     *
     * @var string 
     */
    protected $password;
    protected $ip_address;
    protected $host;
    protected $userAgent;
    protected $user_id;

    /**
     * 
     * @param Database $database
     * @param Encryption $encryption
     */
    public function __construct($database, $encryption) {
        $this->setDatabase($database);
        $this->setEncryption($encryption);
    }

    private function setUserID($userID) {
        $this->user_id = $userID;
    }

    private function getUserID() {
        return $this->user_id;
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

    /**
     * 
     * @param type $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * 
     * @return string
     */
    private function getHost() {
        return $this->host;
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
     * @param type $username
     */
    public function setIpaddress($ipaddress) {
        $this->ip_address = $ipaddress;
    }

    /**
     * 
     * @return type
     */
    private function getIpaddress() {
        return $this->ip_address;
    }

    /**
     * 
     * @param type $username
     */
    public function setUseragent($userAgent) {
        $this->userAgent = $userAgent;
    }

    /**
     * 
     * @return type
     */
    private function getUseragent() {
        return $this->userAgent;
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
        return password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);
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

    private function queryUserdata() {
        $dbConnection = $this->getDatabase()->openConnection();

        $row = array();
        $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

        $statement = $dbConnection->prepare("SELECT username,password FROM account WHERE username = :username AND active = 1");
        $statement->bindParam(":username", $username, PDO::PARAM_STR);

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $row['username'] = $object->username;
                $row['password'] = $object->password;
            }
        }

        $this->getDatabase()->closeConnection($dbConnection);

        return $row;
    }

    private function updatePassword($passwordToUpdate) {
        $dbConnection = $this->getDatabase()->openConnection();

        $success = false;
        $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);
        $password = $passwordToUpdate;

        $statement = $dbConnection->prepare("UPDATE account SET password = :password WHERE username = :username");
        $statement->bindParam(":password", $password, PDO::PARAM_STR);
        $statement->bindParam(":username", $username, PDO::PARAM_STR);

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
    public function validate() {
        try {
            if ($this->validCredentials() == false) {
                return false;
            }

            $success = false;
            $userData = $this->queryUserdata();
            $passwordSaved = $userData['password'];
            $usernameSaved = $userData['username'];
            $usernameInput = $this->getUsername();
            $passwordInput = $this->getPassword();

            if ($usernameSaved == $usernameInput && $usernameSaved != null) {
                if (password_verify($passwordInput, $passwordSaved)) {
                    $success = $this->startSession();
                }
            }

            return $success;
        } catch (Exception $ex) {
            file_put_contents(dirname(dirname(__FILE__)) . '/log.txt', $ex->getMessage());
        }
    }

    /**
     * 
     * @return boolean
     */
    private function startSession() {
        try {

            session_start();

            session_regenerate_id();

            $name = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

            $domain = $this->getHost();

            if ($domain === null) {
                return false;
            }

            $this->setUserID($this->getDatabase()->getUserID($name));
            $userID = $this->getUserID();
            $sessionID = session_id();
            $sessionToken = $this->generateSessionToken();
            $sessionTimestamp = $this->generateSessionTimestamp();
            $cookieTimestamp = $sessionTimestamp + (60 * 60 * 2);
            $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $this->getUseragent();
            $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $this->getIpaddress();

            setcookie('PHPSESSID', $sessionID, 0, '/', $domain, true, true);
            setcookie('TK', $sessionToken, 0, '/', $domain, true, true);
            setcookie('TS', $cookieTimestamp, 0, '/', $domain, true, true);

            $_SESSION['PHPSESSID'] = $sessionID;
            $_SESSION['U'] = $name;
            $_SESSION['UID'] = $userID;
            $_SESSION['IP'] = $ipAddress;
            $_SESSION['UA'] = $userAgent;
            $_SESSION['TK'] = $sessionToken;
            $_SESSION['TS'] = $sessionTimestamp;

            if (!isset($_SESSION['IP']) || !isset($_SESSION['UA'])) {
                return false;
            }

            $saveID = $this->getEncryption()->encrypt($sessionID, $name);
            $saveToken = $this->hashPassword($sessionToken);
            $saveTimestamp = $this->getEncryption()->encrypt($sessionTimestamp, $name);
            $saveIpaddress = $this->getEncryption()->encrypt($ipAddress, $name);
            $saveUseragent = $this->getEncryption()->encrypt($userAgent, $name);

            $this->saveSessionData($userID, $saveID, $saveToken, $saveTimestamp, $saveIpaddress, $saveUseragent, $saveVektor);

            return true;
        } catch (Exception $ex) {
            file_put_contents(dirname(dirname(__FILE__)) . '/log.txt', $ex->getMessage());
        }
    }

    public function ajaxCheck($sessionToken, $sessionTimestamp, $sessionIpAddress) {
        if (!isset($sessionToken)) {
            return false;
        }

        if (!isset($sessionTimestamp)) {
            return false;
        }

        return true;
    }

    public function isAuthenticated() {
        try {


            if (isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time()) {
                return false;
            }

            if (!isset($_SESSION['UID']) || $_SESSION['UID'] <= 0 || filter_var($_SESSION['UID'], FILTER_VALIDATE_INT) == false) {
                return false;
            }

            if (!isset($_SESSION['U']) || $this->getUsername() != $_SESSION['U']) {
                return false;
            }

            if (!isset($_SESSION['TK']) || $_SESSION['TK'] != $_COOKIE['TK']) {
                return false;
            }

            if (!isset($_SESSION['TS']) || $_SESSION['TS'] + (60 * 60 * 2) != $_COOKIE['TS']) {
                return false;
            }

            return true;
        } catch (Exception $ex) {
            file_put_contents(dirname(dirname(__FILE__)) . '/log.txt', $ex->getMessage());
        }
    }

    public function isValid() {
        try {
            if (isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES'])) {
                return false;
            }

            if (isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time()) {
                return false;
            }

            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . '<br/>';
        }
    }

    private function querySessionData() {
        $dbConnection = $this->getDatabase()->openConnection();

        $sessionData = array();
        $username = filter_var($name, FILTER_VALIDATE_EMAIL);

        $statement = $dbConnection->prepare("SELECT session_id,session_token,session_timestamp,session_ipaddress FROM session WHERE username = :username");
        $statement->bindParam(":username", $username, PDO::PARAM_STR);

        if ($statement->execute()) {
            while ($object = $statement->fetchObject()) {
                $sessionData['session_id'] = $object->session_id;
                $sessionData['session_token'] = $object->session_token;
                $sessionData['session_timestamp'] = $object->session_timestamp;
                $sessionData['session_ipaddress'] = $object->session_ipaddress;
            }
        }

        $this->getDatabase()->closeConnection($dbConnection);

        return $sessionData;
    }

    private function saveSessionData($user_id, $id, $token, $timestamp, $ipaddress, $useragent) {
        $dbConnection = $this->getDatabase()->openConnection();

        $success = false;
        $userID = filter_var($user_id, FILTER_VALIDATE_INT);
        $sessionID = filter_var($id, FILTER_SANITIZE_STRING);
        $sessionToken = $token;
        $sessionIpaddress = filter_var($ipaddress, FILTER_VALIDATE_IP);
        $sessionUserAgent = filter_var($useragent, FILTER_SANITIZE_STRING);
        $sessionTimestamp = $timestamp;

        $statement = $dbConnection->prepare("INSERT INTO session (user_id,session_id,session_token,session_timestamp,session_ipaddress,session_useragent) VALUES (:userID,:sessionID,:sessionToken,:sessionTimestamp,:sessionIpaddress,:sessionUseragent)");
        $statement->bindParam(":userID", $userID, PDO::PARAM_STR);
        $statement->bindParam(":sessionID", $sessionID, PDO::PARAM_STR);
        $statement->bindParam(":sessionToken", $sessionToken, PDO::PARAM_STR);
        $statement->bindParam(":sessionTimestamp", $sessionTimestamp, PDO::PARAM_STR);
        $statement->bindParam(":sessionIpaddress", $sessionIpaddress, PDO::PARAM_STR);
        $statement->bindParam(":sessionUseragent", $sessionUserAgent, PDO::PARAM_STR);

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
     * @param type $name
     * @return boolean
     */
    public function deleteSessionData($name) {
        $dbConnection = $this->getDatabase()->openConnection();

        $success = false;
        $userID = filter_var($this->getUserID(), FILTER_VALIDATE_INT);

        $statement = $dbConnection->prepare("DELETE FROM session WHERE user_id = :userID");
        $statement->bindParam(":userID", $userID, PDO::PARAM_INT);

        if ($statement->execute()) {
            if ($statement->rowCount() > 0) {
                $success = true;
            }
        }

        $this->getDatabase()->closeConnection($dbConnection);

        return $success;
    }

}
