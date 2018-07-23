<?php ?>
<!Doctype HTML>
<html lang="de-DE">
    <head>
        <title>Password Tool</title>
        <link rel="stylesheet" href="/Css/main.css" />
        <link rel="stylesheet" href="/Libs/fancybox/dist/jquery.fancybox.min.css" />
        <script src="/Js/jquery.min.js"></script>
        <script src="/Libs/fancybox/dist/jquery.fancybox.min.js"></script>
    </head>
    <body class="<?php echo $page; ?>">
        <input type="hidden" id="session-ipaddress" value="<?= $ts = isset($_SESSION['IP']) ? base64_encode($_SESSION['IP']) : base64_encode($_SERVER['REMOTE_ADDR']); ?>" >
        <input type="hidden" id="session-token" value="<?= $token = isset($_SESSION['TK']) ? base64_encode($_SESSION['TK']) : null; ?>" >
        <input type="hidden" id="session-timestamp" value="<?= $ts = isset($_SESSION['TS']) ? base64_encode($_SESSION['TS']) : null; ?>" >
        <input type="hidden" id="session-uid" value="<?= $ts = isset($_SESSION['UID']) ? base64_encode($_SESSION['UID']) : null; ?>" >
