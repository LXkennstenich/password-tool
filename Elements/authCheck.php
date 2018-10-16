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

switch ($page) {
    case 'login':
        if ($session->isAuthenticated() === true) {
            $factory->redirect('account');
        }

        if ($session->cookiesSet() !== true && $session->isAuthenticated() !== true && $session->isValid()) {
            $cookieTimestamp = $sessionTimestamp + (60 * 60 * 2);
            $cookieTimestampEncrypted = $encryption->systemEncrypt($cookieTimestamp);
            $cookieToken = $encryption->systemEncrypt($sessionToken);
            $sessionID = $_SESSION['PHPSESSID'];

            setcookie('PHPSESSID', $sessionID, 0, '/', $host, true, true);
            setcookie('TK', $cookieToken, 0, '/', $host, true, true);
            setcookie('TS', $cookieTimestampEncrypted, 0, '/', $host, true, true);
            $factory->redirect('login');
        }

        break;
    case 'newpassword':
        if ($session->isAuthenticated() === true) {
            $factory->redirect('account');
        }
        break;
    case 'authenticator':
        if ($session->isAuthenticated() !== true) {
            $factory->redirect('login');
        }

        if ($account->authenticatorIsSetup() === false) {
            $factory->redirect('authenticatorsetup');
        }
        break;
    case 'authenticatorsetup':
        if ($session->isAuthenticated() !== true) {
            $factory->redirect('login');
        }

        if ($account->authenticatorIsSetup() === true) {
            $factory->redirect('authenticator');
        }
        break;
    case 'account':
        if ($session->isAuthenticated() !== true) {
            $factory->redirect('login');
        }

        if ($session->needAuthenticator() !== false) {
            $factory->redirect('authenticator');
        }

        if ($account->needPasswordChange($sessionUID) === true) {
            $factory->redirect('updatepassword');
        }
        break;
    case 'logout':
        if ($session->isAuthenticated() !== true) {
            $factory->redirect('login');
        }

        if ($session->needAuthenticator() !== false) {
            $factory->redirect('authenticator');
        }

        if ($account->needPasswordChange($sessionUID) === true) {
            $factory->redirect('updatepassword');
        }
        break;
    case 'checkforupdate':
        if ($session->isAuthenticated() !== true) {
            $factory->redirect('login');
        }

        if ($session->needAuthenticator() !== false) {
            $factory->redirect('authenticator');
        }

        if ($account->needPasswordChange($sessionUID) === true) {
            $factory->redirect('updatepassword');
        }
        break;
    case 'settings':
        if ($session->isAuthenticated() !== true) {
            $factory->redirect('login');
        }

        if ($session->needAuthenticator() !== false) {
            $factory->redirect('authenticator');
        }

        if ($account->needPasswordChange($sessionUID) === true) {
            $factory->redirect('updatepassword');
        }
        break;
    case 'updatepassword':
        if ($session->isAuthenticated() !== true) {
            $factory->redirect('login');
        }

        if ($session->needAuthenticator() !== false) {
            $factory->redirect('authenticator');
        }
        break;
    case 'installation':
        if ($factory->isSystemInstalled() !== false) {
            $factory->redirect('login');
        }
        break;
}
