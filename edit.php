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

if ($session->isAuthenticated() !== true) {
    $factory->redirect('login');
}

if ($session->needAuthenticator() !== false) {
    $factory->redirect('authenticator');
}

$datasetID = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

$dataset = $factory->getDataset($datasetID, $_SESSION['UID']);
?>

<div id="main">
    <div class="headline">
        <h1>Datensatz bearbeiten</h1>
    </div>

    <?php include_once VIEW_DIR . 'editDataset.view.php'; ?>
</div>



