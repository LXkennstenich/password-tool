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
<?php include_once ELEMENTS_DIR . 'ajaxLoader.php'; ?>
