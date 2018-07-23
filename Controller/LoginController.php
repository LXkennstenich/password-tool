<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
if (!defined('PASSTOOL')) {
    die();
}

$username = filter_var($request->username, FILTER_VALIDATE_EMAIL);
$password = filter_var($request->password, FILTER_SANITIZE_STRING);

$session->setUsername($username);
$session->setPassword($password);
$session->setIpaddress($sessionIpAddress);
if ($session->validate()) {
    echo "1";
} else {
    echo "0";
}
