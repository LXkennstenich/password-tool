<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
if (!defined('PASSTOOL')) {
    die();
}

define('SYSTEM_MODE', 'DEV');


if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(__FILE__) . '/');
}

if (!defined('PAGE_DIR')) {
    define('PAGE_DIR', ROOT_DIR . 'Pages/');
}

if (!defined('UPDATE_DIR')) {
    define('UPDATE_DIR', ROOT_DIR . 'update/');
}

if (!defined('LOG_DIR')) {
    define('LOG_DIR', ROOT_DIR . 'logs/');
}

if (!defined('CLASS_DIR')) {
    define('CLASS_DIR', ROOT_DIR . 'Classes/');
}

if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', ROOT_DIR . 'Config/');
}

if (!defined('CONTROLLER_DIR')) {
    define('CONTROLLER_DIR', ROOT_DIR . 'Controller/');
}

if (!defined('CSS_DIR')) {
    define('CSS_DIR', ROOT_DIR . 'Css/');
}

if (!defined('ELEMENTS_DIR')) {
    define('ELEMENTS_DIR', ROOT_DIR . 'Elements/');
}

if (!defined('JS_DIR')) {
    define('JS_DIR', ROOT_DIR . 'Js/');
}

if (!defined('VIEW_DIR')) {
    define('VIEW_DIR', ROOT_DIR . 'View/');
}

if (!defined('ICON_DIR')) {
    define('ICON_DIR', ROOT_DIR . 'Icons/');
}

if (!defined('KEY_DIR')) {
    define('KEY_DIR', ROOT_DIR . 'Keys/');
}

if (!defined('SESSION_ADMIN')) {
    define('SESSION_ADMIN', '5');
}

if (!defined('SESSION_USER')) {
    define('SESSION_USER', '0');
}
    






