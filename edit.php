<?php
/**
 * edit.php
 * Ansicht für das editieren eines Datensatzes
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
if (!defined('PASSTOOL')) {
    die();
}

if ($session->isAuthenticated() == false) {
    $factory->redirect('login');
}

include_once ELEMENTS_DIR . 'navbar.php';

$datasetID = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

$dataset = $factory->getDataset($datasetID);
?>

<div id="info">

</div>

<div id="main">
    <?php include_once VIEW_DIR . 'editDataset.view.php'; ?>
</div>



