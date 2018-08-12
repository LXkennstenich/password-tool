
<?php
if (!defined('PASSTOOL')) {
    die();
}
?>

<div id="newDataset">
    <div class="row">
        <label for="datasetTitle">Titel</label>
        <input type="text" placeholder="" id="datasetTitle" class="form-input">
    </div>

    <div class="row">
        <label for="datasetLogin">Login</label>
        <input type="text" placeholder="" id="datasetLogin" class="form-input">
    </div>

    <div class="row">
        <label for="datasetPassword">Passwort</label>
        <input type="text" placeholder="" id="datasetPassword" class="form-input">
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

    <a class="button" id="generatePasswordButton">Passwort generieren</a>

    <div class="row">
        <label for="datasetURL">URL</label>
        <input type="text" placeholder="" id="datasetURL" class="form-input">
    </div>

    <div class="row">
        <label for="datasetProject">Projekt</label>
        <input type="text" placeholder="" id="datasetProject" class="form-input">
    </div>
    <input type="button" id="createDatasetButton"  class="form-button" value="Speichern">
</div>
<?php include_once ELEMENTS_DIR . 'ajaxLoader.php'; ?>

