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


if ($session->isAuthenticated() && $session->needAuthenticator() === false) {
    include_once ELEMENTS_DIR . 'navbar.php';
}
?>

<div id = "main">
    <?php
    include_once System::getPage($page);
    ?>
</div>

<?php
include_once ELEMENTS_DIR . 'ajaxLoader.php';

if ($session->isAuthenticated() && $session->needAuthenticator() === false) {
    include_once System::getView('newDataset');

    if ($sessionAccessLevel === SESSION_ADMIN) {
        include_once System::getView('newUser');
    }
}

include ELEMENTS_DIR . 'footer.php';
?>