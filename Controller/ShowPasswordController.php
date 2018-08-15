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

    $id = $request->id;

    $dataset = $factory->getDataset($id, $userID);

    $dataset->decrypt();

    echo $dataset->getPassword();

    $dataset->encrypt();

    unset($dataset);
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}


