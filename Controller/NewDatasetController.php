<?php

/* @var $factory Factory */
/* @var $session Session */
if (!defined('PASSTOOL')) {
    die();
}

$dataset = $factory->createDataset();

$userID = $request->userID;
$title = $request->title;
$login = $request->login;
$password = $request->password;
$url = $request->url;
$project = $request->project;

$dataset->setUserID($userID);
$dataset->setTitle($title);
$dataset->setLogin($login);
$dataset->setPassword($password);
$dataset->setUrl($url);
$dataset->setProject($project);

$dataset->encrypt();

if ($dataset->insert()) {
    echo "1";
} else {
    echo "0";
}