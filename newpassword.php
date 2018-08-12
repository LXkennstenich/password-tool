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
/* @var $account Account */
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
?>


<div id="main">
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
    </div>
</div>





