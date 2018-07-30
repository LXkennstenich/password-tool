<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */
class Account {

    protected $database;
    protected $id;
    protected $username;
    protected $password;
    protected $last_login;
    protected $access_level;
    protected $secret_key;
    protected $encryption_key;
    protected $validation_token;
    protected $cipherMode;
    protected $first_login_password_changed;
    protected $authenticator_is_setup;
    protected $debugger;

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

    public function setValidationToken($token) {
        $this->validation_token = $token;
    }

    public function getValidationToken() {
        return $this->validation_token;
    }

    public function setID($ID) {
        $this->id = $ID;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setLastLogin($lastLogin) {
        $this->last_login = $lastLogin;
    }

    public function setAccessLevel($accessLevel) {
        $this->access_level = $accessLevel;
    }

    public function setSecretKey($secretKey) {
        $this->secret_key = $secretKey;
    }

    public function setEncryptionKey($encryptionKey) {
        $this->encryption_key = $encryptionKey;
    }

    public function getID() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getLastLogin() {
        return $this->last_login;
    }

    public function getAccessLevel() {
        return $this->access_level;
    }

    public function getSecretKey() {
        return $this->secret_key;
    }

    public function getEncryptionKey() {
        return $this->encryption_key;
    }

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

    private function generateValidationToken() {
        return bin2hex(openssl_random_pseudo_bytes(random_int(128, 256)));
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function save() {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

            $success = false;
            $creation = false;
            $ID = filter_var($this->getID(), FILTER_VALIDATE_INT);
            $username = filter_var($this->getUsername(), FILTER_VALIDATE_EMAIL);
            $password = $this->getPassword();
            $accessLevel = filter_var($this->getAccessLevel(), FILTER_VALIDATE_INT);
            $secretKey = filter_var($this->getSecretKey(), FILTER_SANITIZE_STRING);
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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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
            }

            return $success;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function generateProperties() {
        try {
            $this->setPassword($this->generatePassword());
            $this->setSecretKey($this->generateSecretKey());
            $this->setEncryptionKey($this->generateEncryptionKey());
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

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

            $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

}
