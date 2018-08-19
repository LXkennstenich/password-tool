<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Options {

    /**
     * Die ID der Option
     * @var int 
     */
    protected $id;

    /**
     * Die entsprechende User-ID
     * @var int 
     */
    protected $user_id;

    /**
     * Soll der Benutzername nach dem Login angezeigt werden?
     * @var boolean 
     */
    protected $display_login;

    /**
     * Soll 2-Faktor-Authentifizierung verwendet werden?
     * @var boolean 
     */
    protected $use_two_factor;

    /**
     * E-Mail Benachrichtigung bei Login-Vorgang ?
     * @var boolean 
     */
    protected $email_notification_login;

    /**
     * E-Mail Benachrichtigung bei Passwortänderung ?
     * @var boolean 
     */
    protected $email_notification_password_change;

    /**
     *
     * @var boolean 
     */
    protected $email_notifictaion_login_failed;

    /**
     * Database Objekt
     * @var \Database 
     */
    protected $database;

    /**
     * Debug Objekt
     * @var \Debug 
     */
    protected $debugger;

    /**
     * Konstruktor
     * @param \Database $database
     * @param \Debug $debugger
     */
    public function __construct($database, $debugger) {
        $this->setDatabase($database);
        $this->setDebugger($debugger);
    }

    /**
     * Database-Setter
     * @param \Database $database
     */
    private function setDatabase($database) {
        $this->database = $database;
    }

    /**
     * Debug-Setter
     * @param \Debug $debugger
     */
    private function setDebugger($debugger) {
        $this->debugger = $debugger;
    }

    /**
     * ID-Setter
     * @param int $id
     */
    public function setID($id) {
        $this->id = $id;
    }

    /**
     * User-ID-Setter
     * @param int $user_id
     */
    public function setUserID($user_id) {
        $this->user_id = $user_id;
    }

    /**
     * Display-Login-Setter
     * @param boolean $display_login
     */
    public function setDisplayLogin($display_login) {
        $this->display_login = $display_login;
    }

    /**
     * Use-Two-Factor-Setter
     * @param boolean $use_two_factor
     */
    public function setUseTwoFactor($use_two_factor) {
        $this->$use_two_factor = $use_two_factor;
    }

    /**
     * E-Mail-Notification-Setter
     * @param boolean $email_notification_login
     */
    public function setEmailNotificationLogin($email_notification_login) {
        $this->email_notification_login = $email_notification_login;
    }

    /**
     * E-Mail-Notification-Password-Change-Setter
     * @param boolean $email_notification_password_change
     */
    public function setEmailNotificationPasswordChange($email_notification_password_change) {
        $this->email_notification_password_change = $email_notification_password_change;
    }

    public function setEmailNotificationLoginFailed($email_notification_login_failed) {
        $this->email_notifictaion_login_failed = $email_notification_login_failed;
    }

    public function setDefault($user_id = null) {

        $this->setDisplayLogin(true);
        $this->setEmailNotificationLogin(true);
        $this->setEmailNotificationLoginFailed(true);
        $this->setEmailNotificationPasswordChange(true);
        $this->setUseTwoFactor(true);

        if ($user_id !== null) {
            $userID = filter_var($user_id, FILTER_VALIDATE_INT);
            $this->setUserID($userID);
        }
    }

    /**
     * Database-Getter
     * @return \Database
     */
    private function getDatabase() {
        return $this->database;
    }

    /**
     * Debug-Getter
     * @return \Debug
     */
    private function getDebugger() {
        return $this->debugger;
    }

    /**
     * ID-Getter
     * @return int
     */
    public function getID() {
        return $this->id;
    }

    /**
     * User-ID-Getter
     * @return int
     */
    public function getUserID() {
        return $this->user_id;
    }

    /**
     * Display-Login-Getter
     * @return boolean
     */
    public function getDisplayLogin() {
        return $this->display_login;
    }

    /**
     * Use-Two-Factor-Getter
     * @return boolean
     */
    public function getUsetwoFactor() {
        return $this->use_two_factor;
    }

    /**
     * E-Mail-Notification-Login-Getter
     * @return boolean
     */
    public function getEmailNotificationLogin() {
        return $this->email_notification_login;
    }

    /**
     * E-Mail-Notification-Password-Change-Getter
     * @return boolean
     */
    public function getEmailNotificationPasswordChange() {
        return $this->email_notification_password_change;
    }

    public function getEmailNotificationLoginFailed() {
        return $this->email_notifictaion_login_failed;
    }

    /**
     * Lädt das Objekt anhand der gesetzten ID und User-ID aus der Datenbank
     * @return boolean
     */
    public function load() {
        try {
            $userID = filter_var($this->getUserID(), FILTER_SANITIZE_NUMBER_INT);
            $loaded = false;

            $dbConnection = $this->getDatabase()->openConnection();
            $statement = $dbConnection->prepare("SELECT id,user_id,display_login,use_two_factor,email_notification_login,email_notification_password_change WHERE user_id = :userID");
            $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

            if ($statement->execute()) {
                while ($object = $statement->fetchObject()) {
                    $this->setID(filter_var($object->id, FILTER_SANITIZE_NUMBER_INT));
                    $this->setUserID(filter_var($object->user_id, FILTER_SANITIZE_NUMBER_INT));
                    $this->setDisplayLogin(boolval($object->display_login));
                    $this->setUseTwoFactor(boolval($object->use_two_factor));
                    $this->setEmailNotificationLogin(boolval($object->email_notification_login));
                    $this->setEmailNotificationPasswordChange(boolval($object->email_notification_password_change));
                }

                if ($statement->rowCount() > 0) {
                    $loaded = true;
                }
            }

            $this->getDatabase()->closeConnection($dbConnection);

            return $loaded;
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

    public function update() {
        
    }

    public function insert() {
        
    }

    public function exists() {
        
    }

}
