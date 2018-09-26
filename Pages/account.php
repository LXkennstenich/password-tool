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

if (function_exists('sodium_add')) {
    echo 'sodium aktiv';
}
?>

<div id="content-wrapper">

</div>

<script>
    var request = {};
    request.action = 'View';
    request.file = 'dataset';
    request.tk = token;
    request.ts = timestamp;
    request.ipaddress = ipaddress;
    request.uid = uid;
    request.searchTerm = searchTerm;
    $("#content-wrapper").load(getAjaxUrl(), {"request": JSON.stringify(request)});

</script>

<?php
$projects = $factory->getProjects($sessionUID);
?>

<?php if (sizeof($projects) > 0) { ?>


    <form class="nav nav-list" method="post">
        <span class="nav-header">Projekte</span>
        <?php foreach ($projects as $project) { ?>
            <input type="submit" class="project-list-link" name="search" value="<?php echo $project; ?>">
        <?php } ?>
        </ul>
    </form>


<?php } else { ?>

    <div class="alert">Keine Projekte gefunden</div>

    <?php
}


include_once ELEMENTS_DIR . 'ajaxLoader.php';












