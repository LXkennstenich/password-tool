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

ob_flush();
ob_start();

$request = json_decode($_POST['request']);

$action = $request->action;
$userID = base64_decode($request->uid);
$sessionToken = $encryption->decrypt($request->tk, $userID);
$sessionTimestamp = $encryption->decrypt($request->ts, $userID);
$sessionIpAddress = base64_decode($request->ipaddress);
$searchTerm = $request->searchTerm;

if ($action == 'View') {
    $file = VIEW_DIR . $request->file . '.view.php';
} else {
    $file = CONTROLLER_DIR . $action . 'Controller.php';
}

if (file_exists($file)) {
    if ($action == 'Login' || $action == 'NewPassword') {
        $file = CONTROLLER_DIR . $action . 'Controller.php';
        include_once $file;
    } else {
        if ($session->ajaxCheck($sessionToken, $sessionTimestamp, $sessionIpAddress, $userID)) {
            include_once $file;
        }
    }
}


