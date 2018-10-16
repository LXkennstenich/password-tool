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
     * @var Mail
     */
    protected $mail;

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
    public function __construct($database, $debugger, $mail) {
        $this->setDatabase($database);
        $this->setDebugger($debugger);
        $this->setMail($mail);
    }

    /**
     * 
     * @param Mail $mail
     */
    public function setMail($mail) {
        $this->mail = $mail;
    }

    /**
     * 
     * @return Mail
     */
    public function getMail() {
        return $this->mail;
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
            $authenticator = new GoogleAuthenticator();
            return $authenticator->createSecret();
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
    public function generateEncryptionKey($username) {
        if (sodium_crypto_aead_aes256gcm_is_available()) {
            $key = sodium_crypto_aead_aes256gcm_keygen();
        } else {
            $key = sodium_crypto_aead_chacha20poly1305_keygen();
        }

        $length = strlen($key) / 2;
        $keyArray = str_split($key, $length);

        $key = base64_encode($keyArray[0]);
        $keyLocal = base64_encode($keyArray[1]);

        $hashUsername = md5($username);

        $file = KEY_DIR . $hashUsername . '.key';


        file_put_contents($file, $keyLocal);


        return $key;
    }

    /**
     * 
     * @param type $password
     * @return type
     */
    private function hashPassword($password) {
        try {
            return sodium_crypto_pwhash_str($password, SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE);
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
            $username = $this->getUsername();
            $password = $this->hashPassword($this->getPassword());
            $accessLevel = $this->getAccessLevel();
            $secretKey = $this->getSecretKey();
            $encryptionKey = $this->getEncryptionKey();

            $statement = $dbConnection->prepare("INSERT INTO account (username,password,access_level,secret_key,encryption_key,validation_token) VALUES (:username,:password,:accessLevel,:secretKey,:encryptionKey,:validationToken)");
            $this->setValidationToken($this->generateValidationToken());
            $validationToken = $this->getValidationToken();
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->bindParam(':password', $password, PDO::PARAM_STR);
            $statement->bindParam(':accessLevel', $accessLevel, PDO::PARAM_INT);
            $statement->bindParam(':secretKey', $secretKey, PDO::PARAM_STR);
            $statement->bindParam(':encryptionKey', $encryptionKey, PDO::PARAM_STR);
            $statement->bindParam(':validationToken', $validationToken, PDO::PARAM_STR);


            if ($statement->execute()) {
                if ($statement->rowCount() > 0) {
                    $success = true;
                }
            }

            if ($success) {
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

    public function updateEncryptionKey($userID) {
        $dbConnection = $this->getDatabase()->openConnection();

        $success = false;

        $encryptionKey = bin2hex($this->generateEncryptionKey($this->getUsername()));

        $statement = $dbConnection->prepare("UPDATE account set encryption_key = :encryptionKey WHERE id = :id");
        $statement->bindParam(":encryptionKey", $encryptionKey, PDO::PARAM_STR);
        $statement->bindParam(":id", $userID, PDO::PARAM_INT);

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
     */
    private function sendUserInformation() {
        try {
            $password = $this->getPassword();
            $username = $this->getUsername();

            $subject = 'Ihre Zugangsdaten zum Passwort-Tool';
            $message = 'Mit dieser E-Mail erhalten Sie Ihre Zugangsdaten zum Passwort-Tool. Nach erstmaligem Login müssen Sie einen neues Passwort vergeben.' . "\r\n";
            $message .= 'Benutzername: ' . $username . "\r\n";
            $message .= 'Passwort: ' . $password . "\r\n";

            $this->getMail()->sendMail($subject, $message, $username);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param int $userID
     * @return boolean
     */
    public function needPasswordChange($userID) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

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

            $username = $this->getUsername();

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
     * @param string $username
     * @return boolean
     */
    public function updateValidationToken($username) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();

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

            $success = false;

            $statement = $dbConnection->prepare("UPDATE account SET authenticator_is_setup = :isSetup WHERE id = :id");
            $statement->bindParam(":id", $id, PDO::PARAM_INT);
            $statement->bindParam(":isSetup", $value, PDO::PARAM_INT);

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


            $subject = 'Bestätigen Sie Ihren Zugang zum Passwort-Tool';
            $message = 'Bitte bestätigen Sie ihre E-Mail Adresse beim klick auf folgenden Link: ';
            $message .= $validationURL;

            $this->getMail()->sendMail($subject, $message, $username);
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

            $token = null;

            $statement = $dbConnection->prepare("SELECT validation_token FROM account WHERE username = :username");
            $statement->bindParam(":username", $name, PDO::PARAM_STR);

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

            $secretKey = null;

            $statement = $dbConnection->prepare("SELECT secret_key FROM account WHERE id = :userID");
            $statement->bindParam(":userID", $id, PDO::PARAM_INT);

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

            $success = false;

            $statement = $dbConnection->prepare("UPDATE account SET active = 1 WHERE username = :username");
            $statement->bindParam(":username", $name, PDO::PARAM_STR);

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
                $success = $this->activateAccount($username) && $this->updateValidationToken($username) ? true : false;
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
            $this->setEncryptionKey($this->generateEncryptionKey($this->getUsername()));
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

            $username = $this->getUsername();

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

            $username = $this->getUsername();

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

            $statement = $dbConnection->prepare("SELECT username FROM account WHERE id = :ID");
            $statement->bindParam(":ID", $id, PDO::PARAM_INT);

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

            $statement = $dbConnection->prepare("SELECT password FROM account WHERE id = :ID AND username = :username");
            $statement->bindParam(":ID", $id, PDO::PARAM_INT);
            $statement->bindParam(":username", $savedUsername, PDO::PARAM_STR);

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

            $statement = $dbConnection->prepare("UPDATE account SET first_login_password_changed = :changed WHERE id = :ID AND username = :username");
            $statement->bindParam(":ID", $id, PDO::PARAM_INT);
            $statement->bindParam(":username", $username, PDO::PARAM_STR);
            $statement->bindParam(":changed", $val, PDO::PARAM_INT);

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

            $savedUsername = $this->queryUsername($id);
            $savedPassword = $this->queryPassword($id, $savedUsername);


            if ($username == $savedUsername) {
                if (sodium_crypto_pwhash_str_verify($savedPassword, $oldPassword)) {
                    $password = $this->hashPassword($newPassword);
                    $statement = $dbConnection->prepare("UPDATE account SET password = :password WHERE id = :ID AND username = :username");
                    $statement->bindParam(":ID", $id, PDO::PARAM_INT);
                    $statement->bindParam(":username", $username, PDO::PARAM_STR);
                    $statement->bindParam(":password", $password, PDO::PARAM_STR);

                    if ($statement->execute()) {
                        if ($statement->rowCount() > 0) {
                            if ($this->updateFirstPasswordChange($id, $username, 1)) {
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
