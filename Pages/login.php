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

<div class="logo-container">
    <img src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/Icons/logo-weese.png' ?>" width="300px">
</div>

<div id="login-form" class="form">
    <input type="email" class="honeypot-field" id="honeypot" value="">
    <input type="email" id="username-input" placeholder="Benutzername">
    <input type="password" id="password-input" placeholder="Passwort">
    <a id="login-button" class="button">Login</a>
    <a class="forgot-password-link" href="/newpassword">Passwort vergessen ?</a>
</div>

<script src="/Js/login.min.js"></script>

