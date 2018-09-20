<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
interface ItemInterface {

    public function exists();

    public function insert();

    public function update();

    public function delete();

    public function load();

    public function getColumnNames();
}
