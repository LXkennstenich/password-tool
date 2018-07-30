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

$username = $request->username;
$oldPassword = $request->oldPassword;
$newPassword = $request->newPassword;

if ($account->updatePassword($userID, $username, $oldPassword, $newPassword)) {
    echo "1";
} else {
    echo "0";
}

