
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

$dataset->decrypt();
?>

<div id="editDataset">
    <input type="hidden" id="edit-dataset-id" value="<?php echo $dataset->getID(); ?>" >
    <div class="row">
        <label for="datasetTitle">Titel</label>
        <input type="text" placeholder="" id="datasetTitle" class="form-input" value="<?php echo $dataset->getTitle(); ?>">
    </div>

    <div class="row">
        <label for="datasetLogin">Login</label>
        <input type="text" placeholder="" id="datasetLogin" class="form-input" value="<?php echo $dataset->getLogin(); ?>">
    </div>

    <div class="row">
        <label for="datasetPassword">Passwort</label>
        <input type="text" placeholder="" id="datasetPassword" class="form-input" value="<?php echo $dataset->getPassword(); ?>">
    </div>

    <div class="row">
        <label for="datasetPasswordLength">Passwortlänge</label>
        <input type="range" min="6" max="256" value="6" name="datasetPasswordLength" id="datasetPasswordLength" step="1" onchange="updatePasswordBox(this.value)">
        <input type="text" id="passwordLengthBox" value="6" onchange="updateRange(this.value)">
    </div>

    <div class="row checkbox">
        <label for="lowerCharacters">Kleinbuchstaben</label>
        <input type="checkbox" value="1" id="lowerCharacters" name="lowerCharacters" checked="" >
    </div>

    <div class="row checkbox">
        <label for="highCharacters">Großbuchstaben</label>
        <input type="checkbox" value="1" id="highCharacters" name="highCharacters" checked="" >
    </div>

    <div class="row checkbox">
        <label for="numbers">Zahlen</label>
        <input type="checkbox" value="1" id="numbers" name="numbers" checked="" >
    </div>

    <div class="row checkbox">
        <label for="specialchars">Sonderzeichen</label>
        <input type="checkbox" value="1" id="specialchars" name="specialchars" checked="" >
    </div>

    <a class="button" id="regeneratePasswordButton" >Passwort generieren</a>

    <div class="row">
        <label for="datasetURL">URL</label>
        <input type="text" placeholder="" id="datasetURL" class="form-input" value="<?php echo $dataset->getUrl(); ?>">
    </div>

    <div class="row">
        <label for="datasetProject">Projekt</label>
        <input type="text" id="datasetProject" class="form-input" value="<?php echo $dataset->getProject(); ?>">
    </div>

    <button id="updateDatasetButton"  class="button">Aktualisieren</button>
</div>

<script type="text/javascript" src="/Js/editDatasetView.min.js" nonce="<?php echo $nonce; ?>"></script>

