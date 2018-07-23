<?php
if (!defined('PASSTOOL')) {
    die();
}

/* @var $factory Factory */
$data = json_decode($_POST['request']);
$userID = base64_decode($data->uid);

$datasets = $factory->getDatasets($userID);
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
        $title = $dataset->getTitle();
        $login = $dataset->getLogin();
        $password = $dataset->getPassword();
        $url = $dataset->getUrl();
        $project = $dataset->getID;
        ?>

        <div id="dataset-<?php echo $ID; ?>" class="dataset">
            <input type="hidden" id="datasetID" value="<?php echo $ID; ?>">
            <h2 class="dataset-title"><?php echo $title; ?></h2>
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
        </div>

        <?php
        $dataset->encrypt();
    }
    ?>
</div>