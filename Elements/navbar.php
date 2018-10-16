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

    <div id="navbar-trigger">

    </div>

    <div class="container">
        <form action="" method="post">
            <input type="search" id="search-input" name="search" placeholder="Suche">
        </form>
        <ul class="nav-1">

            <li class="nav1-item">
                <a class="nav1-link" href="/export" title="Exportieren"><i class="fas fa-file-export"></i> Exportieren</a>
            </li>

            <li class="nav1-item">
                <a id="clearCacheButton" class="nav1-link" title="Cache löschen"><i class="fas fa-broom"></i> Cache leeren</a>
            </li>

            <?php if ($page == 'account') { ?>

                <?php
                if (!apcu_exists('updateAvailable')) {
                    $updateAvailable = $system->updateAvailable();
                    apcu_store('updateAvailable', $updateAvailable, 1800);
                } else {
                    $updateAvailablef = apcu_fetch('updateAvailable');
                }
                ?>


                <li class="nav1-item">
                    <a class="nav1-link <?= $updateAvailable !== false ? 'orange' : '' ?>" href="/checkforupdate"><i class="fas fa-sync-alt"></i> Updates suchen</a>
                </li>

            <?php } ?>

            <?php if ($sessionAccessLevel === SESSION_ADMIN) { ?>
                <li class="nav1-item">
                    <a class="nav1-link" id="newUserLink" href="#newUser"><i class="fas fa-user-plus"></i> Benutzer hinzufügen</a>
                </li>
            <?php } ?>

            <?php if ($page == 'account') { ?>

                <li class="nav1-item">
                    <a class="nav1-link" id="createNewDatasetButton" href="#newDataset"><i class="fas fa-plus"></i> Neuer Datensatz</a>
                </li>

            <?php } ?>

            <li class="nav1-item">
                <a class="nav1-link" id="settingsLink" href="/settings"><i class="fas fa-cog"></i> Einstellungen</a>
            </li>



        </ul>
        <form method="post" action="/logout" >
            <input type="submit" value="Ausloggen">
        </form>
    </div>
</div>




