<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Options extends Item {

    /**
     * Konstruktor
     * @param \Database $database
     * @param \Debug $debugger
     */
    public function __construct($database, $debugger) {
        parent::__construct(strtolower(__CLASS__));
        $this->setDatabase($database);
        $this->setDebugger($debugger);
    }

    /**
     * ID-Setter
     * @param int $id
     */
    public function setID($id) {
        $this->data['id'] = $id;
    }

    /**
     * User-ID-Setter
     * @param int $user_id
     */
    public function setUserID($user_id) {
        $this->data['user_id'] = $user_id;
    }

    /**
     * Display-Login-Setter
     * @param boolean $display_login
     */
    public function setDisplayLogin($display_login) {
        $this->data['display_login'] = $display_login;
    }

    /**
     * E-Mail-Notification-Setter
     * @param boolean $email_notification_login
     */
    public function setEmailNotificationLogin($email_notification_login) {
        $this->data['email_notification_login'] = $email_notification_login;
    }

    /**
     * E-Mail-Notification-Password-Change-Setter
     * @param boolean $email_notification_password_change
     */
    public function setEmailNotificationPasswordChange($email_notification_password_change) {
        $this->data['email_notification_password_change'] = $email_notification_password_change;
    }

    /**
     * 
     * @param type $email_notification_login_failed
     */
    public function setEmailNotificationLoginFailed($email_notification_login_failed) {
        $this->data['email_notification_login_failed'] = $email_notification_login_failed;
    }

    /**
     * 
     * @return int
     */
    public function getID() {
        return $this->data['id'];
    }

    /**
     * 
     * @return int
     */
    public function getUserID() {
        return $this->data['user_id'];
    }

    /**
     * 
     * @return boolean
     */
    public function getDisplayLogin() {
        return $this->data['display_login'];
    }

    /**
     * 
     * @return boolean
     */
    public function getEmailNotificationLogin() {
        return $this->data['email_notification_login'];
    }

    /**
     * 
     * @return boolean
     */
    public function getEmailNotificationPasswordChange() {
        return $this->data['email_notification_password_change'];
    }

    /**
     * 
     * @return type
     */
    public function getEmailNotificationLoginFailed() {
        return $this->data['email_notification_login_failed'];
    }

}
