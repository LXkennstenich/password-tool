<?php
if (!defined('PASSTOOL')) {
    die();
}

/* @var $factory Factory */
$data = json_decode($_POST['request']);
$userID = base64_decode($data->uid);
$searchTerm = $data->searchTerm;
if ($searchTerm != '') {
    $searchString = filter_var($searchTerm, FILTER_SANITIZE_STRING);
    $datasets = $factory->searchDatasets($userID, strtolower($searchString));
} else {
    $datasets = $factory->getDatasets($userID);
}
?>
<div class="container">
    <div class="ajax-loading">

    </div>
    <div class="ajax-response">

    </div>

    <?php foreach ($datasets as $dataset) { ?>

        <?php
        $dataset->decrypt();
        $ID = $dataset->getID();
        $userID = $dataset->getUserID();
        $title = $dataset->getTitle();
        $login = $dataset->getLogin();
        $password = $dataset->getPassword();
        $url = $dataset->getUrl();
        $project = $dataset->getID;
        ?>

        <div id="dataset-<?php echo $ID; ?>" class="dataset">
            <input type="hidden" class="datasetID" value="<?php echo $ID; ?>">
            <input type="hidden" class="datasetUserID" value="<?php echo $userID; ?>">
            <h2><span class="dataset-title"><?php echo $title; ?></span><a class="edit-dataset-link" href="edit/?id=<?php echo $ID; ?>" ><i class="fas fa-pen"></i></a></h2>
            <div class="content">
                <div class="row">
                    <label>Login:</label>
                    <p><?php echo $login; ?></p>
                </div>
                <div class="row">
                    <label>Password:</label>
                    <p>
                        <?php
                        $length = (int) strlen($password);

                        for ($i = 0; $i <= $length; $i++) {
                            echo '*';
                        }
                        ?>
                    </p>
                    <i class="far fa-eye" onclick="showPassword(this)"></i>
                    <i class="fas fa-copy" onclick="copy(this)"></i>
                </div>
                <div class="row">
                    <label>URL:</label>
                    <p><?php echo $url; ?></p>
                </div>
                <div class="row">
                    <label>Projekt:</label>
                    <p><?php echo $project; ?></p>
                </div>
            </div>
            <?php
            $dataset->encrypt();
            ?>
            <input type="hidden" class="datasetEncrypted" value="<?php echo $dataset->getPassword(); ?>" />
            <input type="hidden" class="datasetHidden" value="<?php
                   $length = (int) strlen($password);

                   for ($i = 0; $i <= $length; $i++) {
                       echo '*';
                   }
                   ?>" />
        </div>

        <?php
    }
    ?>
</div>
<script>
    $('.dataset-title').on("click", function () {
        if ($(this).hasClass('open') == false) {
            $(this).addClass('open');
            $(this).parent('h2').next('.content').slideDown('slow');
        } else {
            $(this).removeClass('open');
            $(this).parent('h2').next('.content').slideUp('slow');
        }
    });


    /*
     $('.edit-dataset-link').bind('click touch', function () {
     $.fancybox({
     width: 400,
     height: 400,
     autoSize: false,
     href: 'Ajax.php',
     type: 'ajax',
     ajax: {
     settings: {
     "request": {JSON.stringify(request)}
     }
     }
     }
     );
     });
     */


</script>