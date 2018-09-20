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

<div id="login-form" class="container-fluid span4 centered">
    <input type="email" class="honeypot-field row-fluid" id="honeypot" value="">
    <input type="email" id="username-input" class="row-fluid" placeholder="Benutzername">
    <input type="password" id="password-input" class="row-fluid" placeholder="Passwort">
    <a id="login-button" class="btn btn-primary">Login</a>
    <a class="forgot-password-link" href="/newpassword">Passwort vergessen ?</a>
    <?php include_once ELEMENTS_DIR . 'ajaxLoader.php'; ?>
</div>

<script src="/Js/login.min.js"></script>

