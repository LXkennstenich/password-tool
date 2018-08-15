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

$debugger = $factory->getDebugger();

try {
    $username = $request->username;
    $accessLevel = $request->accessLevel;

    $account = $factory->getAccount();

    $account->setUsername($username);
    $account->setAccessLevel($accessLevel);
    $account->generateProperties();

    if ($account->save()) {
        echo "1";
    } else {
        echo "Fehlgeschlagen";
    }
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}


