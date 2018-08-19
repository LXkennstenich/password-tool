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

$debugger = $factory->getDebugger();
$account = $factory->getAccount();
$account->setUsername($username);
$host = $request->host;
$userAgent = $request->userAgent;

try {
    $time = microtime(true);
    $timeRequest = (float) $request->timestamp;

    $timeCalculated = $time - $timeRequest;

    if ($account->exists() !== true) {
        $message = 'Loginversuch mit dem nicht existierenden Benutzernamen: ' . $username;
        $debugger->log($message);
        exit('Benutzername oder Passwort ist nicht korrekt');
    }

    if ($timeCalculated > 500 || $timeCalculated < 1) {
        $message = 'Zeitüberschreitung Formular || Datei: ' . __FILE__ . ' Zeit: ' . $timeCalculated;
        $debugger->log($message);
        $system->sendMail($message, "Zeitüberschreitung bei Login-Versuch Password-Tool", $username, $host);
        exit('Fehler bei der Anfrage bitte Seite neu laden');
    }

    $honeypot = $request->honeypot;

    if ($honeypot != '') {
        $message = 'Bot detected || Useragent: ' . $request->userAgent . ' IP-Adresse: ' . $sessionIpAddress . ' Benutzername: ' . $username . ' Honeypot: ' . $honeypot;
        $debugger->log($message);
        $system->sendMail($message, "Bot-Detected Password-Tool", $username, $host);
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
    $session->setIpaddress($sessionIpAddress);
    $session->setUserID($factory->getUserID($username));
    $session->setHost($host);
    $session->setUseragent($userAgent);

    if ($session->validate()) {
        if ($session->startSession()) {
            $message = 'Benutzer ' . $username . ' mit der IP-Adresse: ' . $sessionIpAddress . ' eingeloggt.';
            $debugger->log($message);
            $system->sendMail($message, "Login-Vorgang Password-Tool", $username, $host);
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

        $message = 'Benutzer ' . $username . ' mit der IP-Adresse: ' . $sessionIpAddress . ' hat ein falsches Passwort eingegeben.';
        $debugger->log($message);
        $system->sendMail($message, "Fehlgeschlagener Login-Vorgang Password-Tool", $username, $host);
        echo "Benutzername oder Passwort ist nicht korrekt";
    }
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}



