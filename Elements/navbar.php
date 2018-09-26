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
<div id="navbar">
    <a id="logo" href="/"><img src="/Icons/logo-backend.png"></a>
    <form action="" method="post">
        <input type="search" id="search-input" name="search" placeholder="Suche">
    </form>
    <ul class="nav-1">

        <li class="nav1-item">
            <a class="nav1-link" href="/export" title="Exportieren"><i class="fas fa-sync-alt"></i></a>
        </li>

        <li class="nav1-item">
            <a class="nav1-link" href="/clearcache" title="Cache löschen"><i class="fas fa-sync-alt"></i></a>
        </li>

        <?php if ($page == 'account') { ?>

            <li class="nav1-item">
                <a class="nav1-link" href="/checkforupdate"><i class="fas fa-sync-alt" style="<?= $system->updateAvailable() ? 'color:orange;' : '' ?>"></i></a>
            </li>

        <?php } ?>

        <?php if ($sessionAccessLevel === SESSION_ADMIN) { ?>
            <li class="nav1-item">
                <a class="nav1-link" id="newUserLink" href="#newUser"><i class="fas fa-user-plus"></i></a>
            </li>
        <?php } ?>

        <?php if ($page == 'account') { ?>

            <li class="nav1-item">
                <a class="nav1-link" id="createNewDatasetButton" href="#newDataset"><i class="fas fa-plus"></i></a>
            </li>

        <?php } ?>

        <li class="nav1-item">
            <a class="nav1-link" id="settingsLink" href="/settings"><i class="fas fa-cog"></i></a>
        </li>

    </ul>
    <form method="post" action="/logout" >
        <input type="submit" value="Ausloggen">
    </form>
</div>



