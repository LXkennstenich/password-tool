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
<div class="footer-container">
    <?php if ($isSearch || $page != 'account' && $session->isAuthenticated() && $session->needAuthenticator() === false) { ?>
        <a href="/account" class="button">Zurück</a>
    <?php } ?>
</div>
</body>
</html>

