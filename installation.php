<?php
/**
 * installation.php
 * Installiert die Anwendung und erstellt den Admin Account
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 * @var $account Account
 */
if (!defined('PASSTOOL')) {
    die();
}

$accountCreated = false;
$account = $factory->getAccount();
$account->setUsername($factory->getDatabase()->getAdminEmail());

if ($account->exists() == false) {
    $account->setAccessLevel(5);
    $account->generateProperties();
    if ($account->save()) {
        $accountCreated = true;
    }
}
?>


<?php if ($accountCreated) { ?>

    <div class="installation-container">
        <p>Account erfolgreich erstellt!</p>
    </div>

<?php } ?>



