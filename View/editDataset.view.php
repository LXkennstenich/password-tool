
<?php
/* @var $dataset Dataset */

if (!defined('PASSTOOL')) {
    die();
}

$dataset->decrypt();
?>

<div id="editDataset">
    <input type="hidden" id="datasetUserID" value="<?php echo $_SESSION['UID']; ?>" />
    <input type="text" placeholder="Titel" id="datasetTitle" class="form-input" value="<?php echo $dataset->getTitle(); ?>"/>
    <input type="text" placeholder="Login" id="datasetLogin" class="form-input" value="<?php echo $dataset->getLogin(); ?>"/>
    <input type="text" placeholder="Password" id="datasetPassword" class="form-input" value="<?php echo $dataset->getPassword(); ?>"/>
    <input type="text" placeholder="URL" id="datasetURL" class="form-input" value="<?php echo $dataset->getUrl(); ?>"/>
    <input type="text" placeholder="Projekt" id="datasetProject" class="form-input" value="<?php echo $dataset->getProject(); ?>"/>
    <input type="button" id="createDatasetButton"  class="form-button" value="Speichern"/>
</div>

<?php
$dataset->encrypt();
?>
