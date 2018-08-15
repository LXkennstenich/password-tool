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
?>

<div id="newUser">
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