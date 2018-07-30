<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */
if (!defined('PASSTOOL')) {
    die();
}
/* @var $factory Factory */
/* @var $session Session */
/* @var $dataset Dataset */
$dataset = $factory->createDataset();

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