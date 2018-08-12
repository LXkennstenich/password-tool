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
/* @var $encryption Encryption */
/* @var $sessionUID int */
/* @var $sessionUsername string */
/* @var $sessionIP string */
/* @var $sessionToken string */
/* @var $sessionTimestamp int */
/* @var $searchTerm string */
/* @var $host string */
/* @var $userAgent string */
if (!defined('PASSTOOL')) {
    die();
}


$request = json_decode($_POST['request']);

$action = $request->action;
$userID = base64_decode($request->uid);
$sessionToken = $encryption->decrypt($request->tk, $userID);
$sessionTimestamp = $encryption->decrypt($request->ts, $userID);
$sessionIpAddress = base64_decode($request->ipaddress);
$searchTerm = $request->searchTerm;

if ($session->ajaxCheck($sessionToken, $sessionTimestamp, $sessionIpAddress, $userID)) {

    if ($action == 'View') {
        $file = VIEW_DIR . $request->file . '.view.php';
    } else {
        $file = CONTROLLER_DIR . $action . 'Controller.php';
    }

    if (file_exists($file)) {
        include $file;
    }
} else {
    $file = null;

    if ($action == 'Login' || $action == 'NewPassword') {
        $file = CONTROLLER_DIR . $action . 'Controller.php';
    }

    if (file_exists($file)) {
        include $file;
    }
}



