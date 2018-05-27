<?php

/**
 *
 * @author alexw
 */
interface SystemInterface {

    public function log();

    public function getClassDir();

    public function getControllerDir();

    public function getConfigDir();

    public function getStyleDir();

    public function getElementDir();

    public function getJSDir();

    public function getViewDir();
}
