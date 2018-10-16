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
    public function setID(int $id) {
        $this->data['id'] = $id;
    }

    /**
     * User-ID-Setter
     * @param int $user_id
     */
    public function setUserID(int $user_id) {
        $this->data['user_id'] = $user_id;
    }

    /**
     * Display-Login-Setter
     * @param boolean $display_login
     */
    public function setDisplayLogin(int $display_login) {
        $this->data['display_login'] = boolval($display_login);
    }

    /**
     * E-Mail-Notification-Setter
     * @param boolean $email_notification_login
     */
    public function setEmailNotificationLogin(int $email_notification_login) {
        $this->data['email_notification_login'] = boolval($email_notification_login);
    }

    /**
     * E-Mail-Notification-Password-Change-Setter
     * @param boolean $email_notification_password_change
     */
    public function setEmailNotificationPasswordChange(int $email_notification_password_change) {
        $this->data['email_notification_password_change'] = boolval($email_notification_password_change);
    }

    /**
     * 
     * @return int
     */
    public function getID(): int {
        return $this->data['id'];
    }

    /**
     * 
     * @return int
     */
    public function getUserID(): int {
        return $this->data['user_id'];
    }

    /**
     * 
     * @return boolean
     */
    public function getDisplayLogin(): bool {
        return $this->data['display_login'];
    }

    /**
     * 
     * @return boolean
     */
    public function getEmailNotificationLogin(): bool {
        return $this->data['email_notification_login'];
    }

    /**
     * 
     * @return boolean
     */
    public function getEmailNotificationPasswordChange(): bool {
        return $this->data['email_notification_password_change'];
    }

}
