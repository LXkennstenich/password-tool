
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

$dataset->decrypt();
?>

<div id="editDataset">
    <input type="hidden" id="edit-dataset-id" value="<?php echo $dataset->getID(); ?>" >
    <div class="row">
        <label for="datasetTitle">Titel</label>
        <input type="text" placeholder="Titel" id="datasetTitle" class="form-input" value="<?php echo $dataset->getTitle(); ?>">
    </div>

    <div class="row">
        <label for="datasetLogin">Login</label>
        <input type="text" placeholder="Login" id="datasetLogin" class="form-input" value="<?php echo $dataset->getLogin(); ?>">
    </div>

    <div class="row">
        <label for="datasetPassword">Passwort</label>
        <input type="text" placeholder="Password" id="datasetPassword" class="form-input" value="<?php echo $dataset->getPassword(); ?>">
    </div>

    <div class="row">
        <label for="datasetURL">URL</label>
        <input type="text" placeholder="URL" id="datasetURL" class="form-input" value="<?php echo $dataset->getUrl(); ?>">
    </div>

    <div class="row">
        <label for="datasetProject">Projekt</label>
        <input type="text" placeholder="Projekt" id="datasetProject" class="form-input" value="<?php echo $dataset->getProject(); ?>">
    </div>

    <button id="updateDatasetButton"  class="button">Aktualisieren</button>
    <?php include_once ELEMENTS_DIR . 'ajaxLoader.php'; ?>
</div>

<script src="/Js/editDatasetView.min.js">

</script>


<?php
$dataset->encrypt();
?>
