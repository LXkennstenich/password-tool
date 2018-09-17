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
$datasets = null;

if ($searchTerm != '') {
    $datasets = $factory->searchDatasets($userID, strtolower($searchTerm));
} else {
    $datasets = $factory->getDatasets($userID);
}

include_once ELEMENTS_DIR . 'ajaxLoader.php';
?>

<div class="container">

    <?php
    if (isset($datasets)) {

        foreach ($datasets as $dataset) {
            ?>

            <?php
            $dataset->getEncryption()->setUserID($userID);
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
                            ?>
                        </p>
                        <i class="far fa-eye" onclick="showPassword(this)"></i>
                        <i class="fas fa-copy copy-password"></i>

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
                unset($dataset);
                ?>
            </div>

            <?php
        }

        unset($datasets);
    } else if ($searchTerm != '' && sizeof($datasets) <= 0) {
        ?>

        <p>Ihre Suche ergab keine Treffer</p>

    <?php } ?>
</div>


<script src = "/Js/datasetView.min.js" async=""></script>
