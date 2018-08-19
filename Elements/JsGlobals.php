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

$sessionUIDEncoded = base64_encode($sessionUID);
$sessionTokenEncrypted = $encryption->encrypt($sessionToken, $sessionUID);
$sessionTimestampEncrypted = $encryption->encrypt($sessionTimestamp, $sessionUID);
$sessionIpAddressEncoded = base64_encode($sessionIP);
$sessionHostEncrypted = $userID != '' ? $encryption->encrypt($host, $sessionUID) : $host;
$sessionUserAgentEncrypted = $userID != '' ? $encryption->encrypt($userAgent, $sessionUID) : $userAgent;
$jsVarSearchTerm = 'var searchTerm="' . $searchTerm . '";';
$jsVarUID = 'var uid="' . $sessionUIDEncoded . '";';
$jsVarToken = 'var token="' . $sessionTokenEncrypted . '";';
$jsVarTimestamp = 'var timestamp="' . $sessionTimestampEncrypted . '";';
$jsVarIpAddress = 'var ipaddress="' . $sessionIpAddressEncoded . '";';
$jsVarHost = 'var host="' . $sessionHostEncrypted . '";';
$jsVarUserAgent = 'var userAgent="' . $sessionUserAgentEncrypted . '";';
$jsVarRequestTimestamp = 'var requestTimestamp="' . microtime(true) . '";';
?>

<script>
<?php
echo $jsVarUID;
echo $jsVarToken;
echo $jsVarTimestamp;
echo $jsVarSearchTerm;
echo $jsVarIpAddress;
echo $jsVarHost;
echo $jsVarUserAgent;
echo $jsVarRequestTimestamp;
?>
    function getAjaxUrl() {
        var hostname = document.location.hostname;
        var url = "https://" + hostname + "/" + "Ajax";
        return url;
    }

    $.ajaxSetup({
        async: true,
        cache: true,
        url: getAjaxUrl()
    });

</script>