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

    public function __construct($database, $userID) {
        $this->setDatabase($database);
        $this->setUserID($userID);
    }

    /**
     * 
     * @param int $userID
     */
    private function setUserID($userID) {
        $this->user_id = $userID;
    }

    /**
     * 
     * @return int
     */
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

    private function getSystemEncryptionKey() {
        
    }

    private function getEncryptionKey($userID) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        } catch (PDOException $ex) {
            
        }
    }

    public function getCipherMode($userID) {
        try {
            $dbConnection = $this->getDatabase()->openConnection();
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        } catch (PDOException $ex) {
            
        }
    }

    public function encrypt($stringToEncrypt, $userID) {

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
    }

    public function decrypt($encryptedData, $userID) {
        $encryptionKey = $this->getEncryptionKey($userID);
        $password = substr(hash('sha256', $encryptionKey, true), 0, 32);
        $cipherMode = $this->getCipherMode($userID);
        $decryptedDataFinal = null;

        if ($encryptionKey !== null && $cipherMode !== null) {
            $dataArray = explode(':', $encryptedData);
            $encryptedPassword = base64_decode($dataArray[0]);
            $vektor = base64_decode($dataArray[1]);
            $decryptedDataFinal = openssl_decrypt($encryptedPassword, $cipherMode, $password, OPENSSL_RAW_DATA, $vektor);
        }

        return $decryptedDataFinal;
    }

    public function generatePassword($length, $lowerCharacters, $higherCharacters, $numeric, $specialChars) {
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
    }

    function getRealPasswordLength($encryptedPassword, $username) {
        $decryptedPassword = $this->decrypt($encryptedPassword, $username);
        $length = strlen($decryptedPassword);
        unset($decryptedPassword);
        return $length;
    }

}
