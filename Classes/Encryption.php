<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
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
     * @var string 
     */
    protected $username;

    /**
     *
     * @var string 
     */
    protected $encryptionKey;

    /**
     *
     * @var \Debug 
     */
    protected $debugger;

    public function __construct(\Database $database, int $userID, \Debug $debugger, string $username) {
        $this->setDatabase($database);
        $this->setDebugger($debugger);
        $this->setUserID($userID);
        $this->setUsername($username);
    }

    /**
     * 
     * @param string $username
     */
    public function setUsername(string $username) {
        $this->username = $username;
    }

    /**
     * 
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * 
     * @param string $encryptionKey
     */
    public function setEncryptionKey(string $encryptionKey) {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * 
     * @return string
     */
    public function getEncryptionKey(): string {
        return $this->encryptionKey;
    }

    /**
     * 
     * @return \Debug
     */
    private function getDebugger(): \Debug {
        return $this->debugger;
    }

    /**
     * 
     * @param \Debug $debugger
     */
    private function setDebugger(\Debug $debugger) {
        $this->debugger = $debugger;
    }

    /**
     * 
     * @param int $userID
     */
    public function setUserID(int $userID) {
        $this->user_id = $userID;
    }

    /**
     * 
     * @return int
     */
    public function getUserID(): int {
        return $this->user_id;
    }

    /**
     * 
     * @param Database $database
     */
    private function setDatabase(\Database $database) {
        $this->database = $database;
    }

    /**
     * 
     * @return Database
     */
    private function getDatabase(): \Database {
        return $this->database;
    }

    /**
     * 
     * @param int $userID
     * @return string
     */
    public function queryEncryptionKey(int $userID): string {
        try {
            if (!isset($this->encryptionKey)) {
                $dbConnection = $this->getDatabase()->openConnection();
                $encryptionKey = null;
                $username = $this->getUsername();

                $hash = md5($username);
                $file = KEY_DIR . $hash . '.key';
                $key = base64_decode(file_get_contents($file));
                $statement = $dbConnection->prepare("SELECT encryption_key FROM account WHERE id = :userID");
                $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

                if ($statement->execute()) {
                    while ($row = $statement->fetchObject()) {
                        $encryptionKey = base64_decode($row->encryption_key);
                    }
                }

                $this->getDatabase()->closeConnection($dbConnection);

                $this->setEncryptionKey($encryptionKey . $key);
            }

            return $this->getEncryptionKey();
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function getSystemEncryptionKey(): string {
        return base64_decode(file_get_contents(KEY_DIR . 'system.key'));
    }

    /**
     * 
     * @param string $stringToEncrypt
     * @param int $userID
     * @return string
     */
    public function encrypt(string $stringToEncrypt, int $userID): string {
        try {

            $encryptedData = null;

            $nonce = random_bytes(SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES);
            $ad = $nonce;
            $key = $this->queryEncryptionKey($userID);

            $encryptedData = sodium_crypto_aead_aes256gcm_encrypt($stringToEncrypt, $ad, $nonce, $key);
            $encryptedData .= '||' . base64_encode($nonce);


            return base64_encode($encryptedData);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param string $encryptedData
     * @param int $userID
     * @return string
     */
    public function decrypt(string $encryptedData, int $userID): string {
        try {
            $decryptedData = null;
            $encryptedData = base64_decode($encryptedData);

            $dataArray = explode('||', $encryptedData);
            $nonce = base64_decode($dataArray[1]);
            $ad = $nonce;
            $key = $this->queryEncryptionKey($userID);
            $encryptedString = $dataArray[0];
            $decryptedData = sodium_crypto_aead_aes256gcm_decrypt($encryptedString, $ad, $nonce, $key);


            return $decryptedData;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param string $dataToEncrypt
     * @return string | null
     */
    public function systemEncrypt(string $dataToEncrypt) {
        try {
            $nonce = random_bytes(SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES);
            $ad = $nonce;
            $key = $this->getSystemEncryptionKey();
            $encryptedData = sodium_crypto_aead_aes256gcm_encrypt($dataToEncrypt, $ad, $nonce, $key);
            $encryptedData .= '||' . base64_encode($nonce);


            return base64_encode($encryptedData);
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param string $dataToDecrypt
     * @return string | null
     */
    public function systemDecrypt(string $dataToDecrypt) {
        try {
            $encryptedData = base64_decode($dataToDecrypt);

            $dataArray = explode('||', $encryptedData);
            $nonce = base64_decode($dataArray[1]);
            $ad = $nonce;
            $key = $this->getSystemEncryptionKey();
            $encryptedString = $dataArray[0];
            $decryptedData = sodium_crypto_aead_aes256gcm_decrypt($encryptedString, $ad, $nonce, $key);


            return $decryptedData;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    /**
     * 
     * @param int $length
     * @param bool $lowerCharacters
     * @param bool $higherCharacters
     * @param bool $numeric
     * @param bool $specialChars
     * @return string
     */
    public function generatePassword(int $length, bool $lowerCharacters, bool $higherCharacters, bool $numeric, bool $specialChars): string {
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

}
