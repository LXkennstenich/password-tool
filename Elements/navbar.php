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
    <a id="logo" href="/"><img src="/Icons/logo-backend.png"></a>
    <form action="" method="post">
        <input type="search" id="search-input" name="search" placeholder="Suche">
    </form>
    <ul class="nav-1">
        <li class="nav1-item">
            <?php if ($page == 'account') { ?>
                <a class="nav1-link" id="createNewDatasetButton" href="#newDataset"><i class="fas fa-plus"></i></a>
            <?php } ?>
        </li>
        <li class="nav1-item">
            <a class="nav1-link" id="settingsLink" href="/settings"><i class="fas fa-cog"></i></a>
        </li>
    </ul>
    <form method="post" action="/logout" >
        <input type="submit" value="Ausloggen">
    </form>
</div>


