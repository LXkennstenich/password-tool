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

$debugger = $factory->getDebugger();
?>


<div id="content-wrapper">

</div>
<script type="text/javascript" nonce="<?php echo $nonce; ?>">
    var request = {};
    request.action = 'UpdateCheck';
    request.tk = token;
    request.ts = timestamp;
    request.ipaddress = ipaddress;
    request.uid = uid;
    request.searchTerm = searchTerm;
    $("#content-wrapper").load(getAjaxUrl(), {"request": JSON.stringify(request)});
</script>

<?php
include_once ELEMENTS_DIR . 'ajaxLoader.php';



