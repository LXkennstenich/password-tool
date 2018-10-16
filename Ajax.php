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

    $request = json_decode($_POST['request']);
    $action = filter_var($request->action, FILTER_SANITIZE_STRING);
    $userID = filter_var($encryption->systemDecrypt($request->uid), FILTER_VALIDATE_INT);
    $sessionToken = filter_var($encryption->systemDecrypt($request->tk), FILTER_SANITIZE_STRING);
    $sessionTimestamp = filter_var($encryption->systemDecrypt($request->ts), FILTER_SANITIZE_STRING);
    $sessionIpAddress = $encryption->systemDecrypt($request->ipaddress);
    $searchTerm = filter_var($request->searchTerm, FILTER_SANITIZE_STRING);
    $userAgent = filter_var($encryption->systemDecrypt($request->userAgent), FILTER_SANITIZE_STRING);
    $accessLevel = filter_var($encryption->systemDecrypt($request->accessLevel), FILTER_VALIDATE_INT);

    if ($action == 'View') {
        $file = VIEW_DIR . $request->file . '.view.php';
    } else {
        $file = CONTROLLER_DIR . $action . 'Controller.php';
    }

    if (file_exists($file)) {
        if ($action == 'Login' || $action == 'NewPassword' || $action == 'ValidateAuth') {
            $file = CONTROLLER_DIR . $action . 'Controller.php';
            include_once $file;
        } else {
            if ($session->ajaxCheck($sessionToken, $sessionTimestamp, $sessionIpAddress, $userID, $userAgent, $accessLevel)) {
                include_once $file;
            }
        }
    }
} catch (Exception $ex) {
    $debugger->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
}



