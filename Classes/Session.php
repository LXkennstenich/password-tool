<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Session {

    /**
     *
     * @var \Database 
     */
    protected $database;

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

    /**
     *
     * @var string 
     */
    protected $ip_address;

    /**
     *
     * @var string 
     */
    protected $host;

    /**
     *
     * @var string 
     */
    protected $userAgent;

    /**
     *
     * @var int 
     */
    protected $user_id;

    /**
     *
     * @var \Debug 
     */
    protected $debugger;

    /**
     * 
     * @param \Database $database
     * @param \Debug $debugger
     */
    public function __construct($database, $debugger) {
        $this->setDatabase($database);
        $this->setDebugger($debugger);
    }

    /**
     * 
     * @return \Debug
     */
    private function getDebugger() {
        return $this->debugger;
    }

    /**
     * 
     * @param \Debug $debugger
     */
    private function setDebugger($debugger) {
        $this->debugger = $debugger;
    }

    public function setUserID($userID) {
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
     * @param type $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * 
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * 
     * @param type $username
     */
    public function setUsername($username) {
        $this->username = filter_var($username, FILTER_VALIDATE_EMAIL);
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
        $this->ip_address = filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
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
        $this->password = filter_var($password, FILTER_SANITIZE_STRING);
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
     * @param string $password
     * @return string
     */
    public function hashPassword($password) {
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
        return bin2hex(openssl_random_pseudo_bytes(64));
    }

    /**
     * 
     * @return int
     */
    private function generateSessionTimestamp() {
        return time();
    }

    private function queryUserdata() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $row = array();
            $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

            if ($username) {
                $statement = $dbConnection->prepare("SELECT username,password FROM account WHERE username = :username AND active = 1");
                $statement->bindParam(":username", $username, PDO::PARAM_STR);

                if ($statement->execute()) {
                    while ($object = $statement->fetchObject()) {
                        $row['username'] = $object->username;
                        $row['password'] = $object->password;
                    }
                }

                $this->getDatabase()->closeConnection($dbConnection);
            }

            return $row;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    private function updatePassword($passwordToUpdate) {
        try {
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
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function queryUserID() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $ID = null;
            $name = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

            $statement = $dbConnection->prepare("SELECT id FROM account WHERE username = :username");
            $statement->bindParam(":username", $name, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $ID = $object->id;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $ID;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function needAuthenticator() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $needAuthenticator = true;
            $userID = filter_var($this->getUserID(), FILTER_VALIDATE_INT);

            $statement = $dbConnection->prepare("SELECT session_authenticator FROM session WHERE user_id = :userID");
            $statement->bindParam(":userID", $userID, PDO::PARAM_INT);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $databaseValue = filter_var($object->session_authenticator, FILTER_VALIDATE_INT);
                    if ($databaseValue !== false) {
                        $needAuthenticator = $databaseValue === 1 ? false : true;
                    }
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $needAuthenticator;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function updateAuthenticator($value, $user_id) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;
            $userID = filter_var($user_id, FILTER_VALIDATE_INT);
            $authenticator = filter_var($value, FILTER_VALIDATE_INT);

            $statement = $dbConnection->prepare("UPDATE session SET session_authenticator = :authenticator WHERE user_id = :userID");
            $statement->bindParam(":userID", $userID, PDO::PARAM_INT);
            $statement->bindParam(":authenticator", $authenticator, PDO::PARAM_INT);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function sendLockMail($username) {
        try {
            $mailAddress = filter_var($username, FILTER_VALIDATE_EMAIL);

            $host = $_SERVER['HTTP_HOST'];
            $hostAddress = 'https://' . $host;

            $header = 'From: no-reply@' . $host . "\r\n" .
                    'X-Sender: ' . $hostAddress . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

            $subject = 'Ihr Zugang wurde vorübergehend gesperrt';
            $message = 'Das System hat eine mehrfach falsche Passworteingabe erkannt und aus Sicherheitsgründen den Zugang für 15 Minuten gesperrt.' . "\r\n";
            $message .= 'Wenn der Account 3 mal hintereinander gesperrt wird blockiert das System die Angreifer IP-Adresse dauerhauft' . "\r\n";

            mail($mailAddress, $subject, $message, $header);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function unlockAccount($username) {
        try {
            $name = filter_var($username, FILTER_VALIDATE_EMAIL);

            $success = false;

            $dbConnection = $this->getDatabase()->openConnection();

            $statement = $dbConnection->prepare("UPDATE account SET login_attempts = 0, locked = 0, locktime = 0 WHERE username = :username");
            $statement->bindParam(':username', $name, PDO::PARAM_STR);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function isAccountLocked($username) {
        try {
            $name = filter_var($username, FILTER_VALIDATE_EMAIL);

            $locked = false;

            $dbConnection = $this->getDatabase()->openConnection();

            $statement = $dbConnection->prepare("SELECT locked FROM account WHERE username = :username");
            $statement->bindParam(':username', $name, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $locked = $object->locked == 0 ? false : true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $locked;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getLockTime($username) {
        try {
            $name = filter_var($username, FILTER_VALIDATE_EMAIL);

            $locktime = 0;

            $dbConnection = $this->getDatabase()->openConnection();

            $statement = $dbConnection->prepare("SELECT locktime FROM account WHERE username = :username");
            $statement->bindParam(':username', $name, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $locktime = $object->locktime;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $locktime;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function lockAccount($username) {
        try {
            $name = filter_var($username, FILTER_VALIDATE_EMAIL);
            $lockTime = time() + (15 * 60);
            $success = false;

            $dbConnection = $this->getDatabase()->openConnection();

            $statement = $dbConnection->prepare("UPDATE account SET locked = 1, locktime = :lockTime WHERE username = :username");
            $statement->bindParam(':username', $name, PDO::PARAM_STR);
            $statement->bindParam(':lockTime', $lockTime, PDO::PARAM_INT);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getLoginAttempts($username) {
        try {
            $name = filter_var($username, FILTER_VALIDATE_EMAIL);

            $attempts = 0;

            $dbConnection = $this->getDatabase()->openConnection();

            $statement = $dbConnection->prepare("SELECT login_attempts FROM account WHERE username = :username");
            $statement->bindParam(':username', $name, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $attempts = $object->login_attempts;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $attempts;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function countLoginAttempt($username) {
        try {
            $name = filter_var($username, FILTER_VALIDATE_EMAIL);
            $success = false;
            $attempts = $this->getLoginAttempts($name) == '' || $this->getLoginAttempts($name) == null ? 0 : $this->getLoginAttempts($name);
            $attempts++;

            $dbConnection = $this->getDatabase()->openConnection();

            $statement = $dbConnection->prepare("UPDATE account SET login_attempts = :loginAttempts WHERE username = :username");
            $statement->bindParam(':username', $name, PDO::PARAM_STR);
            $statement->bindParam(':loginAttempts', $attempts, PDO::PARAM_INT);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    private function usernameExists() {
        $dbConnection = $this->getDatabase()->openConnection();
        $inputUsername = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);
        $usernameExists = false;

        $statement = $dbConnection->prepare("SELECT id FROM account WHERE username = :username");
        $statement->bindParam(':username', $inputUsername);

        if ($statement->execute()) {
            if ($statement->rowCount() > 0 && $statement->rowCount() < 2) {
                $usernameExists = true;
            }
        }

        return $usernameExists;
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

            if ($this->usernameExists() !== false) {
                $userData = $this->queryUserdata();
                $passwordSaved = $userData['password'];
                $usernameSaved = $userData['username'];
                $usernameInput = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);
                $passwordInput = $this->getPassword();

                $infoArray = password_get_info($passwordSaved);

                if ($infoArray['algo'] != 1 || $infoArray['algoName'] != 'bcrypt' || $infoArray['options']['cost'] != 12) {
                    return false;
                }

                if ($usernameSaved == $usernameInput && $usernameSaved != null) {
                    if (password_verify($passwordInput, $passwordSaved)) {
                        if (password_needs_rehash($passwordSaved, PASSWORD_DEFAULT, ["cost" => 12])) {
                            $newPassword = $this->hashPassword($passwordSaved);
                            $this->updatePassword($newPassword);
                        }
                        $success = true;
                    }
                }
            }

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    private function queryAccessLevel($id, $username) {
        try {
            $ID = filter_var($id, FILTER_VALIDATE_INT);
            $name = filter_var($username, FILTER_VALIDATE_EMAIL);
            $accessLevel = 0;

            $dbConnection = $this->getDatabase()->openConnection();

            $statement = $dbConnection->prepare("SELECT access_level FROM account WHERE id = :ID AND username = :username");
            $statement->bindParam(':ID', $ID, PDO::PARAM_INT);
            $statement->bindParam(':username', $name, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $accessLevel = $object->access_level;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $accessLevel;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @return boolean
     */
    public function startSession() {
        try {
            session_start();

            session_regenerate_id();

            $name = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

            $domain = $this->getHost();

            if ($domain === null) {
                return false;
            }

            $userID = $this->getUserID();
            $sessionID = session_id();
            $sessionToken = $this->generateSessionToken();
            $sessionTimestamp = $this->generateSessionTimestamp();
            $cookieTimestamp = $sessionTimestamp + (60 * 60 * 2);
            $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $this->getUseragent();
            $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $this->getIpaddress();
            $accessLevel = $this->queryAccessLevel($userID, $name);

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
            $_SESSION['EXPIRES'] = $cookieTimestamp;
            $_SESSION['AL'] = $accessLevel;
            $_SESSION['AUTH'] = 0;

            if (!isset($_SESSION['IP']) || !isset($_SESSION['UA'])) {
                return false;
            }

            $saveID = $sessionID;
            $saveToken = $sessionToken;
            $saveTimestamp = $sessionTimestamp;
            $saveIpaddress = $ipAddress;
            $saveUseragent = $userAgent;

            $this->saveSessionData($userID, $saveID, $saveToken, $saveTimestamp, $saveIpaddress, $saveUseragent);

            return true;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * @todo Datenbankabfrage für Session
     * @param string $sessionToken
     * @param string $sessionTimestamp
     * @param string $sessionIpAddress
     * @param string $userID
     * @return boolean
     */
    public function ajaxCheck($sessionToken, $sessionTimestamp, $sessionIpAddress, $userID) {
        try {
            $sessionData = $this->queryAjaxSessionData($userID);
            $savedToken = $sessionData['session_token'];
            $savedTimestamp = $sessionData['session_timestamp'];
            $savedIpaddress = $sessionData['session_ipaddress'];

            if ($sessionToken == '') {
                return false;
            }

            if ($sessionTimestamp == '') {
                return false;
            }

            if ($sessionIpAddress == '') {
                return false;
            }

            if ($sessionTimestamp != $savedTimestamp) {
                return false;
            }

            if ($sessionToken != $savedToken) {
                return false;
            }

            if ($sessionIpAddress != $savedIpaddress) {
                return false;
            }

            return true;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function isAuthenticated() {
        try {
            $authenticated = null;


            if (session_status() !== PHP_SESSION_ACTIVE) {
                return false;
            }

            if ($this->isValid() === false) {
                $authenticated = false;
            }

            if (isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time()) {
                $authenticated = false;
            }

            if (!isset($_SESSION['UID']) || $_SESSION['UID'] <= 0 || filter_var($_SESSION['UID'], FILTER_VALIDATE_INT) == false) {
                $authenticated = false;
            }

            if (!isset($_SESSION['U']) || $this->getUsername() != $_SESSION['U']) {
                $authenticated = false;
            }

            if (!isset($_SESSION['TK']) || $_SESSION['TK'] != $_COOKIE['TK']) {
                $authenticated = false;
            }

            if (!isset($_SESSION['TS']) || $_SESSION['TS'] + (60 * 60 * 2) != $_COOKIE['TS']) {
                $authenticated = false;
            }

            if ($authenticated === false) {
                $this->deleteSessionData($_SESSION['UID']);
                return false;
            }



            return true;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function isValid() {
        try {

            if (!isset($_SESSION['PHPSESSID'])) {
                return false;
            }

            if (!isset($_SESSION['EXPIRES'])) {
                return false;
            }

            if (!isset($_SESSION['UID'])) {
                return false;
            }

            if (!isset($_SESSION['U'])) {
                return false;
            }

            if (!isset($_SESSION['TK'])) {
                return false;
            }

            if (!isset($_SESSION['TS'])) {
                return false;
            }

            if (isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES'])) {
                return false;
            }

            if (isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time()) {
                return false;
            }

            return true;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function queryAjaxSessionData($user_id) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $sessionData = array();
            $userID = $user_id;

            $statement = $dbConnection->prepare("SELECT session_token,session_timestamp,session_ipaddress FROM session WHERE user_id = :userID");
            $statement->bindParam(":userID", $userID, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $sessionData['session_token'] = $object->session_token;
                    $sessionData['session_timestamp'] = $object->session_timestamp;
                    $sessionData['session_ipaddress'] = $object->session_ipaddress;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $sessionData;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    private function saveSessionData($user_id, $id, $token, $timestamp, $ipaddress, $useragent) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;
            $userID = filter_var($user_id, FILTER_SANITIZE_STRING);
            $sessionID = filter_var($id, FILTER_SANITIZE_STRING);
            $sessionToken = $token;
            $sessionIpaddress = filter_var($ipaddress, FILTER_SANITIZE_STRING);
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
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function deleteSessionDataFromDatabase($user_id) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;
            $userID = $user_id;

            $statement = $dbConnection->prepare("DELETE FROM session WHERE user_id = :userID");
            $statement->bindParam(":userID", $userID, PDO::PARAM_INT);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $userID
     * @return boolean
     */
    public function deleteSessionData($user_id) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;
            $userID = $user_id;

            $statement = $dbConnection->prepare("DELETE FROM session WHERE user_id = :userID");
            $statement->bindParam(":userID", $userID, PDO::PARAM_INT);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];

                    session_start();
                    $_SESSION = array();
                    $_SESSION['OBSOLETE'] = true;
                    $_SESSION['EXPIRES'] = time() + 10;

                    setcookie('PHPSESSID', '', time() - 80000, '/', $domain, true, true);
                    setcookie('TK', '', time() - 80000, '/', $domain, true, true);
                    setcookie('TS', '', time() - 80000, '/', $domain, true, true);

                    session_destroy();
                    $success = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            //apcu_clear_cache();

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

}
