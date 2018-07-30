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
/* @var $account Account */
/* @var $g \Sonata\GoogleAuthenticator\GoogleAuthenticator */
if (!defined('PASSTOOL')) {
    die();
}
$secret = $account->querySecretKey($userID);
$g = $factory->getGoogleAuthenticator();
$codeInput = $request->code;

if ($g->checkCode($secret, $codeInput)) {
    if ($session->updateAuthenticator(1)) {
        echo "1";
    }
} else {
    echo "0";
}
