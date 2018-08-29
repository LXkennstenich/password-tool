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
?>



<div class="forget-password-form form">

    <div class="row">
        <input type="text" class="honeypot-field" id="newPasswordHoneypot" value="">
    </div>

    <div class="row">
        <label for="usernamePasswordReset">Benutzername</label>
        <input type="text" value="" id="usernamePasswordReset" class="form-input">
    </div>

    <div class="row">
        <input type="button" class="button" id="requestNewPasswordButton" value="Neues Passwort anfordern">
    </div>

    <a class="button" href="/">Zurück zur Startseite</a>
</div>


<script src="/Js/newPassword.min.js"></script>





