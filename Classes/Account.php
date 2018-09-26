<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * 
 * @author Alexander Weese <contact@alexweese.de>
 * 
 * @copyright (c) 2018, Alexander Weese
 */
class Account {



    /**
     *
     * @var type 
     */
    protected $database;

    /**
     *
     * @var type 
     */
    protected $id;

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
    protected $last_login;

    /**
     *
     * @var type 
     */
    protected $access_level;

    /**
     *
     * @var type 
     */
    protected $secret_key;

    /**
     *
     * @var type 
     */
    protected $encryption_key;

    /**
     *
     * @var type 
     */
    protected $validation_token;

    /**
     *
     * @var type 
     */
    protected $cipherMode;

    /**
     *
     * @var type 
     */
    protected $first_login_password_changed;

    /**
     *
     * @var type 
     */
    protected $authenticator_is_setup;

    /**
     *
     * @var type 
     */
    protected $debugger;

    /**
     * 
     * @param type $database
     * @param type $debugger
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
     * @param type $token
     */
    public function setValidationToken($token) {
        $this->validation_token = $token;
    }

    /**
     * 
     * @return type
     */
    public function getValidationToken() {
        return $this->validation_token;
    }

    /**
     * 
     * @param type $ID
     */
    public function setID($ID) {
        $this->id = $ID;
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
     * @param type $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * 
     * @param type $lastLogin
     */
    public function setLastLogin($lastLogin) {
        $this->last_login = $lastLogin;
    }

    /**
     * 
     * @param type $accessLevel
     */
    public function setAccessLevel($accessLevel) {
        $this->access_level = $accessLevel;
    }

    /**
     * 
     * @param type $secretKey
     */
    public function setSecretKey($secretKey) {
        $this->secret_key = $secretKey;
    }

    /**
     * 
     * @param type $encryptionKey
     */
    public function setEncryptionKey($encryptionKey) {
        $this->encryption_key = $encryptionKey;
    }

    /**
     * 
     * @return type
     */
    public function getID() {
        return $this->id;
    }

    /**
     * 
     * @return type
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * 
     * @return type
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * 
     * @return type
     */
    public function getLastLogin() {
        return $this->last_login;
    }

    /**
     * 
     * @return type
     */
    public function getAccessLevel() {
        return $this->access_level;
    }

    /**
     * 
     * @return type
     */
    public function getSecretKey() {
        return $this->secret_key;
    }

    /**
     * 
     * @return type
     */
    public function getEncryptionKey() {
        return $this->encryption_key;
    }

    /**
     * 
     * @return type
     */
    private function generatePassword() {
        $passwortLength = random_int(8, 16);

        $allowedCharacters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!?@(){}[]\/=$%&#-+.,_';
        $password = '';

        for ($i = 0; $i < $passwortLength; $i++) {
            $allowedCharactersLength = strlen($allowedCharacters);
            $random = random_int(0, $allowedCharactersLength);
            $password .= mb_substr($allowedCharacters, $random, 1);
        }

        return $password;
    }

    /**
     * 
     * @return type
     */
    private function generateValidationToken() {
        return bin2hex(openssl_random_pseudo_bytes(random_int(128, 256)));
    }

    /**
     * 
     * @return type
     */
    private function generateSecretKey() {
        try {
            $secretKeyLength = 10;

            $allowedCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
            $secretKey = '';

            for ($i = 1; $i <= $secretKeyLength; $i++) {
                $allowedCharactersLength = strlen($allowedCharacters);
                $random = random_int(0, $allowedCharactersLength);
                $secretKey .= mb_substr($allowedCharacters, $random, 1);
            }

            return $secretKey;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @return type
     */
    public function generateEncryptionKey() {
        return bin2hex(openssl_random_pseudo_bytes(256));
    }

    /**
     * 
     * @param type $password
     * @return type
     */
    private function hashPassword($password) {
        try {
            return password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);
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
    public function save() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;
            $creation = false;
            $ID = filter_var($this->getID(), FILTER_VALIDATE_INT);
            $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);
            $password = $this->getPassword();
            $accessLevel = filter_var($this->getAccessLevel(), FILTER_VALIDATE_INT);
            $secretKey = $this->getSecretKey();
            $encryptionKey = filter_var($this->getEncryptionKey(), FILTER_SANITIZE_STRING);
            $sql = '';
            $statement = new PDOStatement;

            if ($ID == false) {
                $creation = true;
                $sql .= "INSERT INTO account (username,password,access_level,secret_key,encryption_key,validation_token) VALUES (:username,:password,:accessLevel,:secretKey,:encryptionKey,:validationToken)";
                $statement = $dbConnection->prepare($sql);
                $this->setValidationToken($this->generateValidationToken());
                $validationToken = $this->getValidationToken();
                $password = $this->hashPassword($this->getPassword());
                $statement->bindParam(':username', $username, PDO::PARAM_STR);
                $statement->bindParam(':password', $password, PDO::PARAM_STR);
                $statement->bindParam(':accessLevel', $accessLevel, PDO::PARAM_INT);
                $statement->bindParam(':secretKey', $secretKey, PDO::PARAM_STR);
                $statement->bindParam(':encryptionKey', $encryptionKey, PDO::PARAM_STR);
                $statement->bindParam(':validationToken', $validationToken, PDO::PARAM_STR);
            } else {
                $sql .= "UPDATE account SET (username,password,access_level,secret_key,encryption_key) VALUES (:username,:password,:accessLevel,:secretKey,:encryptionKey) WHERE id = :id";
                $statement = $dbConnection->prepare($sql);
                $statement->bindParam(':id', $ID, PDO::PARAM_INT);
                $statement->bindParam(':username', $username, PDO::PARAM_STR);
                $statement->bindParam(':password', $password, PDO::PARAM_STR);
                $statement->bindParam(':accessLevel', $accessLevel, PDO::PARAM_INT);
                $statement->bindParam(':secretKey', $secretKey, PDO::PARAM_STR);
                $statement->bindParam(':encryptionKey', $encryptionKey, PDO::PARAM_STR);
            }

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            if ($success && $creation) {
                $this->sendUserInformation();
                $this->sendValidationMail();
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
     */
    private function sendUserInformation() {
        try {
            $password = $this->getPassword();
            $username = $this->getUsername();

            $host = $_SERVER['HTTP_HOST'];
            $hostAddress = 'https://' . $host;

            $header = 'From: no-reply@' . $host . "\r\n" .
                    'X-Sender: ' . $hostAddress . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

            $subject = 'Ihre Zugangsdaten zum Passwort-Tool';
            $message = 'Mit dieser E-Mail erhalten Sie Ihre Zugangsdaten zum Passwort-Tool. Nach erstmaligem Login müssen Sie einen neues Passwort vergeben.' . "\r\n";
            $message .= 'Benutzername: ' . $username . "\r\n";
            $message .= 'Passwort: ' . $password . "\r\n";

            mail($username, $subject, $message, $header);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function needPasswordChange($id) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $userID = filter_var($id, FILTER_VALIDATE_INT);

            $needChange = false;

            $statement = $dbConnection->prepare("SELECT first_login_password_changed FROM account WHERE id = :id");
            $statement->bindParam(":id", $userID, PDO::PARAM_INT);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $needChange = $object->first_login_password_changed != 1 ? true : false;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $needChange;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function requestNewPassword() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;

            if (!$this->exists()) {
                return false;
            }

            $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

            $generatedPassword = $this->generatePassword();
            $this->setPassword($generatedPassword);
            $password = $this->hashPassword($generatedPassword);
            $statement = $dbConnection->prepare("UPDATE account SET password = :password WHERE username = :username");
            $statement->bindParam(":username", $username, PDO::PARAM_STR);
            $statement->bindParam(":password", $password, PDO::PARAM_STR);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            if ($success === true) {
                $this->sendUserInformation();
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $name
     * @return boolean
     */
    public function updateValidationToken($name) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $username = filter_var($name, FILTER_VALIDATE_EMAIL);
            $validationToken = $this->generateValidationToken();
            $success = false;

            $statement = $dbConnection->prepare("UPDATE account SET validation_token = :token WHERE username = :username");
            $statement->bindParam(":username", $username, PDO::PARAM_INT);
            $statement->bindParam(":token", $validationToken, PDO::PARAM_INT);

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
     * @param type $id
     * @param type $value
     * @return boolean
     */
    public function updateAuthSetup($id, $value) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $userID = filter_var($id, FILTER_VALIDATE_INT);
            $isSetup = filter_var($value, FILTER_VALIDATE_INT);
            $success = false;

            $statement = $dbConnection->prepare("UPDATE account SET authenticator_is_setup = :isSetup WHERE id = :id");
            $statement->bindParam(":id", $userID, PDO::PARAM_INT);
            $statement->bindParam(":isSetup", $isSetup, PDO::PARAM_INT);

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
     */
    private function sendValidationMail() {
        try {
            $validationToken = $this->getValidationToken();
            $username = $this->getUsername();

            $host = $_SERVER['HTTP_HOST'];
            $hostAddress = 'https://' . $host;
            $validationURL = $hostAddress . '/validation?u=' . $username . '&' . 't=' . $validationToken;

            $header = 'From: no-reply@' . $host . "\r\n" .
                    'X-Sender: ' . $hostAddress . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

            $subject = 'Bestätigen Sie Ihren Zugang zum Passwort-Tool';
            $message = 'Bitte bestätigen Sie ihre E-Mail Adresse beim klick auf folgenden Link: ';
            $message .= $validationURL;

            mail($username, $subject, $message, $header);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $name
     * @return type
     */
    private function getValidationTokenByUsername($name) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $username = filter_var($name, FILTER_VALIDATE_EMAIL);
            $token = null;

            $statement = $dbConnection->prepare("SELECT validation_token FROM account WHERE username = :username");
            $statement->bindParam(":username", $username, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $token = $object->validation_token;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $token;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function querySecretKey($id) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $userID = filter_var($id, FILTER_VALIDATE_INT);
            $secretKey = null;

            $statement = $dbConnection->prepare("SELECT secret_key FROM account WHERE id = :userID");
            $statement->bindParam(":userID", $userID, PDO::PARAM_INT);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $secretKey = $object->secret_key;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $secretKey;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $name
     * @return boolean
     */
    private function activateAccount($name) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $username = filter_var($name, FILTER_VALIDATE_EMAIL);
            $success = false;

            $statement = $dbConnection->prepare("UPDATE account SET active = 1 WHERE username = :username");
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

    /**
     * 
     * @param type $mail
     * @param type $token
     * @return boolean
     */
    public function validate($mail, $token) {
        try {
            $success = false;
            $username = filter_var($mail, FILTER_VALIDATE_EMAIL);
            $validationToken = $token;

            if ($validationToken == null || empty($validationToken) || !isset($validationToken)) {
                return false;
            }

            $validationTokenSaved = $this->getValidationTokenByUsername($username);

            if ($validationToken == $validationTokenSaved) {
                $success = $this->activateAccount($username);
                $success = $this->updateValidationToken($username);
            }

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
     */
    public function generateProperties() {
        try {
            $this->setPassword($this->generatePassword());
            $this->setSecretKey($this->generateSecretKey());
            $this->setEncryptionKey($this->generateEncryptionKey());
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
    public function exists() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $exists = false;

            $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

            $statement = $dbConnection->prepare("SELECT username FROM account WHERE username = :username");
            $statement->bindParam(":username", $username, PDO::PARAM_STR);

            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $exists = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $exists;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @return type
     */
    public function authenticatorIsSetup() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $isSetup = false;

            $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);

            $statement = $dbConnection->prepare("SELECT authenticator_is_setup FROM account WHERE username = :username");
            $statement->bindParam(":username", $username, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {

                    $isSetup = $object->authenticator_is_setup == 1 ? true : false;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $isSetup;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    private function queryUsername($id) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $username = null;

            $ID = filter_var($id, FILTER_VALIDATE_INT);

            $statement = $dbConnection->prepare("SELECT username FROM account WHERE id = :ID");
            $statement->bindParam(":ID", $ID, PDO::PARAM_INT);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $username = filter_var($object->username, FILTER_VALIDATE_EMAIL);
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $username;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $id
     * @param type $savedUsername
     * @return type
     */
    public function queryPassword($id, $savedUsername) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $password = null;

            $name = filter_var($savedUsername, FILTER_VALIDATE_EMAIL);
            $ID = filter_var($id, FILTER_VALIDATE_INT);

            $statement = $dbConnection->prepare("SELECT password FROM account WHERE id = :ID AND username = :username");
            $statement->bindParam(":ID", $ID, PDO::PARAM_INT);
            $statement->bindParam(":username", $name, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $password = $object->password;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $password;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param type $id
     * @param type $username
     * @param type $val
     * @return boolean
     */
    private function updateFirstPasswordChange($id, $username, $val) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;

            $ID = filter_var($id, FILTER_VALIDATE_INT);
            $name = filter_var($username, FILTER_VALIDATE_EMAIL);
            $value = filter_var($val, FILTER_VALIDATE_INT);


            $password = $this->hashPassword($newPassword);
            $statement = $dbConnection->prepare("UPDATE account SET first_login_password_changed = :changed WHERE id = :ID AND username = :username");
            $statement->bindParam(":ID", $ID, PDO::PARAM_INT);
            $statement->bindParam(":username", $name, PDO::PARAM_STR);
            $statement->bindParam(":changed", $value, PDO::PARAM_INT);

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
     * @param type $id
     * @param type $username
     * @param type $oldPassword
     * @param type $newPassword
     * @return boolean
     */
    public function updatePassword($id, $username, $oldPassword, $newPassword) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;

            $ID = filter_var($id, FILTER_VALIDATE_INT);
            $savedUsername = $this->queryUsername($ID);
            $savedPassword = $this->queryPassword($ID, $savedUsername);


            if ($username == $savedUsername) {
                if (password_verify($oldPassword, $savedPassword)) {
                    $password = $this->hashPassword($newPassword);
                    $statement = $dbConnection->prepare("UPDATE account SET password = :password WHERE id = :ID AND username = :username");
                    $statement->bindParam(":ID", $ID, PDO::PARAM_INT);
                    $statement->bindParam(":username", $username, PDO::PARAM_STR);
                    $statement->bindParam(":password", $password, PDO::PARAM_STR);

                    if ($statement->execute()) {
                        if ($statement->rowCount() > 0) {
                            if ($this->updateFirstPasswordChange($ID, $username, 1)) {
                                $success = true;
                            }
                        }
                    }
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
     * @return boolean
     */
    public function load() {
        $dbConnection = $this->getDatabase()->openConnection();

        $success = false;

        $ID = $this->getID();

        $statement = $dbConnection->prepare("SELECT username,access_level FROM account WHERE id = :id");
        $statement->bindParam(':id', $ID, PDO::PARAM_INT);

        if ($statement->execute()) {

            if ($statement->rowCount > 0) {
                $success = true;
            }

            while ($object = $statement->fetchObject()) {
                $this->setUsername($object->username);
                $this->setAccessLevel($object->access_level);
            }
        }

        return $success;
    }

}
