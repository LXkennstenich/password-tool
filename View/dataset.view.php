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

if ($searchTerm != '') {
    $datasets = $factory->searchDatasets($userID, strtolower($searchTerm));
} else {
    $datasets = $factory->getDatasets($userID);
}
?>

<div class="container">

    <?php foreach ($datasets as $dataset) { ?>

        <?php
        $dataset->decrypt();
        $ID = $dataset->getID();
        $userID = $dataset->getUserID();
        $title = $dataset->getTitle();
        $login = $dataset->getLogin();
        $password = $dataset->getPassword();
        $url = $dataset->getUrl();
        $project = $dataset->getProject();
        ?>

        <div id="dataset-<?php echo $ID; ?>" class="dataset">
            <input type="hidden" class="datasetID" value="<?php echo $ID; ?>">
            <input type="hidden" class="datasetUserID" value="<?php echo $userID; ?>">
            <h2><span class="dataset-title"><?php echo $title; ?></span><a class="edit-dataset-link" href="edit/?id=<?php echo $ID; ?>" ><i class="fas fa-pen"></i></a><a class="delete-dataset-link" datasetID="<?php echo $ID; ?>"><i id="deleteDataset"  class="far fa-trash-alt"></i></a></h2>
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

                        $dataset->encrypt();
                        ?>
                    </p>
                    <i class="far fa-eye" onclick="showPassword(this)"></i>
                    <i class="fas fa-copy" onclick="copy('<?php echo $dataset->getPassword(); ?>')"></i>
                    <?php $dataset->decrypt(); ?>
                </div>
                <div class="row">
                    <label>URL:</label>
                    <p><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></p>
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

<script src="/Js/datasetView.min.js">

</script>
