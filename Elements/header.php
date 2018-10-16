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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="application-name" content="PasswordTool">
        <meta name="robots" content="noindex,nofollow">
        <meta name="googlebot" content="noindex,nofollow">
        <title>Password Tool</title>
        <script type="text/javascript"  src="/Js/jquery.min.js" nonce="<?php echo $nonce; ?>"></script>
        <script type="text/javascript"  src="/Libs/fancybox/dist/jquery.fancybox.min.js" nonce="<?php echo $nonce; ?>"></script>
        <link rel="stylesheet" type="text/css" href="/Css/main.min.css" />
        <link rel="stylesheet" type="text/css" href="/Css/1024px.min.css" />
        <link rel="stylesheet" type="text/css" href="/Libs/fancybox/dist/jquery.fancybox.min.css" />
        <link rel="stylesheet" type="text/css" href="/Libs/fontawesome/css/all.min.css" />
    </head>
    <body class="<?php echo $page; ?>">

