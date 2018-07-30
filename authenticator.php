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
/* @var $account Account */
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

if ($account->authenticatorIsSetup() === false) {
    $factory->redirect('authenticatorsetup');
}


if ($session->needAuthenticator() === false) {
    $factory->redirect('account');
}
?>


<div class="authenticator-form">
    <label for="authenticator-code">Authenticator Code</label>
    <input type="text" id="authenticator-code">
    <input type="text" id="authenticator-code-honeypot">
    <a class="button" id="authenticator-button">Bestätigen</a>
</div>
