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


<div class="loading-div">
    <img src="/Icons/ajax-loader.gif" />
</div>
<div class="ajax-message">

</div>
<script>
    var $loading = $('.loading-div').hide();
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