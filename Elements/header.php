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
<!DOCTYPE html>
<html lang="de-DE">
    <head>
        <meta charset="UTF-8"/>
        <title>Password Tool</title>
        <link rel="stylesheet" type="text/css" href="/Css/main.min.css" />
        <link rel="stylesheet" type="text/css" href="/Libs/fancybox/dist/jquery.fancybox.min.css" />
        <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
        <script src="/Js/jquery.min.js"></script>
        <?php if ($session->isAuthenticated() !== false) { ?>
            <script src="/Libs/fancybox/dist/jquery.fancybox.min.js"></script>
            <script src = "/Js/user.min.js"></script>
        <?php } ?>
    </head>
    <body class="<?php echo $page; ?>">

