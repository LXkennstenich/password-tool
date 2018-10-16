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


$sessionUIDEncoded = $encryption->systemEncrypt($sessionUID);
$sessionTokenEncrypted = $encryption->systemEncrypt($sessionToken);
$sessionTimestampEncrypted = $encryption->systemEncrypt($sessionTimestamp);
$sessionIpAddressEncoded = $encryption->systemEncrypt($sessionIP);
$sessionHostEncrypted = $encryption->systemEncrypt($host);
$sessionUserAgentEncrypted = $encryption->systemEncrypt($userAgent);
?>

<input type="hidden" id="searchTerm" value="<?php echo $searchTerm; ?>" />
<input type="hidden" id="sessionUID" value="<?php echo $sessionUIDEncoded; ?>" />
<input type="hidden" id="sessionToken" value="<?php echo $sessionTokenEncrypted; ?>" />
<input type="hidden" id="sessionTimestamp" value="<?php echo $sessionTimestampEncrypted; ?>" />
<input type="hidden" id="sessionIpAddress" value="<?php echo $sessionIpAddressEncoded; ?>" />
<input type="hidden" id="sessionHost" value="<?php echo $sessionHostEncrypted; ?>" />
<input type="hidden" id="sessionUserAgent" value="<?php echo $sessionUserAgentEncrypted; ?>" />
<input type="hidden" id="requestTimestamp" value="<?php echo microtime(true); ?>" />

<script type="text/javascript"  src="/Js/js-globals.min.js" nonce="<?php echo $nonce; ?>"></script>