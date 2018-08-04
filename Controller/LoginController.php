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

$time = microtime(true);
$timeRequest = (float) $request->timestamp;

//millisekunden
$timeCalculated = $time - $timeRequest;


if ($timeCalculated > 500 || $timeCalculated < 1) {
    echo 'Fehler bei der Anfrage bitte Seite neu laden';
    die();
}


$debugger = $factory->getDebugger();
$honeypot = $request->honeypot;

if (!empty($honeypot) || $honeypot != '') {
    echo 'Benutzername oder Passwort ist nicht korrekt';
    die();
}

$account = $factory->getAccount();
$username = filter_var($request->username, FILTER_VALIDATE_EMAIL);
$account->setUsername($username);

if ($account->exists() !== true) {
    echo 'Benutzername oder Passwort ist nicht korrekt';
    die();
}

$password = $request->password;
$lockTime = $session->getLockTime($username);
$loginAttempts = $session->getLoginAttempts($username);

if ($lockTime <= time() && $session->isAccountLocked($username) === true) {
    if ($session->unlockAccount($username)) {
        $lockTime = $session->getLockTime($username);
        $loginAttempts = $session->getLoginAttempts($username);
    }
}

if ($lockTime > time() || $session->isAccountLocked($username) !== false) {
    echo 'Zugang vorübergehend gesperrt';
    $session->countLoginAttempt($username);
    die();
}

$session->setUsername($username);
$session->setPassword($password);

$host = $request->host;
$userAgent = $request->userAgent;

$session->setIpaddress($sessionIpAddress);
$session->setUserID($factory->getUserID($username));
$session->setHost($host);
$session->setUseragent($userAgent);

if ($session->validate()) {
    if ($session->startSession()) {
        echo "1";
    }
} else {
    if ($loginAttempts >= 3) {
        if ($session->isAccountLocked($username) !== true) {
            if ($session->lockAccount($username)) {
                $session->sendLockMail($username);
            }
        }
    }

    $session->countLoginAttempt($username);

    echo "Benutzername oder Passwort ist nicht korrekt";
}

