<?php
/* @var $factory Factory */
/* @var $session Session */
if (!defined('PASSTOOL')) {
    die();
}

$encryption = $factory->getEncryption();

if ($session->isAuthenticated()) {
    $factory->redirect('account');
}
?>

<div id="login-form">
    <input type="email" id="username-input" placeholder="Benutzername">
    <input type="email" id="honeypot">
    <input type="password" id="password-input">
    <a id="login-button" class="button">Login</a>
</div>
