<?php
/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var Factory $factory
 * @var Session $session
 */
/* @var $factory Factory  */
/* @var $session Session  */
?>
<!DOCTYPE html>
<html lang="de-DE">
    <head>
        <title>Password Tool</title>
        <link rel="stylesheet" href="/Css/main.css" />
        <link rel="stylesheet" href="/Libs/fancybox/dist/jquery.fancybox.min.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
        <script src="/Js/jquery.min.js"></script>
        <script src="/Libs/fancybox/dist/jquery.fancybox.min.js"></script>
        <?php if ($session->isAuthenticated() !== false) { ?>
            <script src = "/Js/user.min.js"></script>
        <?php } else { ?>
            <script src = "/Js/login.min.js"></script>
        <?php } ?>

    </head>
    <body class="<?php echo $page; ?>">
        <input type="hidden" id="session-ipaddress" value="<?= $ts = isset($_SESSION['IP']) ? base64_encode($_SESSION['IP']) : base64_encode($_SERVER['REMOTE_ADDR']); ?>" >
        <input type="hidden" id="session-token" value="<?= $token = isset($_SESSION['TK']) ? base64_encode($_SESSION['TK']) : null; ?>" >
        <input type="hidden" id="session-timestamp" value="<?= $ts = isset($_SESSION['TS']) ? base64_encode($_SESSION['TS']) : null; ?>" >
        <input type="hidden" id="session-uid" value="<?= $ts = isset($_SESSION['UID']) ? base64_encode($_SESSION['UID']) : null; ?>" >
        <input type="hidden" id="search-term" value="<?= $searchTerm = isset($_POST['search']) ? $_POST['search'] : null ?>">
