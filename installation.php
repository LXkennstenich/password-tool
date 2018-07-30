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
/* @var $encryption Encryption */
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

try {



    $database = $factory->getDatabase();

    if ($database->getSystemInstalled() === true) {
        $factory->redirect('login');
    }

    $databaseSetup = $database->setup();
    $defaultValueOptions = false;
    $defaultValueSystem = false;

    if ($databaseSetup) {
        $accountCreated = false;
        $account = $factory->getAccount();
        $username = $factory->getDatabase()->getAdminEmail();
        $account->setUsername($username);

        if ($account->exists() == false) {
            $account->setAccessLevel(5);
            $account->generateProperties();
            if ($account->save()) {
                $accountCreated = true;
            }
        }

        if ($accountCreated) {
            $ID = $database->getUserID($username);

            $defaultValueOptions = $database->insertDefaultValues($ID, 'options');
            $defaultValueSystem = $database->insertDefaultValues($ID, 'system');
        }

        $setup = $defaultValueOptions && $defaultValueSystem && $accountCreated && $databaseSetup ? true : false;

        if ($setup) {
            $database->systemIsInstalled();
        }
    }
} catch (Exception $ex) {
    if (SYSTEM_MODE == 'DEV') {
        $this->getDebugger()->printError($ex->getMessage());
    }

    $this->getDebugger()->log('Ausnahme: ' . $ex->getMessage() . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
}
?>

<?php if ($databaseSetup) { ?>

    <div class="installation-container">
        <p>Datenbank-Setup erfolgreich!</p>
    </div>

<?php } ?>

<?php if ($accountCreated) { ?>

    <div class="installation-container">
        <p>Account erfolgreich erstellt!</p>
    </div>

<?php } ?>

<?php if ($defaultValueOptions && $defaultValueSystem) { ?>

    <div class="installation-container">
        <p>Standardeinstellungen erstellt!</p>
    </div>

<?php } ?>

<?php if ($setup) { ?>

    <div class="installation-container">
        <p>System installiert!</p>
    </div>

<?php } ?>



