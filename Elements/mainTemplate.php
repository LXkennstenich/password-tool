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

include_once ELEMENTS_DIR . 'header.php';

$sessionAuthenticated = $session->isAuthenticated() && $session->needAuthenticator() === false ? true : false;

if ($sessionAuthenticated === true) {
    include_once ELEMENTS_DIR . 'navbar.php';
}

include_once ELEMENTS_DIR . 'JsGlobals.php';
?>

<div id = "main" class="container-fluid">
    <?php
    if ($page != 'login') {
        include_once ELEMENTS_DIR . 'ajaxLoader.php';
    }

    include_once System::getPage($page);
    ?>
</div>



<?php
if ($sessionAuthenticated === true) {

    include_once System::getView('newDataset');

    if ($sessionAccessLevel == SESSION_ADMIN) {
        include_once System::getView('newUser');
    }
}

include ELEMENTS_DIR . 'footer.php';
