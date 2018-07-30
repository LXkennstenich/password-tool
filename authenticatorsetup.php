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

if ($session->needAuthenticator() === false) {
    $factory->redirect('account');
}

if ($account->authenticatorIsSetup() === true) {
    $factory->redirect('authenticator');
}
?>



<div id="authenticator-setup">
    <p for="qr-code">Scannen Sie diesen QR-Code mit Ihrer Google-Authenticator-App</p>
    <?php
    try {
        echo '<img id="qr-code" src="' . \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($sessionUsername, $account->querySecretKey($sessionUID), $host) . '" width="200" height="200" alt="">';
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
    ?>
    <a class="button" id="validate-auth-setup-button">Bestätigen</a>
</div>