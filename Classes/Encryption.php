<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */
class Encryption {

    /**
     *
     * @var Database 
     */
    protected $database;

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

    public function __construct($database, $userID, $debugger) {
        $this->setDatabase($database);
        $this->setDebugger($debugger);
        $this->setUserID($userID);
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
     * @param int $userID
     */
    public function setUserID($userID) {
        $this->user_id = $userID;
    }

    /**
     * 
     * @return int
     */
    public function getUserID() {
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

    private function getEncryptionKey($userID) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $user = $userID;
            $encryptionKey = null;
            $statement = $dbConnection->prepare("SELECT encryption_key FROM account WHERE id = :userID");
            $statement->bindParam(':userID', $user, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($row = $statement->fetchObject()) {
                    $encryptionKey = $row->encryption_key;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $encryptionKey;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getCipherMode($userID) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $user = $userID;
            $cipherMode = null;
            $statement = $dbConnection->prepare("SELECT cypher_mode FROM account WHERE id = :userID");
            $statement->bindParam(':userID', $user, PDO::PARAM_STR);

            if ($statement->execute()) {
                while ($row = $statement->fetchObject()) {
                    $cipherMode = $row->cypher_mode;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $cipherMode;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function encrypt($stringToEncrypt, $userID) {
        try {
            $encryptionKey = $this->getEncryptionKey($userID);
            $password = substr(hash('sha256', $encryptionKey, true), 0, 32);
            $cipherMode = $this->getCipherMode($userID);
            $encrypteddataFinal = null;



            if ($encryptionKey !== null && $cipherMode !== null) {
                $cipherLength = openssl_cipher_iv_length($cipherMode);
                $vektor = openssl_random_pseudo_bytes($cipherLength);
                $encryptedData = base64_encode(openssl_encrypt($stringToEncrypt, $cipherMode, $password, OPENSSL_RAW_DATA, $vektor));
                $encrypteddataFinal = $encryptedData . ':' . base64_encode($vektor);
            }

            return $encrypteddataFinal;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function decrypt($encryptedData, $userID) {
        try {
            $encryptionKey = $this->getEncryptionKey($userID);
            $password = substr(hash('sha256', $encryptionKey, true), 0, 32);
            $cipherMode = $this->getCipherMode($userID);
            $decryptedDataFinal = null;

            if ($encryptionKey != null && $cipherMode != null) {
                $dataArray = explode(':', $encryptedData);
                $encryptedPassword = base64_decode($dataArray[0]);
                $vektor = base64_decode($dataArray[1]);
                $decryptedDataFinal = openssl_decrypt($encryptedPassword, $cipherMode, $password, OPENSSL_RAW_DATA, $vektor);
            }

            return $decryptedDataFinal;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function generatePassword($length, $lowerCharacters, $higherCharacters, $numeric, $specialChars) {
        try {
            $passwortLength = $length;

            $allowedCharacters = '';
            $password = '';

            if ($lowerCharacters) {
                $allowedCharacters .= 'abcdefghijklmnopqrstuvwxyz';
            }

            if ($higherCharacters) {
                $allowedCharacters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }

            if ($numeric) {
                $allowedCharacters .= '1234567890';
            }

            if ($specialChars) {
                $allowedCharacters .= '!?@(){}[]\/=$%&#-+.,_';
            }

            for ($i = 0; $i < $passwortLength; $i++) {
                $allowedCharactersLength = strlen($allowedCharacters);
                $random = random_int(0, $allowedCharactersLength);
                $password .= mb_substr($allowedCharacters, $random, 1);
            }

            return $password;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    function getRealPasswordLength($encryptedPassword, $username) {
        try {
            $decryptedPassword = $this->decrypt($encryptedPassword, $username);
            $length = strlen($decryptedPassword);
            unset($decryptedPassword);
            return $length;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

}
