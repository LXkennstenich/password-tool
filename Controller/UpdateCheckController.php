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
if (!defined('PASSTOOL')) {
    die();
}
set_time_limit(0);
$debugger = $factory->getDebugger();

try {
    $system = $factory->getSystem();

    $commitHash = $system->updateAvailable();

    if ($commitHash !== false) {

        $path = $system->downloadUpdate($commitHash);

        if ($path !== false) {
            if ($system->installUpdate($path) !== false) {
                echo 'Erfolgreich aktualisiert';
            } else {
                echo 'Installation schief gelaufen';
            }
        }
    } else {
        echo 'no update available';
    }
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}
