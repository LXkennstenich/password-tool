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



