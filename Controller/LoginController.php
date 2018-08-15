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

$debugger = $factory->getDebugger();

try {
    $time = microtime(true);
    $timeRequest = (float) $request->timestamp;

    //millisekunden
    $timeCalculated = $time - $timeRequest;


    if ($timeCalculated > 500 || $timeCalculated < 1) {
        $debugger->log('Zeitüberschreitung Formular || Datei: ' . __FILE__ . ' Zeit: ' . $timeCalculated);
        exit('Fehler bei der Anfrage bitte Seite neu laden');
    }

    $honeypot = $request->honeypot;

    if ($honeypot != '') {
        $debugger->log('Bot detected || Useragent: ' . $request->userAgent . ' IP-Adresse: ' . $sessionIpAddress . ' Benutzername: ' . $username . ' Honeypot: ' . $honeypot);
        exit('Benutzername oder Passwort ist nicht korrekt');
    }


    $account = $factory->getAccount();
    $username = filter_var($request->username, FILTER_VALIDATE_EMAIL);
    $account->setUsername($username);

    if ($account->exists() !== true) {
        $debugger->log('Loginversuch mit dem nicht existierenden Benutzernamen: ' . $username);
        exit('Benutzername oder Passwort ist nicht korrekt');
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
        $session->countLoginAttempt($username);
        $debugger->log('Zugang für Benutzer: ' . $username . ' vorübergehend gesperrt.');
        exit('Zugang vorübergehend gesperrt');
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
            $debugger->log('Benutzer ' . $username . ' mit der IP-Adresse: ' . $sessionIpAddress . ' eingeloggt.');
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

        $debugger->log('Benutzer ' . $username . ' mit der IP-Adresse: ' . $sessionIpAddress . ' hat ein falsches Passwort eingegeben.');

        echo "Benutzername oder Passwort ist nicht korrekt";
    }
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}



