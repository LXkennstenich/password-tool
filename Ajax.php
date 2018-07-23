<?php

/**
 * Ajax.php 
 * Nimmt Ajax anfragen entgegen und wertet diese aus
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
if (!defined('PASSTOOL')) {
    define('PASSTOOL', true);
}

if (session_status() == PHP_SESSION_NONE) {
    session_save_path('/tmp');
    session_start();
}

include_once 'init.php';

$request = json_decode($_POST['request']);

$action = $request->action;
$sessionToken = base64_decode($request->tk);
$sessionTimestamp = base64_decode($request->ts);
$sessionIpAddress = base64_decode($request->ipaddress);


if ($session->ajaxCheck($sessionToken, $sessionTimestamp, $sessionIpAddress)) {

    if ($action == 'View') {
        $file = VIEW_DIR . $request->file . '.view.php';
    } else {
        $file = CONTROLLER_DIR . $action . 'Controller.php';
    }

    if (file_exists($file)) {
        include $file;
    }
} else {
    include CONTROLLER_DIR . 'LoginController.php';
}



