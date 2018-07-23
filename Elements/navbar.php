<?php
/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
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
            <a class="nav1-link"  href="#newDataset">Neuen Datensatz erstellen</a>
        </li>
        <li class="nav1-item">
            <a class="nav1-link" id="settingsLink" href="#settings">Einstellungen</a>
        </li>
    </ul>
</div>

