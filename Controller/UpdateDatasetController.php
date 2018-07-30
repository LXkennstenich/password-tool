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
/* @var $account Account */
/* @var $dataset Dataset */
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
    $dataset = $factory->createDataset();
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


