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

try {
    $datasetID = filter_var($request->id, FILTER_VALIDATE_INT);
    $datasetTitle = filter_var($request->title, FILTER_SANITIZE_STRING);
    $datasetLogin = filter_var($request->login, FILTER_SANITIZE_STRING);
    $datasetPassword = $request->password;
    $datasetURL = filter_var($request->url, FILTER_SANITIZE_URL);
    $datasetProject = filter_var($request->project, FILTER_SANITIZE_STRING);
    $dataset = $factory->getDataset($datasetID, $userID);
    $dataset->setID($datasetID);
    $dataset->setUserID($userID);
    $dataset->setTitle($datasetTitle);
    $dataset->setLogin($datasetLogin);
    $dataset->setPassword($datasetPassword);
    $dataset->setUrl($datasetURL);
    $dataset->setProject($datasetProject);
    $dataset->encrypt();

    if ($dataset->update() === true) {
        echo "1";
    } else {
        echo "0";
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}


