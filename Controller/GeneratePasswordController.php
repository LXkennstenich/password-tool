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
/* @var $encryption Encryption */
if (!defined('PASSTOOL')) {
    die();
}

$encryption = $factory->getEncryption();
$length = $request->length;
$lowerCharacters = $request->lowerCharacters == 1 ? true : false;
$highCharacters = $request->highCharacters == 1 ? true : false;
$numbers = $request->numbers == 1 ? true : false;
$specialChars = $request->specialChars == 1 ? true : false;

$password = $encryption->generatePassword($length, $lowerCharacters, $highCharacters, $numbers, $specialChars);

echo $password;
