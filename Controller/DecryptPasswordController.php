<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
if (!defined('PASSTOOL')) {
    die();
}
$encryption = $factory->getEncryption();
$userID = $request->userID;
$encryptedPassword = $request->encrypted;
$decryptedPassword = $encryption->decrypt($encryptedPassword, $userID);

echo $decryptedPassword;
