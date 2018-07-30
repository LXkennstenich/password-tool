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


if ($session->isAuthenticated() === true) {
    $factory->redirect('account');
}
?>

<div id="login-form">
    <input type="email" id="username-input" placeholder="Benutzername">
    <input type="email" id="honeypot">
    <input type="password" id="password-input">
    <a id="login-button" class="button">Login</a>
</div>
