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
?>

<div id="newUser" class="form">
    <div class="row">
        <label for="newUsername">Benutzername (E-Mail)</label>
        <input type="text" id="newUsername" value="">
    </div>

    <div class="row">
        <label for="newAccessLevel">Berechtigung</label>
        <select id="newAccessLevel">
            <option class="option" value="5">Administrator</option>
            <option class="option" value="0">Benutzer</option>
        </select>
    </div>

    <a class="button" id="newUserButton">Neuen Benutzer hinzufügen</a>
</div>

<?php include_once ELEMENTS_DIR . 'ajaxLoader.php'; ?>