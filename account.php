<?php
/* @var $factory Factory */
/* @var $session Session */
if (!defined('PASSTOOL')) {
    die();
}

if ($session->isAuthenticated() == false) {
    $factory->redirect('login');
}

include_once ELEMENTS_DIR . 'navbar.php';
?>


<div id="info">

</div>

<div id="main">
    <div class="loading-div">
        <img src="Icons/ajax-loader.gif" />
    </div>
    <div class="ajax-message">

    </div>
    <div id="content-wrapper">

    </div>
    <script>
        var request = {};
        request.action = 'View';
        request.file = 'dataset';
        request.tk = document.getElementById('session-token').value;
        request.ts = document.getElementById('session-timestamp').value;
        request.ipaddress = document.getElementById('session-ipaddress').value;
        request.uid = document.getElementById('session-uid').value;
        $("#content-wrapper").load("Ajax.php", {"request": JSON.stringify(request)});
    </script>
    <?php include_once VIEW_DIR . 'newDataset.view.php'; ?>
</div>





<script src = "/Js/user.js"></script>