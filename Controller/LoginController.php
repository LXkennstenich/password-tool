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

$options = $factory->getOptions();
$options->setUserID($userID);
$options->load();
$emailNotificationLoginFailed = $options->getEmailNotificationLoginFailed();
$emailNotificationLogin = $options->getEmailNotificationLogin();
$debugger = $factory->getDebugger();
$username = filter_var($request->username, FILTER_VALIDATE_EMAIL);

try {
    $time = microtime(true);
    $timeRequest = (float) $request->timestamp;

    $timeCalculated = $time - $timeRequest;

    if ($timeCalculated > 500 || $timeCalculated < 1) {
        $message = 'Zeitüberschreitung Formular || Datei: ' . __FILE__ . ' Zeit: ' . $timeCalculated;
        $debugger->log($message);

        if ($emailNotificationLoginFailed != false) {
            $system->sendMail($message, "Zeitüberschreitung bei Login-Versuch", $username, $session->getHost());
        }

        exit('Fehler bei der Anfrage bitte Seite neu laden');
    }

    $honeypot = $request->honeypot;

    if ($honeypot != '') {
        $debugger->log('Bot detected || Useragent: ' . $request->userAgent . ' IP-Adresse: ' . $sessionIpAddress . ' Benutzername: ' . $username . ' Honeypot: ' . $honeypot);
        exit('Benutzername oder Passwort ist nicht korrekt');
    }


    $account = $factory->getAccount();

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
            $message = 'Benutzer ' . $username . ' mit der IP-Adresse: ' . $sessionIpAddress . ' eingeloggt.';
            $debugger->log($message);

            if ($emailNotificationLogin != false) {
                $system->sendMail($message, 'Login-Vorgang Password-Tool', $username, $host);
            }

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

        if ($emailNotificationLoginFailed != false) {
            $system->sendMail($message, 'Fehlgeschlagener Login-Vorgang Password-Tool', $username, $host);
        }

        echo "Benutzername oder Passwort ist nicht korrekt";
    }
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}



