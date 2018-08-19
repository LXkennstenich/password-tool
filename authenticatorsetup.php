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
<?php include_once ELEMENTS_DIR . 'ajaxLoader.php'; ?>