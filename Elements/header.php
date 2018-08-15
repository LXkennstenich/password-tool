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
<!DOCTYPE html>
<html lang="de-DE">
    <head>
        <title>Password Tool</title>
        <link rel="stylesheet" href="/Css/main.min.css" />
        <link rel="stylesheet" href="/Libs/fancybox/dist/jquery.fancybox.min.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
        <script src="/Js/jquery.min.js"></script>
        <?php if ($session->isAuthenticated() !== false) { ?>
            <script src="/Libs/fancybox/dist/jquery.fancybox.min.js"></script>
            <script src = "/Js/user.min.js"></script>
        <?php } ?>
    </head>
    <body class="<?php echo $page; ?>">

