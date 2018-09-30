<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ------------------------------------------------- Verfügbare Objekte / Variablen ------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */

/* @var $factory Factory */
/* @var $session Session */
/* @var $system System */
/* @var $account Account */
/* @var $encryption Encryption */
/* @var $options Options */
/* @var $dataset Dataset */
/* @var $sessionUID string */
/* @var $sessionUsername string */
/* @var $sessionIP string */
/* @var $sessionToken string */
/* @var $sessionTimestamp string */
/* @var $sessionAccessLevel string */
/* @var $searchTerm string */
/* @var $isSearch string */
/* @var $host string */
/* @var $userAgent string */

/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
if (!defined('PASSTOOL')) {
    die();
}

$dataset = $factory->createDataset();

$title = $request->title;
$login = $request->login;
$password = $request->password;
$url = $request->url;
$project = $request->project;

if ($title == '') {
    exit('Kein Titel angegeben. Datensatz wurde nicht erstellt');
}

if ($login == '') {
    exit('Kein Login angegeben. Datensatz wurde nicht erstellt');
}

$dataset->setUserID($userID);
$dataset->setTitle($title);
$dataset->setLogin($login);
$dataset->setPassword($password);
$dataset->setUrl($url);
$dataset->setProject($project);

$dataset->encrypt();

if ($dataset->insert()) {
    echo "1";
    apcu_clear_cache();
} else {
    echo "0";
}