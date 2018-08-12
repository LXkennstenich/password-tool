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
/* @var $system System */
if (!defined('PASSTOOL')) {
    die();
}

$cronActive = $request->cronActive;
$cronClearSessionData = $request->clearSessionData;
$cronRecrypt = $request->recrypt;

$system->update($cronActive, $cronClearSessionData, $cronRecrypt);
echo "1";


