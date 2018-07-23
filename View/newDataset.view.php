
<?php
if (!defined('PASSTOOL')) {
    die();
}
?>

<div id="newDataset">
    <input type="hidden" id="datasetUserID" value="<?php echo $_SESSION['UID']; ?>" />
    <input type="text" placeholder="Titel" id="datasetTitle" class="form-input"/>
    <input type="text" placeholder="Login" id="datasetLogin" class="form-input"/>
    <input type="text" placeholder="Password" id="datasetPassword" class="form-input"/>
    <input type="text" placeholder="URL" id="datasetURL" class="form-input"/>
    <input type="text" placeholder="Projekt" id="datasetProject" class="form-input"/>
    <input type="button" id="createDatasetButton"  class="form-button" value="Speichern"/>
</div>

