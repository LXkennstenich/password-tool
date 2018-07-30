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
if (!defined('PASSTOOL')) {
    die();
}

$username = filter_var($request->username, FILTER_VALIDATE_EMAIL);
$password = $request->password;

$session->setUsername($username);
$session->setPassword($password);

$userID = $session->queryUserID();
$host = $request->host;
$userAgent = $request->userAgent;


$session->setIpaddress($sessionIpAddress);
$session->setUserID($userID);
$session->setHost($host);
$session->setUseragent($userAgent);

if ($session->validate()) {
    if ($session->startSession()) {
        echo "1";
    }
} else {
    echo "0";
}
