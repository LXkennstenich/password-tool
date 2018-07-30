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
if (!defined('PASSTOOL')) {
    die();
}
/* @var $factory Factory */
/* @var $session Session */
/* @var $dataset Dataset */
$dataset = $factory->createDataset();

$id = $request->id;

$dataset->setID($id);
$dataset->setUserID($userID);
$dataset->load();

if ($dataset->delete()) {
    echo "1";
} else {
    echo "0";
}