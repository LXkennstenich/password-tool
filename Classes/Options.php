<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */
/* @var $factory Factory */
/* @var $session Session */

class Options {

    protected $id;
    protected $user_id;
    protected $display_login;
    protected $use_two_factor;
    protected $email_notification_login;
    protected $email_notification_password_change;
    protected $database;
    protected $debugger;

    public function __construct($database, $debugger) {
        $this->setDatabase($database);
        $this->setDebugger($debugger);
    }

    private function setDatabase($database) {
        $this->database = $database;
    }

    private function setDebugger($debugger) {
        $this->debugger = $debugger;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function setUserID($user_id) {
        $this->user_id = $user_id;
    }

    public function setDisplayLogin($display_login) {
        $this->display_login = $display_login;
    }

    public function setUseTwoFactor($use_two_factor) {
        $this->$use_two_factor = $use_two_factor;
    }

    public function setEmailNotificationLogin($email_notification_login) {
        $this->email_notification_login = $email_notification_login;
    }

    public function setEmailNotificationPasswordChange($email_notification_password_change) {
        $this->email_notification_password_change = $email_notification_password_change;
    }

    /**
     * 
     * @return \Database
     */
    private function getDatabase() {
        return $this->database;
    }

    /**
     * 
     * @return \Debug
     */
    private function getDebugger() {
        return $this->debugger;
    }

    public function getID() {
        return $this->id;
    }

    public function getUserID() {
        return $this->user_id;
    }

    public function getDisplayLogin() {
        return $this->display_login;
    }

    public function getUgetwoFactor() {
        return $this->use_two_factor;
    }

    public function getEmailNotificationLogin() {
        return $this->email_notification_login;
    }

    public function getEmailNotificationPasswordChange() {
        return $this->email_notification_password_change;
    }

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

}
