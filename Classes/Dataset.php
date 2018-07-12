<?php

/**
 * Description of Dataset
 *
 * @author alexw
 */
class Dataset {

    /**
     *
     * @var type 
     */
    protected $ID;

    /**
     *
     * @var type 
     */
    protected $title;

    /**
     *
     * @var type 
     */
    protected $dateCreated;

    /**
     *
     * @var type 
     */
    protected $dateEdited;

    /**
     *
     * @var type 
     */
    protected $login;

    /**
     *
     * @var type 
     */
    protected $password;

    /**
     *
     * @var type 
     */
    protected $url;

    /**
     *
     * @var type 
     */
    protected $project;

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    public function setDateEdited($dateEdited) {
        $this->dateEdited = $dateEdited;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setProject($project) {
        $this->project = $project;
    }

    public function getID() {
        return $this->ID;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getDateEdited() {
        return $this->dateEdited;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getProject() {
        return $this->project;
    }

}
