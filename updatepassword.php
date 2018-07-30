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

if ($session->isAuthenticated() !== true) {
    $factory->redirect('login');
}

if ($session->needAuthenticator() !== false) {
    $factory->redirect('authenticator');
}
?>


<div id="first-time-password-change">
    <input type="email" id="username-input" autocomplete="off" placeholder="Benutzername">
    <input type="email" id="honeypot">
    <input type="password" id="password-input-old" autocomplete="off" placeholder="Altes Passwort">
    <input type="password" id="password-input-new" autocomplete="off" placeholder="Neues Passwort">
    <a id="update-password-button" class="button">Passwort aktualisieren</a>
</div>





