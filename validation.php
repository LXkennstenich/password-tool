<?php
/* @var $factory Factory */
/* @var $session Session */
/* @var $account Account */
if (!defined('PASSTOOL')) {
    die();
}

$accountActivated = false;
$account = $factory->getAccount();
$username = filter_var($_GET['u'], FILTER_VALIDATE_EMAIL);
$token = htmlspecialchars(strip_tags($_GET['t']));

if ($account->validate($username, $token)) {
    $accountActivated = true;
}
?>


<?php if ($accountActivated) { ?>

    <div class="installation-container">
        <p>Account erfolgreich aktiviert!</p>
    </div>

<?php } ?>



