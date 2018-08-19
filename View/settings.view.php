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
?>

<div id="settings">

    <?php if ($sessionAccessLevel === SESSION_ADMIN) { ?>
        <div class="row">
            <label for="cronRecrypt">Datensätze vom Cron-Job neu Verschlüsseln lassen</label>
            <input type="checkbox" placeholder="" id="cronRecrypt" class="form-input" <?= $system->cronRecrypt() === true ? 'checked' : '' ?>  >
        </div>

        <div class="row">
            <label for="cronClearSessionData">Clear Session Data</label>
            <input type="checkbox" placeholder="" id="cronClearSessionData" class="form-input" <?= $system->cronClearSessionData() === true ? 'checked' : '' ?> >
        </div>

        <div class="row">
            <label id="cronLast">Letzte Cron ausführung: <?php echo $system->cronLast(); ?></label>
        </div>

        <div class="row">
            <label for="datasetPasswordLength">Letzte Cron ausführung erfolgreich: <?php echo $system->cronLastSuccess() === true ? 'Ja' : 'Nein'; ?></label>
        </div>

        <div class="row checkbox">
            <label for="cronActive">Cron aktiv: </label>
            <input type="checkbox" id="cronActive" name="lowerCharacters" <?= $system->isCronEnabled() === true ? 'checked' : '' ?>  >
        </div>

        <div class="row checkbox">
            <label for="cronUrl">Cron-Url</label>
            <input type="text" value="<?php echo $system->cronUrl(); ?>" id="cronUrl">
        </div>
        <input type="button" id="updateSystemSettingsButton"  class="form-button" value="Speichern">


    <?php } ?>
    <?php include_once ELEMENTS_DIR . 'ajaxLoader.php'; ?>
</div>
