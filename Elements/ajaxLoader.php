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


<div class="loading-div">
    <img src="/Icons/ajax-loader.gif" />
</div>
<div class="ajax-message">

</div>
<script>
    var $loading = $('.loading-div img').hide();
    var $ajaxMessage = $('.ajax-message').hide();
    $(document)
            .ajaxStart(function () {
                $loading.show();
            })
            .ajaxStop(function () {
                $loading.hide();
                $ajaxMessage.show();
            });
</script>