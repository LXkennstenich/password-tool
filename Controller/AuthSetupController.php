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

$value = filter_var($request->validate, FILTER_VALIDATE_INT);
if ($account->updateAuthSetup($userID, $value)) {
    echo "1";
} else {
    echo "0";
}
