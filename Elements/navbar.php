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
?>
<div id="navbar">
    <form method="post" action="/logout" >
        <input type="submit" value="Ausloggen">
    </form>
    <form action="" method="post">
        <input type="search" id="search-input" name="search">
        <input type="submit" id="search-button" class="button" value="Suchen">
    </form>
    <ul class="nav-1">
        <li class="nav1-item">
            <a class="nav1-link" id="createNewDatasetButton" href="#newDataset">Neuen Datensatz erstellen</a>
        </li>
        <li class="nav1-item">
            <a class="nav1-link" id="settingsLink" href="/settings"><i class="fas fa-cog"></i></a>
        </li>
    </ul>
</div>


