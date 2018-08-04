<?php
if (!defined('PASSTOOL')) {
    die();
}
?>

<div id="settings">

    <?php if ($sessionAccessLevel === SESSION_ADMIN) { ?>
        <div class="row">
            <label for="cronRecrypt">Datensätze vom Cron-Job neu Verschlüsseln lassen</label>
            <input type="checkbox" placeholder="" id="cronRecrypt" class="form-input">
        </div>

        <div class="row">
            <label for="cronClearSessionData">Login</label>
            <input type="checkbox" placeholder="" id="cronClearSessionData" class="form-input">
        </div>

        <div class="row">
            <label id="cronLast">Letzte Cron ausführung: <?php echo $system->getCronLast(); ?></label>
        </div>

        <div class="row">
            <label for="datasetPasswordLength">Letzte Cron ausführung erfolgreich: <?php echo $system->getCronLastSuccess(); ?></label>
        </div>

        <div class="row checkbox">
            <label for="cronActive">Cron aktiv: </label>
            <input type="checkbox" value="1" id="cronActive" name="lowerCharacters" checked="" >
        </div>

        <div class="row checkbox">
            <label for="cronToken">Cron-Token</label>
            <input type="text" id="cronToken" value="<?php echo $system->getCronToken(); ?>" >
        </div>

        <div class="row checkbox">
            <label for="cronUrl">Cron-Url</label>
            <input type="text" value="<?php echo $system->getCronUrl(); ?>" id="cronUrl">
        </div>

        <input type="button" id="createDatasetButton"  class="form-button" value="Speichern">
    <?php } ?>

</div>